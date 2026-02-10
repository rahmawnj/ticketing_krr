<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Sewa;
use App\Models\Ticket;
use App\Models\Setting;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Exports\ReportExport;
use App\Models\DetailTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\Transaction\CreateTransactionRequest;
use Barryvdh\DomPDF\Facade\Pdf;

class TransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:transaction-access');
    }

    public function index()
    {
        $title = 'Data Transaction';
        $breadcrumbs = ['Master', 'Data Transaction'];
        $tickets = Ticket::get();

        return view('transaction.index', compact('title', 'breadcrumbs', 'tickets'));
    }

    public function get(Request $request)
    {
        if ($request->ajax()) {
            $tanggal = $request->tanggal ? $request->tanggal : Carbon::now()->format('Y-m-d');
            $transactionType = $request->transaction_type;

            // --- Query Building Logic ---
            if (auth()->user()->roles()->first()->id == 1) {
                $data = Transaction::where('is_active', 1)
                    ->whereDate('created_at', $tanggal)
                    ->orderBy('created_at', 'DESC');
            } else {
                $data = Transaction::where(['is_active' => 1, 'user_id' => auth()->user()->id])
                    ->whereDate('created_at', $tanggal)
                    ->orderBy('created_at', 'DESC');
            }

            if (!empty($transactionType)) {
                $data->where('transaction_type', $transactionType);
            }

            return DataTables::eloquent($data)
                ->addIndexColumn()

                ->addColumn('transaction_type', function ($row) {
                    return ucfirst($row->transaction_type);
                })
                ->addColumn('member_info', function ($row) {
                    if (!in_array($row->transaction_type, ['renewal', 'registration'])) {
                        return '-';
                    }

                    if ($row->member_id && $row->member) {
                        return $row->member->nama . ' - ' . $row->member->no_hp;
                    }

                    if (!empty($row->member_info)) {
                        return $row->member_info . ' (deleted)';
                    }

                    return '-';
                })
                ->editColumn('action', function ($row) {
                    $actionBtn = '<a href="' . route("transactions.print", $row->id) . '" class="btn btn-sm btn-primary"><i class="fas fa-print"></i></a> ';

                    $totalQty = $row->detail()->sum('qty');
                    $totalScanned = $row->detail()->sum('scanned');

                    if ($row->transaction_type == 'ticket' && $totalScanned < $totalQty) {
                        $routeFullScan = route('transactions.set_full_scan', $row->id);
                    $actionBtn .= '<button type="button"
                                                data-route="' . $routeFullScan . '"
                                                data-ticket-code="' . $row->ticket_code . '"
                                                class="btn btn-sm btn-warning ms-1 btn-full-scan"
                                                title="Set Full Scan">
                                                <i class="fas fa-check-double"></i>
                                            </button>';
                    }

                    if (in_array($row->transaction_type, ['registration', 'renewal'])) {
                        $actionBtn .= '<a href="' . route('transactions.invoice.pdf', $row->id) . '" class="btn btn-sm btn-secondary ms-1" target="_blank" title="Invoice Membership (PDF)"><i class="fas fa-file-invoice"></i></a>';
                    }

                    if (auth()->user()->can('transaction-delete')) {
                        $actionBtn .= '<button type="button" data-route="' . route('transactions.destroy', $row->id) . '" class="delete btn btn-danger btn-delete btn-sm ms-1"><i class="fas fa-trash"></i></button>';
                    }
                    return $actionBtn;
                })
                ->editColumn('disc', function ($row) {
                    return $row->discount . '%';
                })
                ->editColumn('discount', function ($row) {
                    return 'Rp. ' . number_format($row->detail()->sum('total') * $row->discount / 100, 0, ',', '.');
                })
                ->editColumn('scanned', function ($row) {
                    $hideForTypes = ['rental', 'renewal', 'registration'];

                    if (in_array($row->transaction_type, $hideForTypes)) {
                        return '-';
                    }

                    return '<span class="fw-bold fs-14px">' . $row->detail()->sum('scanned') . '</span>' . ' / ' . $row->detail()->sum('qty');
                })
->addColumn('user_name', function ($row) {
        return $row->user ? $row->user->name : 'N/A';
    })
                ->editColumn('ppn', function ($row): string {
                    return 'Rp. ' . number_format($row->ppn, 0, ',', '.');
                })

                ->editColumn('sisa', function ($row) {
                    return $row->detail()->sum('qty') - $row->detail()->sum('scanned');
                })

                ->editColumn('bayar', function ($row) {
                    return "Rp. " . number_format($row->bayar, 0, ',', '.');
                })

                ->editColumn('harga_ticket', function ($row) {
                    $disc = $row->bayar * $row->discount / 100;
                    return 'Rp. ' . number_format($row->bayar - $disc + $row->ppn, 0, ',', '.');
                })

                // Perubahan ada di sini: Pengecekan transaction_type sebelum menampilkan status
                ->addColumn('status_ticket', function ($row) {
                    if ($row->transaction_type != 'ticket') {
                        return '-';
                    }

                    return $row->status == 'open'
                        ? '<span class="badge bg-success text-capitalize">' . ucfirst($row->status) . '</span>'
                        : '<span class="badge bg-danger">' . ucfirst($row->status) . '</span>';
                })

                ->rawColumns(['action', 'status_ticket', 'scanned'])
                ->make(true);
        }
    }

    public function create()
    {
        $title = 'Input Ticket';
        $breadcrumbs = ['Master', 'Input Ticket'];
        $action = route('transactions.store');
        $method = 'POST';

        if (request('tipe') == 'sewa') {
            $tickets = Sewa::get();
        } else {
            $tickets = Ticket::get();
        }

        $now = Carbon::now()->format('Y-m-d');
        $lastTrx = Transaction::whereDate('created_at', $now)->latest()->first();
        if ($lastTrx) {
            $notrx = $lastTrx->no_trx + 1;
        } else {
            $notrx = 1;
        }

        $active = Transaction::whereDate('created_at', $now)->where(['is_active' => 0, 'user_id' => auth()->user()->id])->latest()->first();

        if ($active) {
            $transaction = $active;
        } else {
            $transaction = Transaction::create([
                'ticket_id' => 0,
                'user_id' => auth()->user()->id,
                'no_trx' => $notrx,
                'ticket_code' => 'INV' . Carbon::now('Asia/Jakarta')->format('/dmY') . '/' . rand(1000, 9999)
            ]);
        }

        $setting = Setting::first();


        $total = $transaction->detail()->sum('total') + $transaction->detail()->sum('ppn');


        return view('transaction.form', compact('title', 'breadcrumbs', 'action', 'method', 'transaction', 'tickets', 'total', 'setting'));
    }

    public function store(CreateTransactionRequest $request)
    {

        try {
            DB::beginTransaction();

            $transactions = [];

            $attr = $request->except('name', 'ticket', 'type_customer', 'print', 'jumlah');
            $ticket = Ticket::where('id', $request->ticket)->first();
            $now = Carbon::now()->format('Y-m-d');

            // $tipe = $request->type_customer;
            $tipe = 'group';
            $attr['ticket_id'] = $request->ticket;
            $attr['tipe'] = $tipe;
            $attr['nama_customer'] = $request->name;
            $attr['metode'] = $request->metode;
            $attr['cash'] = $request->cash;
            $attr['amount'] = 1;
            $attr['harga_ticket'] = $request->harga_ticket;
            $attr['kembalian'] = $request->kembalian;
            $attr['discount'] = ($request->harga_ticket * $request->discount) / 100;
            $attr['user_id'] = auth()->user()->id;
            $attr['transaction_type'] = 'ticket';
            $showPrint = $request->show_print;

            $print = 1;
            $transactions = [];
            $lastTrx = Transaction::whereDate('created_at', $now)->latest()->first();

            if ($lastTrx) {
                $notrx = $lastTrx->no_trx + 1;
            } else {
                $notrx = 1;
            }

            if ($tipe == 'individual') {
                for ($i = 0; $i < $request->amount; $i++) {
                    $attr['no_trx'] = $notrx++;
                    $attr['ticket_code'] = 'TKT' . Carbon::now('Asia/Jakarta')->format('dmY') . rand(1000, 9999);

                    $transaction = Transaction::create($attr);

                    $transactions[] = $transaction->id;
                }
            } else {
                $attr['no_trx'] = $notrx;
                $attr['ticket_code'] = 'GRP' . Carbon::now('Asia/Jakarta')->format('dmY') . rand(1000, 9999);
                $attr['amount'] = $request->amount;

                $transaction = Transaction::create($attr);

                $transactions = $transaction->id;
            }

            DB::commit();

            $tickets = [];

            if ($tipe == 'individual') {
                foreach ($transactions as $transaction) {
                    $tickets[] =   Transaction::where('id', $transaction)->first();
                }
            } else {
                $tickets[] = $transaction;
            }

            return view('transaction.print', compact('tipe', 'print', 'tickets', 'showPrint', '
            '));
        } catch (\Throwable $th) {
            return $th->getMessage();
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }

    public function show(Transaction $transaction)
    {
        return response()->json([
            'status' => 'success',
            'ticket' => $transaction
        ], 200);
    }

    public function edit(Transaction $transaction)
    {
        $title = 'Edit Transaction';
        $breadcrumbs = ['Master', 'Edit Transaction'];
        $action = route('transactions.update', $transaction->id);
        $method = 'PUT';

        return view('transaction.form', compact('title', 'breadcrumbs', 'action', 'method', 'transaction'));
    }

    public function update(CreateTransactionRequest $request, Transaction $transaction)
    {
        try {
            DB::beginTransaction();

            $transaction->update($request->all());

            DB::commit();

            return redirect()->route('transactions.index')->with('success', "Transaction berhasil diupdate");
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }

    public function destroy(Transaction $transaction)
    {
        try {
            DB::beginTransaction();

            foreach ($transaction->detail as $detail) {
                $detail->delete();
            }

            $transaction->delete();

            DB::commit();

            return redirect()->route('transactions.index')->with('success', "Transaction berhasil dihapus");
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }

   public function print(Transaction $transaction)
{
    $setting = Setting::first();

    // 1. TAMBAHKAN INI: Inisialisasi agar variabel selalu ada
    $tickets = [];

    $printMode = $setting->print_mode ?? 'per_qty';
    foreach ($transaction->detail as $detail) {
        if ($printMode === 'per_ticket') {
            $tickets[] = [
                "name" => $detail->ticket->name,
                "harga" => number_format($detail->ticket->harga + $detail->ppn, 0, ',', '.'),
                "ticket_code" => $detail->ticket_code,
                "qty" => $detail->qty,
            ];
            continue;
        }

        for ($i = 1; $i <= $detail->qty; $i++) {
            $tickets[] = [
                "name" => $detail->ticket->name,
                "harga" => number_format($detail->ticket->harga + $detail->ppn, 0, ',', '.'),
                "ticket_code" => $detail->ticket_code,
                "qty" => $detail->qty,
            ];
        }
    }

    $logo = $setting ? asset('/storage/' . $setting->logo) : 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('/images/rio.png')));
    $name = $setting->name ?? 'Ticketing';
    $ucapan = $setting->ucapan ?? 'Terima Kasih';
    $deskripsi = $setting->deskripsi ?? 'qr code hanya berlaku satu kali';
    $use = $setting->use_logo ?? false;
    $ppn = $setting->ppn ?? 0;
    $print = 0;

    return view('transaction.print', compact('transaction', 'logo', 'ucapan', 'deskripsi', 'use', 'name', "tickets", 'ppn', 'print', 'printMode'));
}
    public function report(Request $request)
    {
        $title = 'Report Transaction';
        $breadcrumbs = ['Master', 'Report Transaction'];
        $transactions = Transaction::get();
        $from = $request->from ? Carbon::parse($request->from)->format('Y-m-d') : Carbon::now()->format('Y-m-d');
        $to = $request->to ? Carbon::parse($request->to)->addDay(1)->format('Y-m-d') : Carbon::now()->format('Y-m-d');
        $tickets = Ticket::whereNotIn('id', [11, 12, 13])->get();

        return view('transaction.report', compact('title', 'breadcrumbs', 'transactions', 'from', 'to', 'tickets'));
    }

    public function invoice(Transaction $transaction)
    {
        if (!in_array($transaction->transaction_type, ['registration', 'renewal'])) {
            abort(404);
        }

        $member = $transaction->member;
        if (!$member) {
            abort(404);
        }

        $member->load(['membership', 'childs']);

        return view('member.invoice', compact('member', 'transaction'));
    }

    public function invoicePdf(Transaction $transaction)
    {
        if (!in_array($transaction->transaction_type, ['registration', 'renewal'])) {
            abort(404);
        }

        $member = $transaction->member;
        if (!$member) {
            abort(404);
        }

        $member->load(['membership', 'childs']);

        $type = $transaction->transaction_type === 'renewal' ? 'Perpanjangan' : 'Registrasi';
        $invoiceCode = $transaction->ticket_code;
        $price = 'Rp. ' . number_format($member->membership->price ?? 0, 0, ',', '.');
        $date = $transaction->created_at?->format('d/m/Y H:i:s') ?? now('Asia/Jakarta')->format('d/m/Y H:i:s');

        $setting = Setting::first();
        $appName = $setting->name ?? 'Ticketing App';
        $logoData = null;
        if ($setting && $setting->use_logo && $setting->logo) {
            $logoPath = public_path('storage/' . $setting->logo);
            if (is_file($logoPath)) {
                $logoBase64 = base64_encode(file_get_contents($logoPath));
                $logoMime = pathinfo($logoPath, PATHINFO_EXTENSION) === 'png' ? 'image/png' : 'image/jpeg';
                $logoData = 'data:' . $logoMime . ';base64,' . $logoBase64;
            }
        }

        $pdf = Pdf::loadView('member.invoice-pdf', [
            'member' => $member,
            'type' => $type,
            'invoice_code' => $invoiceCode,
            'date' => $date,
            'price' => $price,
            'transaction' => $transaction,
            'app_name' => $appName,
            'logo_data' => $logoData,
        ]);

        return $pdf->download('invoice_membership_' . $member->id . '.pdf');
    }

    // Dalam TransactionController.php

public function setFullScan(Transaction $transaction)
{
    try {
        // Mulai transaksi database untuk memastikan atomisitas
        DB::beginTransaction();

        // Hanya proses jika transaction_type adalah 'ticket'
        // Catatan: Asumsi $transaction->transaction_type sesuai dengan kolom yang benar (misalnya 'tipe' di DB Anda)
        if ($transaction->transaction_type != 'ticket') {
             return back()->with('error', "Aksi ini hanya berlaku untuk transaksi tiket.");
        }

        $detailTransactions = $transaction->detail;

        // 1. Update detail transactions
        foreach ($detailTransactions as $detail) {
            // Set scanned menjadi sama dengan qty
            $detail->scanned = $detail->qty;
            // Opsional: Anda mungkin ingin mengatur status detail menjadi 'close' jika ada
            // $detail->status = 'close';
            $detail->save();
        }

        // 2. Perbarui status utama transaksi menjadi 'closed'
        // Baris ini sudah ada sebagai komentar dan kini diaktifkan/dimodifikasi
        if ($transaction->status != 'closed') {
           $transaction->status = 'closed';
           $transaction->save();
        }

        // Commit transaksi jika semua berhasil
        DB::commit();

        return back()->with('success', "Transaksi " . $transaction->ticket_code . " berhasil ditandai sebagai Full Scanned dan ditutup.");

    } catch (\Throwable $th) {
        // Rollback transaksi jika terjadi kesalahan
        DB::rollBack();
        return back()->with('error', $th->getMessage());
    }
}
}
