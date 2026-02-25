<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Sewa;
use App\Models\Ticket;
use App\Models\Membership;
use App\Models\Member;
use App\Models\Penyewaan;
use App\Models\Setting;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Exports\ReportExport;
use App\Exports\TransactionDailyReceiptExport;
use App\Models\DetailTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\Transaction\CreateTransactionRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

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
        $setting = Setting::asObject();
        $reminderDays = (int) ($setting->member_suspend_before_days ?? 7);
        $limitDate = Carbon::now('Asia/Jakarta')->addDays($reminderDays)->toDateString();
        $renewalCount = Member::where('tgl_expired', '<=', $limitDate)
            ->where('is_active', 1)
            ->where('parent_id', 0)
            ->count();

        return view('transaction.index', compact('title', 'breadcrumbs', 'tickets', 'renewalCount'));
    }

    public function get(Request $request)
    {
        if ($request->ajax()) {
            [$startDate, $endDate] = $this->resolveDateRange($request);

            $transactionType = $request->transaction_type;

            // --- Query Building Logic ---
            if (auth()->user()->roles()->first()->id == 1) {
                $data = Transaction::where('is_active', 1)
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->orderBy('created_at', 'DESC');
            } else {
                $data = Transaction::where(['is_active' => 1, 'user_id' => auth()->user()->id])
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->orderBy('created_at', 'DESC');
            }

            if (!empty($transactionType)) {
                $data->where('transaction_type', $transactionType);
            }

            return DataTables::eloquent($data)
                ->addIndexColumn()
                ->addColumn('tanggal', function ($row) {
                    return optional($row->created_at)->timezone('Asia/Jakarta')->format('d/m/Y H:i:s');
                })

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
                    $buttons = [];

                    if (!in_array($row->transaction_type, ['registration', 'renewal'])) {
                        $buttons[] = '<a href="' . route("transactions.print", $row->id) . '" class="btn btn-sm btn-primary"><i class="fas fa-print"></i></a>';
                    }

                    $totalQty = $row->detail()->sum('qty');
                    $totalScanned = $row->detail()->sum('scanned');

                    if ($row->transaction_type == 'ticket' && $totalScanned < $totalQty) {
                        $routeFullScan = route('transactions.set_full_scan', $row->id);
                        $buttons[] = '<button type="button"
                                                data-route="' . $routeFullScan . '"
                                                data-ticket-code="' . $row->ticket_code . '"
                                                class="btn btn-sm btn-warning btn-full-scan"
                                                title="Set Full Scan">
                                                <i class="fas fa-check-double"></i>
                                            </button>';
                    }

                    if (in_array($row->transaction_type, ['registration', 'renewal'])) {
                        $buttons[] = '<a href="' . route('transactions.invoice.pdf', $row->id) . '" class="btn btn-sm btn-secondary" target="_blank" title="Invoice Membership (PDF)"><i class="fas fa-file-invoice"></i></a>';
                    }

                    if (auth()->user()->can('transaction-delete')) {
                        $buttons[] = '<button type="button" data-route="' . route('transactions.destroy', $row->id) . '" class="delete btn btn-danger btn-delete btn-sm"><i class="fas fa-trash"></i></button>';
                    }

                    return '<div class="d-inline-flex flex-nowrap align-items-center gap-1">' . implode('', $buttons) . '</div>';
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

    public function exportDaily(Request $request)
    {
        [$startDate, $endDate] = $this->resolveDateRange($request);
        $transactionType = $request->input('transaction_type');

        $query = Transaction::with(['detail.ticket', 'user', 'member'])
            ->where('is_active', 1)
            ->whereBetween('created_at', [$startDate, $endDate]);

        if (auth()->user()->roles()->first()->id != 1) {
            $query->where('user_id', auth()->id());
        }

        if (!empty($transactionType)) {
            $query->where('transaction_type', $transactionType);
        }

        $transactions = $query->orderBy('created_at')->orderBy('id')->get();
        $exportRows = $this->buildDataTransactionExportRows($transactions);
        $setting = Setting::asObject();
        $reportTitle = strtoupper(trim((string) ($setting->name ?? config('app.name', 'TICKETING'))));

        $fileName = 'Laporan_Penerimaan_Harian_' . now('Asia/Jakarta')->format('Ymd_His') . '.xlsx';

        return Excel::download(
            new TransactionDailyReceiptExport($startDate, $endDate, $exportRows, $reportTitle),
            $fileName
        );
    }

    private function buildDataTransactionExportRows($transactions): array
    {
        $rows = [];

        foreach ($transactions as $trx) {
            $tanggal = optional($trx->created_at)->timezone('Asia/Jakarta')->format('d/m/Y H:i:s');
            $kasir = $trx->user->name ?? '-';
            $caraBayar = strtoupper((string) ($trx->metode ?? '-'));

            if ($trx->transaction_type === 'ticket' && $trx->detail->isNotEmpty()) {
                foreach ($trx->detail as $detail) {
                    $qty = max((int) ($detail->qty ?? 1), 1);
                    $ppn = (float) ($detail->ppn ?? 0);
                    $harga = (float) ($detail->total ?? 0) + $ppn;

                    $rows[] = $this->makeExportRow(
                        (string) ($trx->ticket_code ?? ('TRX/' . $trx->id)),
                        $kasir,
                        $tanggal,
                        $harga,
                        $qty,
                        $caraBayar,
                        $harga
                    );
                }
                continue;
            }

            $qty = max((int) ($trx->amount ?? 1), 1);
            $ppn = (float) ($trx->ppn ?? 0);
            $harga = (float) ($trx->bayar ?? 0) - (float) ($trx->kembali ?? 0) + $ppn;

            $rows[] = $this->makeExportRow(
                (string) ($trx->ticket_code ?? ('TRX/' . $trx->id)),
                $kasir,
                $tanggal,
                $harga,
                $qty,
                $caraBayar,
                $harga
            );
        }

        return $rows;
    }

    private function makeExportRow(
        string $noTransaksi,
        string $namaKasir,
        string $tanggal,
        float $harga,
        int $qty,
        string $caraBayar,
        float $totalBayar
    ): array {
        $tunai = 0.0;
        $debit = 0.0;
        $qr = 0.0;
        $creditCard = 0.0;
        $transfer = 0.0;
        $pembayaranLainnya = 0.0;

        $metode = $this->normalizePaymentMethod($caraBayar);
        if ($metode === 'cash') {
            $tunai = $totalBayar;
        } elseif ($metode === 'debit') {
            $debit = $totalBayar;
        } elseif ($metode === 'qris') {
            $qr = $totalBayar;
        } elseif ($metode === 'transfer') {
            $transfer = $totalBayar;
        } elseif ($metode === 'kredit') {
            $creditCard = $totalBayar;
        } else {
            $pembayaranLainnya = $totalBayar;
        }

        return [
            'no_transaksi' => $noTransaksi,
            'nama_kasir' => $namaKasir,
            'tanggal' => $tanggal,
            'harga' => $harga,
            'qty' => $qty,
            'cara_bayar' => $caraBayar,
            'tunai' => $tunai,
            'debit' => $debit,
            'qr' => $qr,
            'credit_card' => $creditCard,
            'transfer' => $transfer,
            'pembayaran_lainnya' => $pembayaranLainnya,
            'total_bayar' => $totalBayar,
        ];
    }

    private function resolveDateRange(Request $request): array
    {
        $startDate = Carbon::now('Asia/Jakarta')->startOfDay();
        $endDate = Carbon::now('Asia/Jakarta')->endOfDay();
        $daterange = trim((string) $request->input('daterange', ''));

        if ($daterange !== '') {
            $parts = explode(' - ', $daterange);
            if (count($parts) === 2) {
                try {
                    $startDate = Carbon::createFromFormat('m/d/Y', trim($parts[0]), 'Asia/Jakarta')->startOfDay();
                    $endDate = Carbon::createFromFormat('m/d/Y', trim($parts[1]), 'Asia/Jakarta')->endOfDay();
                } catch (\Throwable $e) {
                    $startDate = Carbon::now('Asia/Jakarta')->startOfDay();
                    $endDate = Carbon::now('Asia/Jakarta')->endOfDay();
                }
            }
        } elseif ($request->filled('tanggal')) {
            try {
                $legacyDate = Carbon::parse($request->input('tanggal'), 'Asia/Jakarta');
                $startDate = $legacyDate->copy()->startOfDay();
                $endDate = $legacyDate->copy()->endOfDay();
            } catch (\Throwable $e) {
                $startDate = Carbon::now('Asia/Jakarta')->startOfDay();
                $endDate = Carbon::now('Asia/Jakarta')->endOfDay();
            }
        }

        return [$startDate, $endDate];
    }

    private function buildDailyReceiptGroups($transactions): array
    {
        $membershipIds = $transactions
            ->whereIn('transaction_type', ['registration', 'renewal'])
            ->pluck('ticket_id')
            ->filter()
            ->unique()
            ->values();

        $rentalIds = $transactions
            ->where('transaction_type', 'rental')
            ->pluck('ticket_id')
            ->filter()
            ->unique()
            ->values();

        $memberships = Membership::query()
            ->whereIn('id', $membershipIds)
            ->get()
            ->keyBy('id');

        $penyewaans = Penyewaan::query()
            ->with('sewa')
            ->whereIn('id', $rentalIds)
            ->get()
            ->keyBy('id');

        $groups = [];

        $pushRow = function (array $row) use (&$groups) {
            $groupCode = $row['group_code'];
            if (!isset($groups[$groupCode])) {
                $groups[$groupCode] = [
                    'group_code' => $groupCode,
                    'group_name' => $row['group_name'],
                    'rows' => [],
                    'subtotal_qty' => 0,
                    'subtotal_tunai' => 0,
                    'subtotal_non_tunai' => 0,
                    'subtotal_total' => 0,
                ];
            }

            $groups[$groupCode]['rows'][] = $row;
            $groups[$groupCode]['subtotal_qty'] += $row['qty'];
            $groups[$groupCode]['subtotal_tunai'] += $row['tunai'];
            $groups[$groupCode]['subtotal_non_tunai'] += $row['non_tunai'];
            $groups[$groupCode]['subtotal_total'] += $row['total_bayar'];
        };

        foreach ($transactions as $trx) {
            $shift = (int) $trx->created_at->timezone('Asia/Jakarta')->format('H') < 15 ? 1 : 2;
            $jam = $trx->created_at->timezone('Asia/Jakarta')->format('H:i:s');
            $fc = strtoupper((string) ($trx->user->name ?? '-'));
            $metode = strtolower((string) ($trx->metode ?? ''));
            $memberFlag = in_array($trx->transaction_type, ['registration', 'renewal']) ? 'Y' : 'T';
            $voucherFlag = 'T';

            if ($trx->transaction_type === 'ticket') {
                foreach ($trx->detail as $detail) {
                    $qty = max((int) ($detail->qty ?? 1), 1);
                    $total = (float) ($detail->total ?? 0) + (float) ($detail->ppn ?? 0);
                    $tarif = $qty > 0 ? $total / $qty : $total;
                    $productCode = 'TK' . str_pad((string) ($detail->ticket_id ?? 0), 2, '0', STR_PAD_LEFT);
                    $productName = strtoupper((string) ($detail->ticket->name ?? 'TIKET'));

                    $pushRow([
                        'no_bukti' => $trx->ticket_code,
                        'shift' => $shift,
                        'jam' => $jam,
                        'fc' => $fc,
                        'member' => $memberFlag,
                        'voucher' => $voucherFlag,
                        'group_code' => $productCode,
                        'group_name' => $productName,
                        'qty' => $qty,
                        'tarif' => (float) $tarif,
                        'tunai' => $metode === 'cash' ? (float) $total : 0.0,
                        'non_tunai' => $metode === 'cash' ? 0.0 : (float) $total,
                        'total_bayar' => (float) $total,
                    ]);
                }
                continue;
            }

            if (in_array($trx->transaction_type, ['registration', 'renewal'])) {
                $membership = $memberships->get($trx->ticket_id);
                $productCode = strtoupper((string) ($membership->code ?? ('M' . str_pad((string) ($trx->ticket_id ?? 0), 2, '0', STR_PAD_LEFT))));
                $productName = strtoupper((string) ($membership->name ?? ucfirst($trx->transaction_type)));
            } elseif ($trx->transaction_type === 'rental') {
                $penyewaan = $penyewaans->get($trx->ticket_id);
                $sewa = $penyewaan?->sewa;
                $sewaId = $sewa->id ?? 0;
                $productCode = 'SR' . str_pad((string) $sewaId, 2, '0', STR_PAD_LEFT);
                $productName = strtoupper((string) ($sewa->name ?? 'RENTAL'));
            } else {
                $productCode = strtoupper((string) $trx->transaction_type);
                $productName = strtoupper((string) $trx->transaction_type);
            }

            $qty = max((int) ($trx->amount ?? 1), 1);
            $total = (float) ($trx->bayar ?? 0) - (float) ($trx->kembali ?? 0) + (float) ($trx->ppn ?? 0);
            $tarif = $qty > 0 ? $total / $qty : $total;

            $pushRow([
                'no_bukti' => $trx->ticket_code,
                'shift' => $shift,
                'jam' => $jam,
                'fc' => $fc,
                'member' => $memberFlag,
                'voucher' => $voucherFlag,
                'group_code' => $productCode,
                'group_name' => $productName,
                'qty' => $qty,
                'tarif' => (float) $tarif,
                'tunai' => $metode === 'cash' ? (float) $total : 0.0,
                'non_tunai' => $metode === 'cash' ? 0.0 : (float) $total,
                'total_bayar' => (float) $total,
            ]);
        }

        ksort($groups);

        return $groups;
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

        $now = Carbon::now('Asia/Jakarta');
        $notrx = Transaction::nextNoTrxByType('ticket', $now);

        $active = Transaction::whereDate('created_at', $now)->where(['is_active' => 0, 'user_id' => auth()->user()->id])->latest()->first();

        if ($active) {
            $transaction = $active;
        } else {
            $transaction = Transaction::create([
                'ticket_id' => 0,
                'user_id' => auth()->user()->id,
                'no_trx' => $notrx,
                'ticket_code' => Transaction::buildTicketCodeByType('ticket', $now, $notrx),
                'transaction_type' => 'ticket',
            ]);
        }

        $setting = Setting::asObject();


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
            $now = Carbon::now('Asia/Jakarta');

            // $tipe = $request->type_customer;
            $tipe = 'group';
            $attr['ticket_id'] = $request->ticket;
            $attr['tipe'] = $tipe;
            $attr['nama_customer'] = $request->name;
            $attr['metode'] = $this->normalizePaymentMethod($request->metode);
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
            $notrx = Transaction::nextNoTrxByType('ticket', $now);

            if ($tipe == 'individual') {
                for ($i = 0; $i < $request->amount; $i++) {
                    $attr['no_trx'] = $notrx++;
                    $attr['ticket_code'] = Transaction::buildTicketCodeByType('ticket', $now, $attr['no_trx']);
                    $attr['transaction_type'] = 'ticket';

                    $transaction = Transaction::create($attr);

                    $transactions[] = $transaction->id;
                }
            } else {
                $attr['no_trx'] = $notrx;
                $attr['ticket_code'] = Transaction::buildTicketCodeByType('ticket', $now, $notrx);
                $attr['amount'] = $request->amount;
                $attr['transaction_type'] = 'ticket';

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
    if ($transaction->transaction_type === 'rental') {
        if (!$transaction->ticket_id) {
            abort(404);
        }

        return redirect()->route('penyewaan.print', $transaction->ticket_id);
    }

    $setting = Setting::asObject();

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

    $logo = !empty($setting->logo) ? asset('/storage/' . $setting->logo) : 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('/images/rio.png')));
    $name = $setting->name ?? 'Ticketing';
    $ucapan = $setting->ucapan ?? 'Terima Kasih';
    $deskripsi = $setting->deskripsi ?? 'qr code hanya berlaku satu kali';
    $use = $setting->use_logo ?? false;
    $ppn = $setting->ppn ?? 0;
    $print = 0;
    $ticketPrintOrientation = $setting->ticket_print_orientation ?? 'portrait';

    return view('transaction.print', compact('transaction', 'logo', 'ucapan', 'deskripsi', 'use', 'name', "tickets", 'ppn', 'print', 'printMode', 'ticketPrintOrientation'));
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
        $cashierName = $transaction->user?->name ?? '-';
        $setting = Setting::asObject();
        $ucapan = $setting->ucapan ?? 'Terima Kasih';
        $deskripsi = $setting->deskripsi ?? '';

        return view('member.invoice', compact('member', 'transaction', 'cashierName', 'ucapan', 'deskripsi'));
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

        $baseMembershipPrice = (float) ($member->membership->price ?? 0);
        $adminFee = max(0, ((float) ($transaction->bayar ?? 0)) - $baseMembershipPrice);
        $type = $adminFee > 0
            ? 'Perpanjangan Baru'
            : ($transaction->transaction_type === 'renewal' ? 'Perpanjangan' : 'Registrasi');
        $invoiceCode = $transaction->ticket_code;
        $price = 'Rp. ' . number_format($member->membership->price ?? 0, 0, ',', '.');
        $date = $transaction->created_at?->format('d/m/Y H:i:s') ?? now('Asia/Jakarta')->format('d/m/Y H:i:s');
        $cashierName = $transaction->user?->name ?? '-';

        $setting = Setting::asObject();
        $appName = $setting->name ?? 'Ticketing App';
        $ucapan = $setting->ucapan ?? 'Terima Kasih';
        $deskripsi = $setting->deskripsi ?? '';
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
            'cashier_name' => $cashierName,
            'ucapan' => $ucapan,
            'deskripsi' => $deskripsi,
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
