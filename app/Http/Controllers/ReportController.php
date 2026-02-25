<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Sewa;
use App\Models\User;
use App\Models\Ticket;
use App\Models\Setting;
use App\Models\Penyewaan;
use App\Models\Membership;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Exports\PenyewaanExport;
use App\Models\DetailTransaction;
use App\Exports\TransactionExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RingkasanTransaksiExport;
use App\Exports\ReportPenyewaanExport;
use App\Exports\ReportTransactionExport;
use Yajra\DataTables\Facades\DataTables;

class ReportController extends Controller
{
    private function buildRingkasanTransaksiData(string $from, string $to, string $kasir = 'all'): array
    {
        $start = Carbon::parse($from, 'Asia/Jakarta')->startOfDay();
        $end = Carbon::parse($to, 'Asia/Jakarta')->endOfDay();

        $query = Transaction::with('detail')
            ->where('is_active', 1)
            ->whereBetween('created_at', [$start->format('Y-m-d H:i:s'), $end->format('Y-m-d H:i:s')]);

        if ($kasir !== 'all' && $kasir !== '') {
            $query->where('user_id', $kasir);
        }

        $transactions = $query->orderBy('created_at')->get();

        $grouped = [];
        foreach ($transactions as $trx) {
            $dateKey = Carbon::parse($trx->created_at)->timezone('Asia/Jakarta')->format('Y-m-d');

            if (!isset($grouped[$dateKey])) {
                $grouped[$dateKey] = [
                    'tanggal' => Carbon::parse($dateKey)->format('d/m/Y'),
                    'member' => 0.0,
                    'ticket' => 0.0,
                    'lain_lain' => 0.0,
                    'total' => 0.0,
                    'dpp' => 0.0,
                    'ppn' => 0.0,
                ];
            }

            if ($trx->transaction_type === 'ticket') {
                $dpp = (float) $trx->detail->sum('total');
                $ppn = (float) $trx->detail->sum('ppn');
            } else {
                $dpp = max(0.0, ((float) ($trx->bayar ?? 0)) - ((float) ($trx->kembali ?? 0)));
                $ppn = (float) ($trx->ppn ?? 0);
            }

            $total = $dpp + $ppn;

            if (in_array($trx->transaction_type, ['registration', 'renewal'], true)) {
                $grouped[$dateKey]['member'] += $total;
            } elseif ($trx->transaction_type === 'ticket') {
                $grouped[$dateKey]['ticket'] += $total;
            } else {
                $grouped[$dateKey]['lain_lain'] += $total;
            }

            $grouped[$dateKey]['dpp'] += $dpp;
            $grouped[$dateKey]['ppn'] += $ppn;
            $grouped[$dateKey]['total'] += $total;
        }

        ksort($grouped);
        $rows = array_values($grouped);

        $footer = [
            'member' => array_sum(array_column($rows, 'member')),
            'ticket' => array_sum(array_column($rows, 'ticket')),
            'lain_lain' => array_sum(array_column($rows, 'lain_lain')),
            'total' => array_sum(array_column($rows, 'total')),
            'dpp' => array_sum(array_column($rows, 'dpp')),
            'ppn' => array_sum(array_column($rows, 'ppn')),
        ];

        return [$rows, $footer];
    }

    public function ringkasanTransaksi(Request $request)
    {
        $now = Carbon::now('Asia/Jakarta');
        $from = $request->input('from');
        $to = $request->input('to');
        $kasir = (string) $request->input('kasir', 'all');

        if ((!$from || !$to) && $request->filled('daterange')) {
            try {
                $range = explode(' - ', (string) $request->daterange);
                $from = Carbon::createFromFormat('m/d/Y', trim($range[0]))->format('Y-m-d');
                $to = Carbon::createFromFormat('m/d/Y', trim($range[1]))->format('Y-m-d');
            } catch (\Throwable $e) {
                $from = null;
                $to = null;
            }
        }

        $from = $from ?: $now->format('Y-m-d');
        $to = $to ?: $now->format('Y-m-d');
        [$rows, $footer] = $this->buildRingkasanTransaksiData($from, $to, $kasir);

        $dateLabel = Carbon::parse($from)->format('d/m/Y') . ' s.d ' . Carbon::parse($to)->format('d/m/Y');
        $title = 'Report Ringkasan Transaksi ' . $dateLabel;
        $breadcrumbs = ['Master', 'Report Ringkasan Transaksi'];
        $users = User::query()->orderBy('name')->get();

        return view('report.ringkasan-transaksi', compact(
            'title',
            'breadcrumbs',
            'from',
            'to',
            'kasir',
            'users',
            'rows',
            'footer'
        ));
    }

