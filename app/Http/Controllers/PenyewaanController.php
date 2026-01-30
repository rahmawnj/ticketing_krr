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
    public function __construct()
    {
        $this->middleware('permission:penyewaan-access');
    }

    public function index()
    {
        $title = 'Data Penyewaan';
        $breadcrumbs = ['Master', 'Data Penyewaan'];
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
            'metode' => 'required|string',
            'jumlah' => 'required|string',
            // Tambahkan validasi untuk bayar/kembali jika metodenya cash
            'bayar' => 'nullable|string',
            'kembali' => 'nullable|string',
            // Validasi RFID jika metode tap
            'name' => 'nullable|string',
        ]);

        DB::beginTransaction();

        $ticketData = Sewa::findOrFail($request->ticket);

        $basePrice = $ticketData->harga * $request->qty;

        $ppnAmount = 0;
        $netPrice = $basePrice;

        if ($ticketData->use_ppn) {
            $ppnAmount = (float) $ticketData->ppn * $request->qty;
        }

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
        if ($request->metode == 'tap') {

            $member = Member::where('rfid', $request->name)->first();
            if (!$member) {
                DB::rollBack();
                return back()->with('error', "Member tidak ditemukan");
            }

            $grossAmount = $basePrice;

            if ($member->saldo < $grossAmount) {
                DB::rollBack();
                return back()->with('error', "Saldo anda tidak mencukupi. Dibutuhkan: " . number_format($grossAmount));
            }

            $penyewaan = Penyewaan::create([
                'sewa_id' => $request->ticket,
                'qty' => $request->qty,
                'metode' => $request->metode,
                'jumlah' => $grossAmount, // Simpan gross amount di sini (total yang dipotong dari saldo)
                'bayar' => $grossAmount,
                'kembali' => 0,
                'user_id' => auth()->user()->id
            ]);

            $member->update([
                'saldo' => $member->saldo - $grossAmount
            ]);

            HistoryPenyewaan::create([
                'penyewaan_id' => $penyewaan->id,
                'member_id' => $member->id,
            ]);

              $transaction = Transaction::create([
            'ticket_id' => $penyewaan->id,
            'user_id' => auth()->id(),
            // Pastikan no_trx dihitung dengan benar
            'no_trx' => Transaction::max('no_trx') + 1,
            'ticket_code' => 'RENT/' . time(),
            'transaction_type' => 'rental',
            'tipe' => 'individual',
                'metode' => 'cash',
            'amount' => $request->qty,       // harga bersih (net price)
            'bayar' => $basePrice,       // Total dibayar (gross price)
            'status' => 'open',
            'is_active' => 1,
            'ppn' => $ppnAmount          // simpan ppn untuk laporan
        ]);

            DB::commit();
            return back()->with('success', "Penyewaan berhasil (TAP). Saldo terpotong sebesar " . number_format($grossAmount));

        } else { // CASH

            $bayarCash = (int) str_replace('.', '', $request->bayar);
            $kembaliCash = (int) str_replace('.', '', $request->kembali);

            // Simpan data Penyewaan
            $sewa = Penyewaan::create([
                'sewa_id' => $request->ticket,
                'qty' => $request->qty,
                'metode' => $request->metode,
                'jumlah' => $basePrice, // Simpan total yang dibayar (gross amount)
                'bayar' => $bayarCash,
                'kembali' => $kembaliCash,
                'user_id' => auth()->user()->id

            ]);

              $transaction = Transaction::create([
            'ticket_id' => $sewa->id,
            'user_id' => auth()->id(),
            // Pastikan no_trx dihitung dengan benar
            'no_trx' => Transaction::max('no_trx') + 1,
            'ticket_code' => 'TKT/' . time(),
            'transaction_type' => 'rental',
            'tipe' => 'individual',
                'metode' => 'cash',
            'amount' => $request->qty,       // harga bersih (net price)
            'bayar' => $basePrice,       // Total dibayar (gross price)
            'status' => 'open',
            'is_active' => 1,
            'ppn' => $ppnAmount          // simpan ppn untuk laporan
        ]);

            DB::commit();
            return $this->print($sewa->id);
        }


    } catch (\Throwable $th) {
        DB::rollBack();
        return back()->with('error', "Penyewaan gagal. Error: " . $th->getMessage());
    }
}


    public function print($id)
    {
        $penyewaan = Penyewaan::find($id);
        $setting = Setting::first();

        $logo = $setting ? asset('/storage/' . $setting->logo) : 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('/images/rio.png')));
        $name = $setting->name ?? 'Ticketing';
        $ucapan = $setting->ucapan ?? 'Terima Kasih';
        $deskripsi = $setting->deskripsi ?? 'qr code hanya berlaku satu kali';
        $use = $setting->use_logo ?? false;

        return view('penyewaan.print', compact('penyewaan', 'logo', 'name', 'use', 'ucapan', 'deskripsi'));
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

                return back()->with('success', "Penyewaan berhasil dihapus");
            } else {
                $penyewaan->delete();

                DB::commit();
                return back()->with('success', "Penyewaan berhasil dihapus");
            }
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function create()
    {
        $title = 'Input Penyewaan Baru';
        $breadcrumbs = ['Master', 'Data Penyewaan', 'Input Baru'];
        $tickets = Sewa::get();

        return view('penyewaan.create', compact('title', 'breadcrumbs', 'tickets'));
    }
}
