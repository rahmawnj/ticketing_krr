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
use App\Exports\DetailCategoryReportExport;
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
    private function resolveAdminFee(Transaction $transaction): float
    {
        if (!in_array($transaction->transaction_type, ['registration', 'renewal'], true)) {
            return 0.0;
        }

        return (float) ($transaction->admin_fee ?? 0);
    }

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
                    'admin_fee' => 0.0,
                ];
            }

            if ($trx->transaction_type === 'ticket') {
                $dpp = (float) $trx->detail->sum('total');
                $ppn = (float) $trx->detail->sum('ppn');
            } else {
                $dpp = max(0.0, ((float) ($trx->bayar ?? 0)) - ((float) ($trx->kembali ?? 0)));
                $ppn = (float) ($trx->ppn ?? 0);
            }

            $adminFee = $this->resolveAdminFee($trx);
            $total = $dpp + $ppn + $adminFee;

            if (in_array($trx->transaction_type, ['registration', 'renewal'], true)) {
                $grouped[$dateKey]['member'] += $total;
            } elseif ($trx->transaction_type === 'ticket') {
                $grouped[$dateKey]['ticket'] += $total;
            } else {
                $grouped[$dateKey]['lain_lain'] += $total;
            }

            $grouped[$dateKey]['dpp'] += $dpp;
            $grouped[$dateKey]['ppn'] += $ppn;
            $grouped[$dateKey]['admin_fee'] += $adminFee;
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
            'admin_fee' => array_sum(array_column($rows, 'admin_fee')),
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

    private function resolveReportDateRange(Request $request): array
    {
        $now = Carbon::now('Asia/Jakarta');
        $from = $request->input('from');
        $to = $request->input('to');

        if ((!$from || !$to) && $request->filled('daterange')) {
            try {
                $range = explode(' - ', (string) $request->input('daterange'));
                $from = Carbon::createFromFormat('m/d/Y', trim($range[0]))->format('Y-m-d');
                $to = Carbon::createFromFormat('m/d/Y', trim($range[1]))->format('Y-m-d');
            } catch (\Throwable $e) {
                $from = null;
                $to = null;
            }
        }

        return [
            (string) ($from ?: $now->format('Y-m-d')),
            (string) ($to ?: $now->format('Y-m-d')),
        ];
    }

    private function resolveReportHeading(): string
    {
        $setting = Setting::asObject();

        return strtoupper(trim((string) ($setting->name ?? config('app.name', 'TICKETING'))));
    }

    private function detailReportScopes(): array
    {
        return [
            'member' => [
                'key' => 'member',
                'title' => 'Report Detail Member',
                'breadcrumb' => 'Report Detail Member',
                'group_label' => 'Member',
                'export_file' => 'Report_Detail_Member.xlsx',
            ],
            'ticket' => [
                'key' => 'ticket',
                'title' => 'Report Detail Ticket',
                'breadcrumb' => 'Report Detail Ticket',
                'group_label' => 'Ticket',
                'export_file' => 'Report_Detail_Ticket.xlsx',
            ],
            'other' => [
                'key' => 'other',
                'title' => 'Report Detail Lain-lain',
                'breadcrumb' => 'Report Detail Lain-lain',
                'group_label' => 'Lain-lain',
                'export_file' => 'Report_Detail_Lain-lain.xlsx',
            ],
        ];
    }

    private function resolveDetailReportScope(string $scope): array
    {
        $scopes = $this->detailReportScopes();
        abort_unless(isset($scopes[$scope]), 404);

        return $scopes[$scope];
    }

    private function registerDetailReportColumn(
        array &$columns,
        array &$rowsByDate,
        array &$footer,
        string $key,
        string $label
    ): void {
        if ($key === '' || isset($columns[$key])) {
            return;
        }

        $columns[$key] = [
            'key' => $key,
            'label' => $label,
        ];
        $footer['items'][$key] = 0.0;

        foreach ($rowsByDate as &$row) {
            $row['items'][$key] = 0.0;
        }
        unset($row);
    }

    private function ensureDetailReportRow(array &$rowsByDate, string $dateKey, array $columns): void
    {
        if (isset($rowsByDate[$dateKey])) {
            return;
        }

        $rowsByDate[$dateKey] = [
            'tanggal' => Carbon::parse($dateKey)->format('d/m/Y'),
            'items' => array_fill_keys(array_keys($columns), 0.0),
            'total' => 0.0,
            'dpp' => 0.0,
            'ppn' => 0.0,
            'admin_fee' => 0.0,
        ];
    }

    private function addDetailReportValue(
        array &$rowsByDate,
        array &$footer,
        string $dateKey,
        string $columnKey,
        float $itemTotal,
        float $dpp,
        float $ppn,
        float $adminFee,
        array $columns
    ): void {
        $this->ensureDetailReportRow($rowsByDate, $dateKey, $columns);

        if (!array_key_exists($columnKey, $rowsByDate[$dateKey]['items'])) {
            $rowsByDate[$dateKey]['items'][$columnKey] = 0.0;
        }
        if (!array_key_exists($columnKey, $footer['items'])) {
            $footer['items'][$columnKey] = 0.0;
        }

        $rowsByDate[$dateKey]['items'][$columnKey] += $itemTotal;
        $rowsByDate[$dateKey]['total'] += $itemTotal;
        $rowsByDate[$dateKey]['dpp'] += $dpp;
        $rowsByDate[$dateKey]['ppn'] += $ppn;
        $rowsByDate[$dateKey]['admin_fee'] += $adminFee;

        $footer['items'][$columnKey] += $itemTotal;
        $footer['total'] += $itemTotal;
        $footer['dpp'] += $dpp;
        $footer['ppn'] += $ppn;
        $footer['admin_fee'] += $adminFee;
    }

    private function buildDetailReportData(string $scope, string $from, string $to, string $kasir = 'all'): array
    {
        $start = Carbon::parse($from, 'Asia/Jakarta')->startOfDay();
        $end = Carbon::parse($to, 'Asia/Jakarta')->endOfDay();

        $columns = [];
        $rowsByDate = [];
        $footer = [
            'items' => [],
            'total' => 0.0,
            'dpp' => 0.0,
            'ppn' => 0.0,
            'admin_fee' => 0.0,
        ];

        if ($scope === 'member') {
            $query = Transaction::query()
                ->where('is_active', 1)
                ->whereIn('transaction_type', ['registration', 'renewal'])
                ->whereBetween('created_at', [$start->format('Y-m-d H:i:s'), $end->format('Y-m-d H:i:s')]);

            if ($kasir !== 'all' && $kasir !== '') {
                $query->where('user_id', $kasir);
            }

            $transactions = $query->orderBy('created_at')->orderBy('id')->get();
            $membershipIds = $transactions->pluck('ticket_id')->filter()->map(fn ($id) => (int) $id)->unique()->values();

            $memberships = Membership::query()
                ->when($membershipIds->isNotEmpty(), function ($membershipQuery) use ($membershipIds) {
                    $membershipQuery->where(function ($query) use ($membershipIds) {
                        $query->where('is_active', 1)
                            ->orWhereIn('id', $membershipIds);
                    });
                }, function ($membershipQuery) {
                    $membershipQuery->where('is_active', 1);
                })
                ->orderBy('name')
                ->get(['id', 'name']);

            foreach ($memberships as $membership) {
                $this->registerDetailReportColumn(
                    $columns,
                    $rowsByDate,
                    $footer,
                    (string) $membership->id,
                    (string) $membership->name
                );
            }

            foreach ($transactions as $transaction) {
                $columnKey = (string) ((int) ($transaction->ticket_id ?? 0));
                $columnLabel = $columnKey !== '0' ? ('Membership #' . $columnKey) : 'Membership';

                $this->registerDetailReportColumn($columns, $rowsByDate, $footer, $columnKey, $columnLabel);

                $dateKey = Carbon::parse($transaction->created_at)->timezone('Asia/Jakarta')->format('Y-m-d');
                $dpp = max(0.0, ((float) ($transaction->bayar ?? 0)) - ((float) ($transaction->kembali ?? 0)));
                $ppn = (float) ($transaction->ppn ?? 0);
                $adminFee = $this->resolveAdminFee($transaction);
                $itemTotal = $dpp + $ppn + $adminFee;

                $this->addDetailReportValue(
                    $rowsByDate,
                    $footer,
                    $dateKey,
                    $columnKey,
                    $itemTotal,
                    $dpp,
                    $ppn,
                    $adminFee,
                    $columns
                );
            }
        } elseif ($scope === 'ticket') {
            $query = Transaction::query()
                ->with(['detail.ticket:id,name'])
                ->where('is_active', 1)
                ->where('transaction_type', 'ticket')
                ->whereBetween('created_at', [$start->format('Y-m-d H:i:s'), $end->format('Y-m-d H:i:s')]);

            if ($kasir !== 'all' && $kasir !== '') {
                $query->where('user_id', $kasir);
            }

            $transactions = $query->orderBy('created_at')->orderBy('id')->get();
            $ticketIds = $transactions
                ->flatMap(fn ($transaction) => $transaction->detail->pluck('ticket_id'))
                ->filter()
                ->map(fn ($id) => (int) $id)
                ->unique()
                ->values();

            $tickets = $ticketIds->isNotEmpty()
                ? Ticket::query()->whereIn('id', $ticketIds)->orderBy('name')->get(['id', 'name'])
                : Ticket::query()->orderBy('name')->get(['id', 'name']);

            foreach ($tickets as $ticket) {
                $this->registerDetailReportColumn(
                    $columns,
                    $rowsByDate,
                    $footer,
                    (string) $ticket->id,
                    (string) $ticket->name
                );
            }

            foreach ($transactions as $transaction) {
                $dateKey = Carbon::parse($transaction->created_at)->timezone('Asia/Jakarta')->format('Y-m-d');

                foreach ($transaction->detail as $detail) {
                    $columnKey = (string) ((int) ($detail->ticket_id ?? 0));
                    $columnLabel = $columnKey !== '0' ? ('Ticket #' . $columnKey) : 'Ticket';

                    $this->registerDetailReportColumn($columns, $rowsByDate, $footer, $columnKey, $columnLabel);

                    $dpp = (float) ($detail->total ?? 0);
                    $ppn = (float) ($detail->ppn ?? 0);
                    $itemTotal = $dpp + $ppn;

                    $this->addDetailReportValue(
                        $rowsByDate,
                        $footer,
                        $dateKey,
                        $columnKey,
                        $itemTotal,
                        $dpp,
                        $ppn,
                        0.0,
                        $columns
                    );
                }
            }
        } else {
            $query = Transaction::query()
                ->where('is_active', 1)
                ->whereNotIn('transaction_type', ['ticket', 'registration', 'renewal'])
                ->whereBetween('created_at', [$start->format('Y-m-d H:i:s'), $end->format('Y-m-d H:i:s')]);

            if ($kasir !== 'all' && $kasir !== '') {
                $query->where('user_id', $kasir);
            }

            $transactions = $query->orderBy('created_at')->orderBy('id')->get();
            $penyewaanIds = $transactions
                ->where('transaction_type', 'rental')
                ->pluck('ticket_id')
                ->filter()
                ->map(fn ($id) => (int) $id)
                ->unique()
                ->values();

            $penyewaanMap = Penyewaan::query()
                ->with('sewa:id,name')
                ->when($penyewaanIds->isNotEmpty(), function ($penyewaanQuery) use ($penyewaanIds) {
                    $penyewaanQuery->whereIn('id', $penyewaanIds);
                }, function ($penyewaanQuery) {
                    $penyewaanQuery->whereRaw('1 = 0');
                })
                ->get()
                ->keyBy('id');

            $sewaIds = $penyewaanMap->pluck('sewa_id')->filter()->map(fn ($id) => (int) $id)->unique()->values();
            $sewaItems = $sewaIds->isNotEmpty()
                ? Sewa::query()->whereIn('id', $sewaIds)->orderBy('name')->get(['id', 'name'])
                : Sewa::query()->orderBy('name')->get(['id', 'name']);

            foreach ($sewaItems as $sewaItem) {
                $this->registerDetailReportColumn(
                    $columns,
                    $rowsByDate,
                    $footer,
                    'rental:' . $sewaItem->id,
                    (string) $sewaItem->name
                );
            }

            $otherTypes = $transactions
                ->where('transaction_type', '!=', 'rental')
                ->pluck('transaction_type')
                ->filter()
                ->unique()
                ->sort()
                ->values();

            foreach ($otherTypes as $transactionType) {
                $this->registerDetailReportColumn(
                    $columns,
                    $rowsByDate,
                    $footer,
                    'type:' . $transactionType,
                    ucwords(str_replace('_', ' ', (string) $transactionType))
                );
            }

            foreach ($transactions as $transaction) {
                if (($transaction->transaction_type ?? '') === 'rental') {
                    $penyewaan = $penyewaanMap->get((int) ($transaction->ticket_id ?? 0));
                    $sewaId = (int) ($penyewaan->sewa_id ?? 0);
                    $columnKey = $sewaId > 0
                        ? 'rental:' . $sewaId
                        : 'type:rental';
                    $columnLabel = $sewaId > 0
                        ? (string) ($penyewaan?->sewa?->name ?? ('Penyewaan #' . $sewaId))
                        : 'Rental';
                } else {
                    $columnKey = 'type:' . (string) ($transaction->transaction_type ?? 'lain_lain');
                    $columnLabel = ucwords(str_replace('_', ' ', (string) ($transaction->transaction_type ?? 'lain_lain')));
                }

                $this->registerDetailReportColumn($columns, $rowsByDate, $footer, $columnKey, $columnLabel);

                $dateKey = Carbon::parse($transaction->created_at)->timezone('Asia/Jakarta')->format('Y-m-d');
                $dpp = max(0.0, ((float) ($transaction->bayar ?? 0)) - ((float) ($transaction->kembali ?? 0)));
                $ppn = (float) ($transaction->ppn ?? 0);
                $adminFee = $this->resolveAdminFee($transaction);
                $itemTotal = $dpp + $ppn + $adminFee;

                $this->addDetailReportValue(
                    $rowsByDate,
                    $footer,
                    $dateKey,
                    $columnKey,
                    $itemTotal,
                    $dpp,
                    $ppn,
                    $adminFee,
                    $columns
                );
            }
        }

        ksort($rowsByDate);

        return [
            'columns' => array_values($columns),
            'rows' => array_values($rowsByDate),
            'footer' => $footer,
            'showAdminFee' => (float) $footer['admin_fee'] > 0,
        ];
    }

    public function detailSummary(Request $request, string $scope)
    {
        $scopeMeta = $this->resolveDetailReportScope($scope);
        [$from, $to] = $this->resolveReportDateRange($request);
        $kasir = (string) $request->input('kasir', 'all');
        $reportData = $this->buildDetailReportData($scope, $from, $to, $kasir);
        $dateLabel = Carbon::parse($from)->format('d/m/Y') . ' s.d ' . Carbon::parse($to)->format('d/m/Y');
        $title = $scopeMeta['title'] . ' ' . $dateLabel;
        $breadcrumbs = ['Master', $scopeMeta['breadcrumb']];
        $users = User::query()->orderBy('name')->get();
        $reportHeading = $this->resolveReportHeading();

        return view('report.detail-summary', [
            'title' => $title,
            'breadcrumbs' => $breadcrumbs,
            'from' => $from,
            'to' => $to,
            'kasir' => $kasir,
            'users' => $users,
            'scopeMeta' => $scopeMeta,
            'reportHeading' => $reportHeading,
            'columns' => $reportData['columns'],
            'rows' => $reportData['rows'],
            'footer' => $reportData['footer'],
            'showAdminFee' => $reportData['showAdminFee'],
        ]);
    }

    public function exportDetailSummary(Request $request, string $scope)
    {
        $scopeMeta = $this->resolveDetailReportScope($scope);
        [$from, $to] = $this->resolveReportDateRange($request);
        $kasir = (string) $request->input('kasir', 'all');
        $reportData = $this->buildDetailReportData($scope, $from, $to, $kasir);
        $reportHeading = $this->resolveReportHeading();

        return Excel::download(
            new DetailCategoryReportExport(
                $from,
                $to,
                $reportData['columns'],
                $reportData['rows'],
                $reportData['footer'],
                $reportData['showAdminFee'],
                $scopeMeta,
                $reportHeading
            ),
            $scopeMeta['export_file']
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

        $resolveRowAmounts = function ($row): array {
            if (($row->transaction_type ?? '') === 'ticket') {
                if ($row->relationLoaded('detail')) {
                    $dpp = (float) $row->detail->sum('total');
                    $ppn = (float) $row->detail->sum('ppn');
                } else {
                    $dpp = (float) $row->detail()->sum('total');
                    $ppn = (float) $row->detail()->sum('ppn');
                }
            } else {
                $dpp = max(0.0, ((float) ($row->bayar ?? 0)) - ((float) ($row->kembali ?? 0)));
                $ppn = (float) ($row->ppn ?? 0);
            }

            $storedDisc = (float) ($row->disc ?? 0);
            $disc = $storedDisc > 0
                ? $storedDisc
                : ($dpp * (float) ($row->discount ?? 0) / 100);

            $adminFee = $this->resolveAdminFee($row);
            $total = max(0.0, $dpp - $disc + $ppn + $adminFee);

            return [
                'dpp' => $dpp,
                'ppn' => $ppn,
                'disc' => $disc,
                'admin_fee' => $adminFee,
                'total' => $total,
            ];
        };

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
            ->editColumn('harga', function ($row) use ($resolveRowAmounts) {
                $amounts = $resolveRowAmounts($row);
                return 'Rp. ' . number_format($amounts['dpp'], 0, ',', '.');
            })
            ->editColumn('jumlah', function ($row) use ($resolveRowAmounts) {
                $amounts = $resolveRowAmounts($row);
                return 'Rp. ' . number_format($amounts['dpp'], 0, ',', '.');
            })
            ->editColumn('discount', function ($row) use ($resolveRowAmounts) {
                $amounts = $resolveRowAmounts($row);
                return 'Rp. ' . number_format($amounts['disc'], 0, ',', '.');
            })
            ->addColumn('harga_ticket', function ($row) use ($resolveRowAmounts) {
                $amounts = $resolveRowAmounts($row);
                return 'Rp. ' . number_format($amounts['total'], 0, ',', '.');
            })
            ->editColumn('ppn', function ($row) use ($resolveRowAmounts) {
                $amounts = $resolveRowAmounts($row);
                return 'Rp. ' . number_format($amounts['ppn'], 0, ',', '.');
            })
            ->editColumn('admin_fee', function ($row) use ($resolveRowAmounts) {
                $amounts = $resolveRowAmounts($row);
                return 'Rp. ' . number_format($amounts['admin_fee'], 0, ',', '.');
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

        $lines = ['TANGGAL|KODE_TRANSAKSI|KETERANGAN_PRODUK|HARGA_SEBELUM_PBJT|PBJT|TOTAL_INCLUDE_PBJT'];
        foreach ($transactions as $trx) {
            $dateTime = Carbon::parse($trx->created_at)->timezone('Asia/Jakarta')->format('m/d/Y H:i:s');
            $ticketCode = trim((string) ($trx->ticket_code ?? ('TRX/' . $trx->id)));
            $productDesc = str_replace(
                ["\r", "\n", "|"],
                [' ', ' ', '/'],
                (string) $this->resolveProductDescription($trx)
            );

            if (($trx->transaction_type ?? '') === 'ticket') {
                $hargaSebelumPbjt = (float) $trx->detail->sum('total');
                $pbjt = (float) $trx->detail->sum('ppn');
            } else {
                $hargaSebelumPbjt = max(0.0, ((float) ($trx->bayar ?? 0)) - ((float) ($trx->kembali ?? 0)));
                $pbjt = (float) ($trx->ppn ?? 0);
            }

            $totalIncludePbjt = $hargaSebelumPbjt + $pbjt;

            $lines[] = implode('|', [
                $dateTime,
                $ticketCode,
                $productDesc,
                (int) round($hargaSebelumPbjt),
                (int) round($pbjt),
                (int) round($totalIncludePbjt),
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
        [$groupedRows, $grandQty, $grandPpn, $grandTotal] = $this->buildTransaksiLainnyaGroupedRows($from, $to, $kasir);
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
        [$groupedRows, $grandQty, $grandPpn, $grandTotal] = $this->buildTransaksiLainnyaGroupedRows($from, $to, $kasir);

        return Excel::download(
            new ReportPenyewaanExport($groupedRows->all(), $grandQty, $grandPpn, $grandTotal),
            "Laporan Transaksi Lainnya.xlsx"
        );
    }

    private function buildTransaksiLainnyaGroupedRows(string $from, string $to, string $kasir = 'all'): array
    {
        $toExclusive = Carbon::parse($to)->addDay()->format('Y-m-d');

        $transactionQuery = Transaction::query()
            ->with(['user:id,name', 'detail.ticket:id,name'])
            ->where('is_active', 1)
            ->whereIn('transaction_type', ['ticket', 'rental', 'registration', 'renewal'])
            ->whereBetween('created_at', [$from, $toExclusive]);

        if ($kasir !== 'all' && $kasir !== '') {
            $transactionQuery->where('user_id', $kasir);
        }

        $transactions = $transactionQuery
            ->orderBy('created_at')
            ->orderBy('id')
            ->get();

        $membershipMap = Membership::query()
            ->whereIn('id', $transactions->whereIn('transaction_type', ['registration', 'renewal'])->pluck('ticket_id')->filter()->unique()->values())
            ->pluck('name', 'id');

        $penyewaanMap = Penyewaan::query()
            ->with('sewa:id,name')
            ->whereIn('id', $transactions->where('transaction_type', 'rental')->pluck('ticket_id')->filter()->unique()->values())
            ->get()
            ->keyBy('id');

        $detailRows = collect();

        foreach ($transactions as $trx) {
            $kasirName = $trx->user->name ?? '-';
            $createdAt = Carbon::parse($trx->created_at)->timezone('Asia/Jakarta');
            $tanggal = $createdAt->format('d/m/Y H:i:s');
            $tanggalKey = $createdAt->format('Y-m-d');
            $tanggalLabel = $createdAt->format('d/m/Y');
            $kodeTrx = (string) ($trx->ticket_code ?? ('TRX/' . $trx->id));
            $metode = strtoupper((string) ($trx->metode ?? '-'));
            $noBukti = (string) ((int) ($trx->no_trx ?? 0) > 0 ? $trx->no_trx : $trx->id);

            if (($trx->transaction_type ?? '') === 'ticket') {
                foreach ($trx->detail as $detail) {
                    $qty = max((int) ($detail->qty ?? 1), 1);
                    $ppn = (float) ($detail->ppn ?? 0);
                    $totalBayar = (float) ($detail->total ?? 0) + $ppn;
                    $ticketId = (int) ($detail->ticket_id ?? 0);
                    $itemName = (string) ($detail->ticket->name ?? 'Ticket');

                    $detailRows->push([
                        'group_key' => 'ticket:' . $ticketId,
                        'transaction_type_label' => 'Ticket',
                        'item_name' => $itemName,
                        'no_bukti' => $noBukti,
                        'tanggal' => $tanggal,
                        'tanggal_key' => $tanggalKey,
                        'tanggal_label' => $tanggalLabel,
                        'kasir' => $kasirName,
                        'kode_trx' => $kodeTrx,
                        'qty' => $qty,
                        'metode' => $metode,
                        'ppn' => $ppn,
                        'total_bayar' => $totalBayar,
                    ]);
                }
                continue;
            }

            if (in_array($trx->transaction_type, ['registration', 'renewal'], true)) {
                $membershipId = (int) ($trx->ticket_id ?? 0);
                $itemName = (string) ($membershipMap[$membershipId] ?? 'Membership');
                $qty = max((int) ($trx->amount ?? 1), 1);
                $ppn = (float) ($trx->ppn ?? 0);
                $adminFee = $this->resolveAdminFee($trx);
                $totalBayar = max(0.0, ((float) ($trx->bayar ?? 0)) - ((float) ($trx->kembali ?? 0))) + $ppn + $adminFee;

                $detailRows->push([
                    'group_key' => 'membership:' . $membershipId,
                    'transaction_type_label' => 'Membership',
                    'item_name' => $itemName,
                    'no_bukti' => $noBukti,
                    'tanggal' => $tanggal,
                    'tanggal_key' => $tanggalKey,
                    'tanggal_label' => $tanggalLabel,
                    'kasir' => $kasirName,
                    'kode_trx' => $kodeTrx,
                    'qty' => $qty,
                    'metode' => $metode,
                    'ppn' => $ppn,
                    'total_bayar' => $totalBayar,
                ]);
                continue;
            }

            if (($trx->transaction_type ?? '') === 'rental') {
                $penyewaanId = (int) ($trx->ticket_id ?? 0);
                $penyewaan = $penyewaanMap->get($penyewaanId);
                $sewaId = (int) ($penyewaan->sewa_id ?? $penyewaanId);
                $itemName = (string) ($penyewaan?->sewa?->name ?? 'Penyewaan');
                $qty = max((int) ($trx->amount ?? ($penyewaan->qty ?? 1)), 1);
                $ppn = (float) ($trx->ppn ?? 0);
                $totalBayar = max(0.0, ((float) ($trx->bayar ?? 0)) - ((float) ($trx->kembali ?? 0))) + $ppn;

                $detailRows->push([
                    'group_key' => 'rental:' . $sewaId,
                    'transaction_type_label' => 'Penyewaan',
                    'item_name' => $itemName,
                    'no_bukti' => $noBukti,
                    'tanggal' => $tanggal,
                    'tanggal_key' => $tanggalKey,
                    'tanggal_label' => $tanggalLabel,
                    'kasir' => $kasirName,
                    'kode_trx' => $kodeTrx,
                    'qty' => $qty,
                    'metode' => $metode,
                    'ppn' => $ppn,
                    'total_bayar' => $totalBayar,
                ]);
            }
        }

        $groupedRows = $detailRows
            ->groupBy('tanggal_key')
            ->sortKeys()
            ->map(function ($dateItems, $dateKey) {
                $firstDateItem = $dateItems->first();
                $dateLabel = (string) ($firstDateItem['tanggal_label'] ?? Carbon::parse($dateKey)->format('d/m/Y'));

                $itemGroups = $dateItems
                    ->groupBy('group_key')
                    ->map(function ($items) {
                        $details = $items->values()->map(function ($item, $index) {
                            $item['no'] = $index + 1;
                            return $item;
                        });

                        return [
                            'transaction_type_label' => (string) ($items->first()['transaction_type_label'] ?? '-'),
                            'item_name' => (string) ($items->first()['item_name'] ?? '-'),
                            'details' => $details,
                            'subtotal_qty' => (int) $details->sum('qty'),
                            'subtotal_ppn' => (float) $details->sum('ppn'),
                            'subtotal_total' => (float) $details->sum('total_bayar'),
                        ];
                    })
                    ->values();

                return [
                    'tanggal_key' => (string) $dateKey,
                    'tanggal_label' => $dateLabel,
                    'groups' => $itemGroups,
                    'rowspan' => (int) ($itemGroups->sum(function ($group) {
                        return count($group['details']) + 1;
                    }) + 1),
                    'subtotal_qty' => (int) $itemGroups->sum('subtotal_qty'),
                    'subtotal_ppn' => (float) $itemGroups->sum('subtotal_ppn'),
                    'subtotal_total' => (float) $itemGroups->sum('subtotal_total'),
                ];
            })
            ->values();

        $grandQty = (int) $groupedRows->sum('subtotal_qty');
        $grandPpn = (float) $groupedRows->sum('subtotal_ppn');
        $grandTotal = (float) $groupedRows->sum('subtotal_total');

        return [$groupedRows, $grandQty, $grandPpn, $grandTotal];
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