    public function exportRingkasanTransaksi(Request $request)
    {
        $now = Carbon::now('Asia/Jakarta');
        $from = (string) ($request->input('from') ?: $now->format('Y-m-d'));
        $to = (string) ($request->input('to') ?: $now->format('Y-m-d'));
        $kasir = (string) $request->input('kasir', 'all');
        [$rows, $footer] = $this->buildRingkasanTransaksiData($from, $to, $kasir);

        return Excel::download(
            new RingkasanTransaksiExport($from, $to, $rows, $footer),
            'Report_Ringkasan_Transaksi.xlsx'
        );
    }

    private function resolveProductDescription($transaction): string
    {
        if ($transaction->transaction_type === 'ticket') {
            $names = $transaction->detail->pluck('ticket.name')->filter()->unique()->values();
            return $names->isNotEmpty() ? $names->implode(', ') : '-';
        }

        if (in_array($transaction->transaction_type, ['registration', 'renewal'])) {
            return Membership::find($transaction->ticket_id)?->name ?? '-';
        }

        if ($transaction->transaction_type === 'rental') {
            return Penyewaan::with('sewa')->find($transaction->ticket_id)?->sewa?->name ?? '-';
        }

        return '-';
    }

    public function transaction(Request $request)
    {
        if ($request->filled('from') && $request->filled('to')) {
            $date = Carbon::parse($request->from)->format('d/m/Y') . ' s.d ' . Carbon::parse($request->to)->format('d/m/Y');
        } elseif ($request->filled('daterange')) {
            try {
                $range = explode(' - ', (string) $request->daterange);
                $start = Carbon::createFromFormat('m/d/Y', trim($range[0]));
                $end = Carbon::createFromFormat('m/d/Y', trim($range[1]));
                $date = $start->format('d/m/Y') . ' s.d ' . $end->format('d/m/Y');
            } catch (\Throwable $e) {
                $date = Carbon::now('Asia/Jakarta')->format('d/m/Y');
            }
        } else {
            $date = Carbon::now('Asia/Jakarta')->format('d/m/Y');
        }

        $title = 'Report Transaction ' . $date;
        $breadcrumbs = ['Master', 'Report Transaction'];
        $users = User::get();

        return view('report.transaction', compact('title', 'breadcrumbs', 'users'));
    }

