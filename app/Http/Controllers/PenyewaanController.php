<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Sewa;
use App\Models\Member;
use App\Models\Ticket;
use App\Models\Setting;
use App\Models\Penyewaan;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\HistoryPenyewaan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class PenyewaanController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('permission:penyewaan-access');
    // }

    public function index()
    {
        $title = 'Data Transaksi Lainnya';
        $breadcrumbs = ['Master', 'Data Transaksi Lainnya'];
        $tickets = Sewa::get();

        return view('penyewaan.index', compact('title', 'breadcrumbs', 'tickets'));
    }

    public function get(Request $request)
    {
        if ($request->ajax()) {
            if ($request->tanggal) {
                $data = Penyewaan::whereDate('created_at', $request->tanggal)->orderBy('id', 'DESC');
            } else {
                $now = Carbon::now('Asia/Jakarta')->format('Y-m-d');
                $data = Penyewaan::whereDate('created_at', $now)->orderBy('id', 'DESC');
            }

            return DataTables::eloquent($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '<a href="' . route('penyewaan.print', $row->id) . '" class="btn btn-sm btn-primary">Print</a> ';

                    if (auth()->user()->can('penyewaan-delete')) {
                        $actionBtn .= '<button type="button" data-route="' . route('penyewaan.destroy', $row->id) . '" class="delete btn btn-danger btn-delete btn-sm">Delete</button>';
                    }
                    return $actionBtn;
                })
                ->editColumn('ticket', function ($row) {
                    return $row->sewa->name;
                })
                ->editColumn('harga', function ($row) {
                    return 'Rp. ' . number_format($row->sewa->harga, 0, ',', '.');
                })
                ->editColumn('jumlah', function ($row) {
                    return 'Rp. ' . number_format($row->jumlah, 0, ',', '.');
                })
                ->editColumn('keterangan', function ($row) {
                    return $row->keterangan ?? '-';
                })
                ->editColumn('start_time', function ($row) {
                    return $row->start_time ?? '-';
                })
                ->editColumn('end_time', function ($row) {
                    return $row->end_time ?? '-';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }
public function store(Request $request)
{
    try {
        $request->validate([
            'ticket' => 'required|numeric',
            'qty' => 'required|numeric',
            'metode' => 'required|in:cash,debit,qris,kredit,transfer,tap,lain-lain,qr,credit',
            'jumlah' => 'required|string',
            'harga_ticket' => 'nullable|string',
            'jam' => 'nullable|numeric|min:1',
            // Tambahkan validasi untuk bayar/kembali jika metodenya cash
            'bayar' => 'nullable|string',
            'kembali' => 'nullable|string',
            // Validasi RFID jika metode tap
            'name' => 'nullable|string',
        ]);

        DB::beginTransaction();
        $now = Carbon::now('Asia/Jakarta');
        $metode = $this->normalizePaymentMethod($request->metode);

        $ticketData = Sewa::findOrFail($request->ticket);
        $qty = (int) $request->qty;
        $startTime = $request->start_time ?: Carbon::now('Asia/Jakarta')->format('H:i');
        $endTime = $request->end_time;

        if ((int) ($ticketData->use_time ?? 0) === 1) {
            $jamSewa = (float) $request->input('jam', 0);
            if ($jamSewa <= 0) {
                DB::rollBack();
                return back()->with('error', 'Jam sewa wajib diisi untuk item berbasis waktu.');
            }

            $startTimeObj = Carbon::createFromFormat('H:i', $startTime, 'Asia/Jakarta');
            $endTime = $startTimeObj->copy()->addMinutes((int) round($jamSewa * 60))->format('H:i');
        }

        // Harga per-item dari form (editable). Jika kosong, fallback ke harga default item.
        $hargaTicketInput = (int) str_replace('.', '', (string) $request->harga_ticket);
        $defaultHargaPerItem = (int) $ticketData->harga + ((int) $ticketData->use_ppn === 1 ? (int) $ticketData->ppn : 0);
        $isNominalFlexible = (int) ($ticketData->is_nominal_flexible ?? 0) === 1;

        // Keamanan: jika dynamic price nonaktif, abaikan input harga dari client.
        $grossPerItem = $defaultHargaPerItem;
        if ($isNominalFlexible && $hargaTicketInput > 0) {
            $grossPerItem = $hargaTicketInput;
        }

        // Simpan struktur lama: bayar(net) + ppn, tapi total tetap mengikuti harga editable.
        $ppnPerItem = ((int) $ticketData->use_ppn === 1) ? (float) $ticketData->ppn : 0;
        $netPerItem = max(0, $grossPerItem - $ppnPerItem);
        $basePrice = $netPerItem * $qty;      // net total
        $ppnAmount = $ppnPerItem * $qty;      // pbjt total
        $grossAmountTotal = $grossPerItem * $qty; // total akhir sesuai form

        // ================== TRANSACTION ==================


        // ================== TRANSACTION DETAIL ==================
        // DetailTransaction::create([
        //     'transaction_id' => $transaction->id,
        //     'ticket_id' => $request->ticket,
        //     'qty' => $request->qty,
        //     'total' => $netPrice,        // total bersih
        //     'ppn' => $ppnAmount
        // ]);

        // ================== RENTAL / TAP / SALDO CHECK ==================
        if ($metode === 'tap') {

            $member = Member::where('rfid', $request->name)->first();
            if (!$member) {
                DB::rollBack();
                return back()->with('error', "Member tidak ditemukan");
            }

            $grossAmount = $grossAmountTotal;

            if ($member->saldo < $grossAmount) {
                DB::rollBack();
                return back()->with('error', "Saldo anda tidak mencukupi. Dibutuhkan: " . number_format($grossAmount));
            }

            $penyewaan = Penyewaan::create([
                'sewa_id' => $request->ticket,
                'qty' => $request->qty,
                'metode' => $metode,
                'jumlah' => $grossAmount, // Simpan gross amount di sini (total yang dipotong dari saldo)
                'bayar' => $grossAmount,
                'kembali' => 0,
                'keterangan' => $request->keterangan,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'user_id' => auth()->user()->id
            ]);

            $member->update([
                'saldo' => $member->saldo - $grossAmount
            ]);

            HistoryPenyewaan::create([
                'penyewaan_id' => $penyewaan->id,
                'member_id' => $member->id,
            ]);
            $rentalNoTrx = Transaction::nextNoTrxByType('rental', $now);

            $transaction = Transaction::create([
            'ticket_id' => $penyewaan->id,
            'user_id' => auth()->id(),
            'no_trx' => $rentalNoTrx,
            'ticket_code' => Transaction::buildTicketCodeByType('rental', $now, $rentalNoTrx),
            'transaction_type' => 'rental',
            'tipe' => 'individual',
                'metode' => $metode,
            'amount' => $request->qty,       // harga bersih (net price)
            'bayar' => $basePrice,       // Total dibayar (gross price)
            'status' => 'open',
            'is_active' => 1,
            'ppn' => $ppnAmount,         // simpan ppn untuk laporan
        ]);

            DB::commit();
            return back()->with('success', "Transaksi lainnya berhasil (TAP). Saldo terpotong sebesar " . number_format($grossAmount));

        } else { // CASH

            $bayarCash = (int) str_replace('.', '', $request->bayar);
            $kembaliCash = (int) str_replace('.', '', $request->kembali);

            // Simpan data Penyewaan
            $sewa = Penyewaan::create([
                'sewa_id' => $request->ticket,
                'qty' => $request->qty,
                'metode' => $metode,
                // jumlah harus mengikuti total akhir (sudah termasuk PBJT)
                'jumlah' => $grossAmountTotal,
                'bayar' => $bayarCash,
                'kembali' => $kembaliCash,
                'keterangan' => $request->keterangan,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'user_id' => auth()->user()->id

            ]);
            $rentalNoTrx = Transaction::nextNoTrxByType('rental', $now);

            $transaction = Transaction::create([
            'ticket_id' => $sewa->id,
            'user_id' => auth()->id(),
            'no_trx' => $rentalNoTrx,
            'ticket_code' => Transaction::buildTicketCodeByType('rental', $now, $rentalNoTrx),
            'transaction_type' => 'rental',
            'tipe' => 'individual',
                'metode' => $metode,
            'amount' => $request->qty,       // harga bersih (net price)
            'bayar' => $basePrice,       // Total dibayar (gross price)
            'status' => 'open',
            'is_active' => 1,
            'ppn' => $ppnAmount,         // simpan ppn untuk laporan
        ]);

            DB::commit();
            return $this->print($sewa->id);
        }


    } catch (\Throwable $th) {
        DB::rollBack();
        return back()->with('error', "Transaksi lainnya gagal. Error: " . $th->getMessage());
    }
}


    public function print($id)
    {
        $penyewaan = Penyewaan::find($id);
        $transaction = Transaction::where('ticket_id', $id)
            ->where('transaction_type', 'rental')
            ->latest()
            ->first();
        $setting = Setting::asObject();

        $logo = !empty($setting->logo) ? asset('/storage/' . $setting->logo) : 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('/images/rio.png')));
        $name = $setting->name ?? 'Ticketing';
        $ucapan = $setting->ucapan ?? 'Terima Kasih';
        $deskripsi = $setting->deskripsi ?? 'qr code hanya berlaku satu kali';
        $use = $setting->use_logo ?? false;

        return view('penyewaan.print', compact('penyewaan', 'transaction', 'logo', 'name', 'use', 'ucapan', 'deskripsi'));
    }

    public function destroy(Penyewaan $penyewaan)
    {
        try {
            $history = HistoryPenyewaan::where('penyewaan_id', $penyewaan->id)->first();

            if ($history) {
                $member = Member::find($history->member_id);
                $member->update([
                    'saldo' => $member->saldo + $penyewaan->jumlah
                ]);

                $history->delete();
                $penyewaan->delete();

                DB::commit();

                return back()->with('success', "Transaksi lainnya berhasil dihapus");
            } else {
                $penyewaan->delete();

                DB::commit();
                return back()->with('success', "Transaksi lainnya berhasil dihapus");
            }
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function create()
    {
        $title = 'Input Transaksi Lainnya Baru';
        $breadcrumbs = ['Master', 'Data Transaksi Lainnya', 'Input Baru'];
        $tickets = Sewa::get();

        return view('penyewaan.create', compact('title', 'breadcrumbs', 'tickets'));
    }

    private function normalizePaymentMethod(?string $method): string
    {
        $normalized = strtolower(trim((string) $method));

        return match ($normalized) {
            'qr' => 'qris',
            'credit', 'credit card', 'kartu kredit' => 'kredit',
            default => $normalized,
        };
    }
}