   public function transactionList(Request $request)
{
    if ($request->ajax()) {
        $now = Carbon::now()->format('Y-m-d');
        $transactionType = $request->transaction_type;
        $kasir = $request->kasir;

        $query = Transaction::with(['user.roles', 'detail.ticket'])->where('is_active', 1);

        if ($request->from && $request->to) {
            $to = Carbon::parse($request->to)->addDay(1)->format('Y-m-d');
            $query->whereBetween('created_at', [$request->from, $to]);
        } else {
            $query->whereDate('created_at', $now);
        }

        if (!empty($kasir) && $kasir !== 'all') {
            $query->where('user_id', $kasir);
        }

        if (!empty($transactionType)) {
            $query->where('transaction_type', $transactionType);
        }

        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->editColumn('tanggal', function ($row) {
                return Carbon::parse($row->created_at)->format('d/m/Y H:i:s');
            })
            ->addColumn('kasir', function ($row) {
                return $row->user->name ?? '-';
            })
            ->addColumn('metode', function ($row) {
                return strtoupper($row->metode ?? '-');
            })
            ->addColumn('keterangan_produk', function ($row) {
                return $this->resolveProductDescription($row);
            })
            ->addColumn('transaction_type_label', function ($row) {
                return ucfirst($row->transaction_type ?? '-');
            })
            ->editColumn('harga', function ($row) {
                return 'Rp. ' . number_format($row->bayar, 0, ',', '.') ?? 0;
            })
            ->editColumn('jumlah', function ($row) {
                return 'Rp. ' . number_format($row->detail()->sum('total') + $row->detail()->sum('ppn'), 0, ',', '.') ?? 0;
            })
            ->editColumn('discount', function ($row) {
                return 'Rp. ' . number_format($row->detail()->sum('total') * $row->discount / 100, 0, ',', '.') ?? 0;
            })
            ->addColumn('harga_ticket', function ($row) {
                $disc = $row->bayar * $row->discount / 100;
                return 'Rp. ' . number_format($row->bayar - $disc + $row->ppn, 0, ',', '.') ?? 0;
            })
            ->editColumn('ppn', function ($row) {
                return 'Rp. ' .  number_format($row->ppn, 0, ',', '.') ?? 0;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}

    function export_transaction(Request $request)
    {
        $now = Carbon::now()->format('Y-m-d');
        $transactionType = $request->transaction_type;
        $kasir = $request->kasir;

        if ($request->from && $request->to) {
            $to = Carbon::parse($request->to)->addDay(1)->format('Y-m-d');
            $query = Transaction::with(['user.roles', 'detail.ticket'])->where('is_active', 1)->whereBetween('created_at', [$request->from, $to]);
        } else {
            $query = Transaction::with(['user.roles', 'detail.ticket'])->where('is_active', 1)->whereDate('created_at', $now);
        }

        if (!empty($kasir) && $kasir !== 'all') {
            $query->where('user_id', $kasir);
        }

        if (!empty($transactionType)) {
            $query->where('transaction_type', $transactionType);
        }

        $data = $query->get();

        return Excel::download(new ReportTransactionExport($data), "Laporan Transaksi.xlsx");
    }

    public function export_transaction_txt(Request $request)
    {
        $now = Carbon::now('Asia/Jakarta')->format('Y-m-d');
        $transactionType = $request->transaction_type;
        $kasir = $request->kasir;

        $query = Transaction::with(['user', 'detail.ticket'])->where('is_active', 1);

        if ($request->from && $request->to) {
            $to = Carbon::parse($request->to)->addDay(1)->format('Y-m-d');
            $query->whereBetween('created_at', [$request->from, $to]);
        } else {
            $query->whereDate('created_at', $now);
        }

        if (!empty($kasir) && $kasir !== 'all') {
            $query->where('user_id', $kasir);
        }

        if (!empty($transactionType)) {
            $query->where('transaction_type', $transactionType);
        }

        $transactions = $query->orderBy('created_at')->orderBy('id')->get();

        $lines = ['TANGGAL|KASIR|TRANSACTION_TYPE|TICKET_CODE|KETERANGAN_PRODUK|METODE|AMOUNT|JUMLAH|TOTAL|PBJT|DISCOUNT'];
        foreach ($transactions as $trx) {
            $dateTime = Carbon::parse($trx->created_at)->timezone('Asia/Jakarta')->format('m/d/Y H:i:s');
            $kasirName = $trx->user->name ?? '-';
            $trxType = ucfirst($trx->transaction_type ?? '-');
            $ticketCode = trim((string) ($trx->ticket_code ?? ('TRX/' . $trx->id)));
            $productDesc = $this->resolveProductDescription($trx);
            $metode = strtoupper((string) ($trx->metode ?? '-'));
            $amount = (int) ($trx->amount ?? 0);
            $jumlah = (int) round((float) ($trx->bayar ?? 0));
            $discount = (float) ($trx->disc ?? 0);
            $pbjt = (float) ($trx->ppn ?? 0);
            $total = (int) round(((float) ($trx->bayar ?? 0) - $discount) + $pbjt);

            $lines[] = implode('|', [
                $dateTime,
                $kasirName,
                $trxType,
                $ticketCode,
                $productDesc,
                $metode,
                $amount,
                $jumlah,
                $total,
                (int) round($pbjt),
                (int) round($discount),
            ]);
        }

        $prefix = 'H1091306460002';
        $fileName = $prefix . '_' . now('Asia/Jakarta')->format('Ymd') . '000000.TXT';
        $content = implode("\r\n", $lines);
        if ($content !== '') {
            $content .= "\r\n";
        }

        return response($content, 200, [
            'Content-Type' => 'text/plain; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }

    public function penyewaan(Request $request)
    {
        $now = Carbon::now('Asia/Jakarta');
        $from = $request->input('from');
        $to = $request->input('to');
        $kasir = $request->input('kasir', 'all');

        if ((!$from || !$to) && $request->filled('daterange')) {
            try {
                $range = explode(' - ', (string) $request->daterange);
                $from = Carbon::createFromFormat('m/d/Y', trim($range[0]))->format('Y-m-d');
                $to = Carbon::createFromFormat('m/d/Y', trim($range[1]))->format('Y-m-d');
            } catch (\Throwable $e) {
                $from = null;
                $to = null;
            }
        }

        $from = $from ?: $now->format('Y-m-d');
        $to = $to ?: $now->format('Y-m-d');
        $toExclusive = Carbon::parse($to)->addDay()->format('Y-m-d');

        $baseQuery = Penyewaan::query()
            ->with(['sewa:id,name,harga', 'user:id,name'])
            ->whereBetween('created_at', [$from, $toExclusive]);
        if ($kasir !== 'all' && $kasir !== null && $kasir !== '') {
            $baseQuery->where('user_id', $kasir);
        }

        $rows = (clone $baseQuery)
            ->orderBy('sewa_id')
            ->orderBy('created_at')
            ->get()
            ->values();

        $transactionMap = Transaction::query()
            ->where('transaction_type', 'rental')
            ->whereIn('ticket_id', $rows->pluck('id')->all())
            ->select('ticket_id', 'ticket_code', 'no_trx', 'bayar', 'ppn')
            ->get()
            ->keyBy('ticket_id');

        $groupedRows = $rows
            ->groupBy('sewa_id')
            ->map(function ($items) use ($transactionMap) {
                $detailRows = $items->values()->map(function ($item, $index) use ($transactionMap) {
                    $trx = $transactionMap->get($item->id);
                    $dpp = $trx ? (float) ($trx->bayar ?? 0) : (float) ($item->jumlah ?? 0);
                    $ppn = (float) ($trx->ppn ?? 0);
                    $totalBayar = $trx ? ($dpp + $ppn) : (float) ($item->jumlah ?? 0);

                    return [
                        'no' => $index + 1,
                        'no_bukti' => $trx->no_trx ?? ('RENT-' . $item->id),
                        'tanggal' => Carbon::parse($item->created_at)->format('d/m/Y H:i:s'),
                        'kasir' => $item->user->name ?? '-',
                        'kode_trx' => $trx->ticket_code ?? '-',
                        'metode' => strtoupper((string) ($item->metode ?? '-')),
                        'qty' => (int) ($item->qty ?? 0),
                        'harga' => (float) ($item->sewa->harga ?? 0),
                        'dpp' => $dpp,
                        'ppn' => $ppn,
                        'total_bayar' => $totalBayar,
                    ];
                });

                return [
                    'sewa_name' => $items->first()->sewa->name ?? '-',
                    'sewa_id' => $items->first()->sewa_id,
                    'details' => $detailRows,
                    'subtotal_qty' => (int) $items->sum('qty'),
                    'subtotal_ppn' => (float) $detailRows->sum('ppn'),
                    'subtotal_total' => (float) $detailRows->sum('total_bayar'),
                ];
            })
            ->values();

        $grandQty = (int) $groupedRows->sum('subtotal_qty');
        $grandPpn = (float) $groupedRows->sum('subtotal_ppn');
        $grandTotal = (float) $groupedRows->sum('subtotal_total');
        $date = Carbon::parse($from)->format('d/m/Y') . ' s.d ' . Carbon::parse($to)->format('d/m/Y');

        $title = 'Report Transaksi Lainnya ' . $date;
        $breadcrumbs = ['Master', 'Report Transaksi Lainnya'];
        $users = User::get();

        return view('report.penyewaan', compact(
            'title',
            'breadcrumbs',
            'users',
            'from',
            'to',
            'kasir',
            'groupedRows',
            'grandQty',
            'grandPpn',
            'grandTotal'
        ));
    }

    public function penyewaanList(Request $request)
    {
        if ($request->ajax()) {
            $now = Carbon::now()->format('Y-m-d');
            $data = Penyewaan::query()
                ->join('sewa', 'penyewaans.sewa_id', '=', 'sewa.id')
                ->selectRaw('
                    penyewaans.sewa_id,
                    sewa.name as sewa_name,
                    sewa.harga as sewa_harga,
                    SUM(penyewaans.qty) as qty,
                    SUM(penyewaans.jumlah) as total
                ')
                ->groupBy('penyewaans.sewa_id', 'sewa.name', 'sewa.harga');

            if ($request->from && $request->to) {
                $to = Carbon::parse($request->to)->addDay(1)->format('Y-m-d');
                $data->whereBetween('penyewaans.created_at', [$request->from, $to]);
            } else {
                $data->whereDate('penyewaans.created_at', $now);
            }

            if ($request->filled('kasir') && $request->kasir !== 'all') {
                $data->where('penyewaans.user_id', $request->kasir);
            }

            return DataTables::eloquent($data)
                ->addIndexColumn()
                ->editColumn('sewa', function ($row) {
                    return $row->sewa_name ?? '-';
                })
                ->editColumn('harga', function ($row) {
                    return 'Rp. ' . number_format($row->sewa_harga ?? 0, 0, ',', '.');
                })
                ->editColumn('total', function ($row) {
                    return 'Rp. ' . number_format($row->total ?? 0, 0, ',', '.');
                })
                ->make(true);
        }
    }

    function export_penyewaan(Request $request)
    {
        $now = Carbon::now('Asia/Jakarta');
        $from = $request->input('from') ?: $now->format('Y-m-d');
        $to = $request->input('to') ?: $now->format('Y-m-d');
        $kasir = $request->input('kasir', 'all');
        $toExclusive = Carbon::parse($to)->addDay()->format('Y-m-d');

        $baseQuery = Penyewaan::query()
            ->with(['sewa:id,name,harga', 'user:id,name'])
            ->whereBetween('created_at', [$from, $toExclusive]);

        if ($kasir !== 'all' && $kasir !== null && $kasir !== '') {
            $baseQuery->where('user_id', $kasir);
        }

        $rows = (clone $baseQuery)
            ->orderBy('sewa_id')
            ->orderBy('created_at')
            ->get()
            ->values();

        $transactionMap = Transaction::query()
            ->where('transaction_type', 'rental')
            ->whereIn('ticket_id', $rows->pluck('id')->all())
            ->select('ticket_id', 'ticket_code', 'no_trx', 'bayar', 'ppn')
            ->get()
            ->keyBy('ticket_id');

        $groupedRows = $rows
            ->groupBy('sewa_id')
            ->map(function ($items) use ($transactionMap) {
                $detailRows = $items->values()->map(function ($item, $index) use ($transactionMap) {
                    $trx = $transactionMap->get($item->id);
                    $dpp = $trx ? (float) ($trx->bayar ?? 0) : (float) ($item->jumlah ?? 0);
                    $ppn = (float) ($trx->ppn ?? 0);
                    $totalBayar = $trx ? ($dpp + $ppn) : (float) ($item->jumlah ?? 0);

                    return [
                        'no' => $index + 1,
                        'no_bukti' => $trx->no_trx ?? ('RENT-' . $item->id),
                        'tanggal' => Carbon::parse($item->created_at)->format('d/m/Y H:i:s'),
                        'kasir' => $item->user->name ?? '-',
                        'kode_trx' => $trx->ticket_code ?? '-',
                        'metode' => strtoupper((string) ($item->metode ?? '-')),
                        'qty' => (int) ($item->qty ?? 0),
                        'ppn' => $ppn,
                        'total_bayar' => $totalBayar,
                    ];
                });

                return [
                    'sewa_name' => $items->first()->sewa->name ?? '-',
                    'details' => $detailRows,
                    'subtotal_qty' => (int) $items->sum('qty'),
                    'subtotal_ppn' => (float) $detailRows->sum('ppn'),
                    'subtotal_total' => (float) $detailRows->sum('total_bayar'),
                ];
            })
            ->values()
            ->all();

        $grandQty = (int) collect($groupedRows)->sum('subtotal_qty');
        $grandPpn = (float) collect($groupedRows)->sum('subtotal_ppn');
        $grandTotal = (float) collect($groupedRows)->sum('subtotal_total');

        return Excel::download(
            new ReportPenyewaanExport($groupedRows, $grandQty, $grandPpn, $grandTotal),
            "Laporan Transaksi Lainnya.xlsx"
        );
    }

    public function rekapTransaction(Request $request)
    {
        $date = $request->from ? Carbon::parse($request->from)->format('d/m/Y') . ' s.d ' . Carbon::parse($request->to)->format('d/m/Y') : Carbon::now()->format('d/m/Y');
        $from = $request->from ? Carbon::parse($request->from)->format('Y-m-d') : Carbon::now()->format('Y-m-d');

        if ($request->to) {
            $to = Carbon::parse($request->to)->addDay(1)->format('Y-m-d');
        } else {
            $to = Carbon::now()->addDay(1)->format('Y-m-d');
        }
        $title = 'Rekap Transaction ' . $date;
        $breadcrumbs = ['Master', 'Rekap Transaction'];
        $tickets = Ticket::get();
        $users = User::get();

        $memberships = Membership::where('is_active', 1)->get();
        $sewa = Sewa::get();
        return view('report.rekap-transaction', compact('title', 'breadcrumbs', 'from', 'to', 'tickets', 'users', 'memberships', 'sewa'));
    }

 public function exportTransaction(Request $request)
{
    $from = Carbon::parse($request->from)->format('Y-m-d');
    $to = Carbon::parse($request->to)->addDay(1)->format('Y-m-d');

    $memberships = Membership::where('is_active', 1)->get();
    $sewa = Sewa::get();

    return Excel::download(new TransactionExport($from, $to, $request->kasir, $memberships, $sewa), 'Rekap Transaction.xlsx');
}

public function printTransaction(Request $request)
{
    $from = Carbon::parse($request->from)->format('Y-m-d');
    $to = Carbon::parse($request->to)->addDay(1)->format('Y-m-d');
    $kasir = $request->filled('kasir') ? $request->kasir : 'all';

    // Ambil semua data master untuk looping di view
    $tickets = Ticket::get();
    $memberships = Membership::where('is_active', 1)->get();
    $sewa = Sewa::get();
    $users = User::all();

    return view('report.download-transaction', compact('tickets', 'memberships', 'sewa', 'from', 'to', 'kasir', 'users'));
}
    public function rekapPenyewaan(Request $request)
    {
        $date = $request->from ? Carbon::parse($request->from)->format('d/m/Y') . ' s.d ' . Carbon::parse($request->to)->format('d/m/Y') : Carbon::now()->format('d/m/Y');
        $from = $request->from ? Carbon::parse($request->from)->format('Y-m-d') : Carbon::now()->format('Y-m-d');
        $to = $request->to ? Carbon::parse($request->to)->addDay(1)->format('Y-m-d') : Carbon::now()->format('Y-m-d');

        $title = 'Rekap Transaksi Lainnya ' . $date;
        $breadcrumbs = ['Master', 'Rekap Transaksi Lainnya'];
        $sewa = Sewa::get();
        $users = User::get();

        return view('report.rekap-penyewaan', compact('title', 'breadcrumbs', 'from', 'to', 'sewa', 'users'));
    }

    public function exportPenyewaan(Request $request)
    {
        $from = Carbon::parse(request('from'))->format('Y-m-d');
        $to = Carbon::parse(request('to'))->addDay(1)->format('Y-m-d');

        return Excel::download(new PenyewaanExport($from, $to, $request->kasir), 'Rekap Transaksi Lainnya.xlsx');
    }

    public function printPenyewaan(Request $request)
    {
        $from = Carbon::parse(request('from'))->format('Y-m-d');
        $to = Carbon::parse(request('to'))->addDay(1)->format('Y-m-d');
        $kasir = $request->kasir;
        $penyewaanId = Penyewaan::whereBetween('created_at', [$from, $to])->pluck('id');

        $sewa = Sewa::whereHas('penyewaans', function ($query) use ($penyewaanId) {
            $query->whereIn('id', $penyewaanId);
        })->get();

        return view('report.download-penyewaan', compact('from', 'to', 'kasir', 'sewa'));
    }
}
