@extends('layouts.master', ['title' => $title, 'breadcrumbs' => $breadcrumbs])

@push('style')
<link href="{{ asset('/') }}plugins/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
<link href="{{ asset('/') }}plugins/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" />
<link href="{{ asset('/') }}plugins/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet" />
<style>
    @media print {
        #header,
        #sidebar,
        .app-sidebar-bg,
        .app-sidebar-mobile-backdrop,
        .breadcrumb,
        .page-header {
            display: none !important;
        }

        #content.app-content {
            margin: 0 !important;
            padding: 0 !important;
        }

        .no-print,
        .panel-heading-btn {
            display: none !important;
        }

        .panel {
            border: 0 !important;
            box-shadow: none !important;
        }

        .panel-body {
            padding: 0 !important;
        }

        .table {
            width: 100% !important;
        }
    }
</style>
@endpush

@section('content')
<div class="panel panel-inverse">
    <div class="panel-heading">
        <h4 class="panel-title">{{ $title }}</h4>
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand"><i class="fa fa-expand"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-success" data-toggle="panel-reload"><i class="fa fa-redo"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-warning" data-toggle="panel-collapse"><i class="fa fa-minus"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-danger" data-toggle="panel-remove"><i class="fa fa-times"></i></a>
        </div>
    </div>

    <div class="panel-body">

        <form action="" method="get" class="row mb-3 no-print">
            <div class="col-md-3">
                <label for="daterange">Tanggal</label>
                <input type="text" name="daterange" id="daterange" class="form-control"
                    value="{{ request('daterange') ?: now('Asia/Jakarta')->format('m/d/Y') . ' - ' . now('Asia/Jakarta')->format('m/d/Y') }}">
                <input type="hidden" name="from" id="from" value="{{ request('from') ?? Carbon\Carbon::now()->format('Y-m-d') }}">
                <input type="hidden" name="to" id="to" value="{{ request('to') ?? Carbon\Carbon::now()->format('Y-m-d') }}">
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label for="kasir">Kasir</label>
                    <select name="kasir" id="kasir" class="form-control">
                        <option value="all" selected>All</option>
                        @foreach($users as $user)
                        <option {{ request('kasir') == $user->id ? 'selected' : '' }} value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-3 mt-1">
                <button type="submit" class="btn btn-primary mt-3">Submit</button>
                <a href="{{ route('transactions.export') }}?from={{ request('from') }}&to={{ request('to') }}&kasir={{ request('kasir') }}" class="btn btn-success mt-3">Export</a>
                <a href="#" id="btn-print-view" class="btn btn-info mt-3"><i class="fas fa-print me-1"></i>Print</a>
            </div>
        </form>

        <div class="mb-5">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th colspan="5">Report Transaction Ticket Tanggal {{ Carbon\Carbon::parse($from)->format('d/m/Y') }} - {{ request('to') ? Carbon\Carbon::parse($to)->subDay(1)->format('d/m/Y') : Carbon\Carbon::parse($from)->format('d/m/Y') }}</th>
                    </tr>
                    <tr>
                        <th>Jenis Ticket</th>
                        <th class="text-center">Jumlah</th>
                        <th class="text-center">Harga Ticket</th>
                        <th class="text-center">PBJT</th>
                        <th class="text-end">Total Harga Ticket</th>
                    </tr>
                </thead>
                <tbody>
                    @php
    // ... kode PHP perhitungan sebelum loop tetap sama
    $queryTrxTicket = App\Models\Transaction::where(['is_active' => 1, 'transaction_type' => 'ticket'])->whereBetween('created_at', [$from, $to]);
    if (request('kasir') != 'all' && request('kasir')) {
        $queryTrxTicket->where('user_id', request('kasir'));
    }
    $idTrxTicket = $queryTrxTicket->pluck('id');
    $totalQtyTicket = 0;
    $totalAmountTicket = 0;
@endphp

@foreach($tickets as $ticket)
    @php
        $qty = App\Models\DetailTransaction::whereIn('transaction_id', $idTrxTicket)->where('ticket_id', $ticket->id)->sum('qty');
        $totalPerTicket = App\Models\DetailTransaction::whereIn('transaction_id', $idTrxTicket)->where('ticket_id', $ticket->id)->sum(\DB::raw('total + ppn'));

        if ($qty > 0) {
            $totalQtyTicket += $qty;
            $totalAmountTicket += $totalPerTicket;
        }
    @endphp

    @if($qty > 0)
        <tr>
            <td>{{ $ticket->name }}</td>
            <td class="text-center">{{ $qty }}</td>
            <td class="text-center">{{ number_format($ticket->harga + $ticket->ppn,0, ',', '.') }}</td>
            <td class="text-center">
                <input type="checkbox" class="form-check-input" {{ $ticket->use_ppn == 1 ? 'checked' : '' }} disabled>
            </td>
            <td class="text-end">
                {{ number_format($totalPerTicket, 0, ',', '.') }}
            </td>
        </tr>
    @endif
@endforeach
                    <tr>
                        <th>Total Penjualan Ticket :</th>
                        <th class="text-center"><b>{{ $totalQtyTicket }}</b></th>
                        <th colspan="2"></th>
                        <th class="text-end"><b>{{ number_format($totalAmountTicket, 0, ',', '.') }}</b></th>
                    </tr>
                </tbody>
            </table>
        </div>

        <hr/>

        <div class="mb-5">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th colspan="5">Report Transaction Non-Ticket Tanggal {{ Carbon\Carbon::parse($from)->format('d/m/Y') }} - {{ request('to') ? Carbon\Carbon::parse($to)->subDay(1)->format('d/m/Y') : Carbon\Carbon::parse($from)->format('d/m/Y') }}</th>
                    </tr>
                    <tr>
                        <th>Jenis Transaksi</th>
                        <th class="text-center">Jumlah</th>
                        <th class="text-center">Harga</th>
                        <th class="text-center">PBJT</th>
                        <th class="text-end">Total Harga</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $totalQtyNonTicket = 0;
                        $totalAmountNonTicket = 0;
                        $orderedMemberships = $memberships->sortBy('name');
                        $membershipTrxTypes = ['registration', 'renewal'];
                        $adminFeeExpr = "(CASE WHEN transaction_type IN ('registration', 'renewal') THEN admin_fee ELSE 0 END)";
                    @endphp

                    @foreach($membershipTrxTypes as $type)
                        @foreach($orderedMemberships as $membership)
                            @php
                                $queryTrxMembership = App\Models\Transaction::where(['is_active' => 1, 'transaction_type' => $type, 'ticket_id' => $membership->id])->whereBetween('created_at', [$from, $to]);
                                if (request('kasir') != 'all' && request('kasir')) {
                                    $queryTrxMembership->where('user_id', request('kasir'));
                                }
                                $qtyMembership = $queryTrxMembership->count();
                                $totalPerMembership = $queryTrxMembership->sum(\DB::raw('(bayar - kembali) + ppn + admin_fee'));
                                $totalQtyNonTicket += $qtyMembership;
                                $totalAmountNonTicket += $queryTrxMembership->sum(\DB::raw('(bayar - kembali) + admin_fee'));
                            @endphp
                            @if($qtyMembership > 0)
                            <tr>
                                <td>{{ ucfirst($type) }} ({{ $membership->name }})</td>
                                <td class="text-center">{{ $qtyMembership }}</td>
                                <td class="text-center">{{ number_format($membership->price, 0, ',', '.') }}</td>
                                <td class="text-center">
                                    <input type="checkbox" class="form-check-input" {{ $membership->use_ppn == 1 ? 'checked' : '' }} disabled>
                                </td>
                                <td class="text-end">
                                    {{ number_format($totalPerMembership, 0, ',', '.') }}
                                </td>
                            </tr>
                            @endif
                        @endforeach
                    @endforeach

                   @php
                        $typeRental = 'rental';
                    @endphp
                    @foreach($sewa as $sewaItem)
                        @php
                            $rentalPenyewaanIds = App\Models\Penyewaan::where('sewa_id', $sewaItem->id)->pluck('id');
                            $queryTrxRental = App\Models\Transaction::where(['is_active' => 1, 'transaction_type' => $typeRental])
                                ->whereIn('ticket_id', $rentalPenyewaanIds)
                                ->whereBetween('created_at', [$from, $to]);
                            if (request('kasir') != 'all' && request('kasir')) {
                                $queryTrxRental->where('user_id', request('kasir'));
                            }

                            $qtyRental = $queryTrxRental->count();
                            $totalPerRental = $queryTrxRental->sum(\DB::raw('(bayar - kembali) + ppn'));

                            $totalQtyNonTicket += $qtyRental;
                            $totalAmountNonTicket += $queryTrxRental->sum(\DB::raw('(bayar - kembali)'));
                        @endphp
                        @if($qtyRental > 0)
                        <tr>
                            <td>Rental ({{ $sewaItem->name }})</td>
                            <td class="text-center">{{ $qtyRental }}</td>
                            <td class="text-center">{{ number_format($sewaItem->harga, 0, ',', '.') }}</td>
                            <td class="text-center">
                                <input type="checkbox" class="form-check-input" {{ $sewaItem->use_ppn == 1 ? 'checked' : '' }} disabled>
                            </td>
                            <td class="text-end">
                                {{ number_format($totalPerRental, 0, ',', '.') }}
                            </td>
                        </tr>
                        @endif
                    @endforeach
                    <tr>
                        <th>Total Penjualan Non-Ticket :</th>
                        <th class="text-center"><b>{{ $totalQtyNonTicket }}</b></th>
                        <th colspan="2"></th>
                        <th class="text-end"><b>{{ number_format($totalAmountNonTicket, 0, ',', '.') }}</b></th>
                    </tr>
                </tbody>
            </table>
        </div>

        <hr/>

        @php
            $queryTrxAll = App\Models\Transaction::where(['is_active' => 1])->whereBetween('created_at', [$from, $to]);
            if (request('kasir') != 'all' && request('kasir')) {
                $queryTrxAll->where('user_id', request('kasir'));
            }
            $idTrxAll = $queryTrxAll->pluck('id');

            $totalSalesQty = App\Models\DetailTransaction::whereIn('transaction_id', $idTrxTicket)->sum('qty') + $totalQtyNonTicket;

            $totalDiscount = $queryTrxAll->sum('disc');
            $totalPPN = App\Models\DetailTransaction::whereIn('transaction_id', $idTrxAll)->sum('ppn') +
                                App\Models\Transaction::whereIn('id', $idTrxAll)->whereIn('transaction_type', ['renewal', 'registration', 'rental'])->sum('ppn');
            $totalAdminFee = App\Models\Transaction::whereIn('id', $idTrxAll)->sum(\DB::raw($adminFeeExpr));
            $cashid = $queryTrxAll->clone()
                ->where('metode', 'cash')
                ->pluck('id');
            $debitid = $queryTrxAll->clone()
                ->where('metode', 'debit')
                ->pluck('id');
            $kreditid = $queryTrxAll->clone()
                ->whereIn('metode', ['kredit', 'credit', 'credit card'])
                ->pluck('id');
            $qrisid = $queryTrxAll->clone()
                ->whereIn('metode', ['qris', 'qr'])
                ->pluck('id');
            $transferid = $queryTrxAll->clone()
                ->where('metode', 'transfer')
                ->pluck('id');
            $lainnyaid = $queryTrxAll->clone()
                ->where(function ($q) {
                    $q->whereIn('metode', ['tap', 'lain-lain'])
                        ->orWhereNull('metode')
                        ->orWhere('metode', '');
                })
                ->pluck('id');

            $calculateTotalPerMethod = function ($trxIds) {
                $detailTotal = App\Models\DetailTransaction::whereIn('transaction_id', $trxIds)->sum(\DB::raw('total + ppn'));
                $trxNonDetailTotal = App\Models\Transaction::whereIn('id', $trxIds)
                    ->whereIn('transaction_type', ['renewal', 'registration', 'rental'])
                    ->sum(\DB::raw('(bayar - kembali) + ' . "(CASE WHEN transaction_type IN ('registration', 'renewal') THEN admin_fee ELSE 0 END)"));
                return $detailTotal + $trxNonDetailTotal;
            };

            $cashTotal = $calculateTotalPerMethod($cashid);
            $debitTotal = $calculateTotalPerMethod($debitid);
            $kreditTotal = $calculateTotalPerMethod($kreditid);
            $qrisTotal = $calculateTotalPerMethod($qrisid);
            $transferTotal = $calculateTotalPerMethod($transferid);
            $lainnyaTotal = $calculateTotalPerMethod($lainnyaid);

            $cashQty = $cashid->count();
            $debitQty = $debitid->count();
            $kreditQty = $kreditid->count();
            $qrisQty = $qrisid->count();
            $transferQty = $transferid->count();
            $lainnyaQty = $lainnyaid->count();

            $pembayaranLainnyaTotal = $lainnyaTotal;
            $pembayaranLainnyaQty = $lainnyaQty;
            $grandTotalIncome = $cashTotal + $debitTotal + $qrisTotal + $kreditTotal + $transferTotal + $pembayaranLainnyaTotal;
            $grandTotalQtyAll = $cashQty + $debitQty + $qrisQty + $kreditQty + $transferQty + $pembayaranLainnyaQty;

            // Samakan basis hitung total akhir dengan ringkasan metode pembayaran
            // agar tidak terjadi selisih antar bagian report.
            $totalSalesAmount = $grandTotalIncome;
            $totalAmountSetelahDiskon = $grandTotalIncome - $totalDiscount;
            $totalAmountAkhirPlusPBJTAdmin = $totalAmountSetelahDiskon + $totalPPN + $totalAdminFee;
        @endphp


        <table class="table table-bordered table-hover">
            <tbody>
                <tr>
                    <th>Total Penjualan :</th>
                    <th class="text-center">
                        <b>{{ $totalSalesQty }}</b>
                    </th>
                    <th></th>
                    <th></th>
                    <th class="text-end">
                        <b>{{ number_format($totalSalesAmount, 0, ',', '.') }}</b>
                    </th>
                </tr>
                <tr>
                    <th colspan="4">Total Discount :</th>
                    <th class="text-end">
                        <b>{{ number_format($totalDiscount, 0, ',', '.') }}</b>
                    </th>
                </tr>
                <tr>
                    <th colspan="4">Total PBJT :</th>
                    <th class="text-end">
                        <b>{{ number_format($totalPPN, 0, ',', '.') }}</b>
                    </th>
                </tr>
                <tr>
                    <th colspan="4">Total Biaya Admin :</th>
                    <th class="text-end">
                        <b>{{ number_format($totalAdminFee, 0, ',', '.') }}</b>
                    </th>
                </tr>
                <tr>
                    <th rowspan="7"></th>
                    <th>TOTAL TUNAI</th>
                    <th class="text-end">Rp {{ number_format($cashTotal, 2, ',', '.') }}</th>
                    <th>QTY TUNAI</th>
                    <th class="text-end">{{ $cashQty }} Orang</th>
                </tr>
                <tr>
                    <th>TOTAL DEBIT</th>
                    <th class="text-end">Rp {{ number_format($debitTotal, 2, ',', '.') }}</th>
                    <th>QTY DEBIT</th>
                    <th class="text-end">{{ $debitQty }} Orang</th>
                </tr>
                <tr>
                    <th>TOTAL QRIS</th>
                    <th class="text-end">Rp {{ number_format($qrisTotal, 2, ',', '.') }}</th>
                    <th>QTY QRIS</th>
                    <th class="text-end">{{ $qrisQty }} Orang</th>
                </tr>
                <tr>
                    <th>TOTAL CREDIT CARD</th>
                    <th class="text-end">Rp {{ number_format($kreditTotal, 2, ',', '.') }}</th>
                    <th>QTY CREDIT CARD</th>
                    <th class="text-end">{{ $kreditQty }} Orang</th>
                </tr>
                <tr>
                    <th>TOTAL TRANSFER</th>
                    <th class="text-end">Rp {{ number_format($transferTotal, 2, ',', '.') }}</th>
                    <th>QTY TRANSFER</th>
                    <th class="text-end">{{ $transferQty }} Orang</th>
                </tr>
                <tr>
                    <th>TOTAL PEMBAYARAN LAINNYA</th>
                    <th class="text-end">Rp {{ number_format($pembayaranLainnyaTotal, 2, ',', '.') }}</th>
                    <th>QTY PEMBAYARAN LAINNYA</th>
                    <th class="text-end">{{ $pembayaranLainnyaQty }} Orang</th>
                </tr>
                <tr class="table-secondary">
                    <th>GRANDTOTAL INCOME</th>
                    <th class="text-end">Rp {{ number_format($grandTotalIncome, 2, ',', '.') }}</th>
                    <th>GRANDTOTAL QTY ALL</th>
                    <th class="text-end">{{ $grandTotalQtyAll }} Orang</th>
                </tr>
                <tr>
                    <th colspan="4">Total Amount Akhir (Total Penjualan - Diskon) :</th>
                    <th class="text-end">
                        <b>{{ number_format($totalAmountSetelahDiskon, 0, ',', '.') }}</b>
                    </th>
                </tr>
                <tr class="table-primary">
                    <th colspan="4">Total Amount Akhir (Total Penjualan - Diskon) + PBJT + Biaya Admin:</th>
                    <th class="text-end">
                        <b>{{ number_format($totalAmountAkhirPlusPBJTAdmin, 0, ',', '.') }}</b>
                    </th>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('script')
<script src="{{ asset('/') }}plugins/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="{{ asset('/') }}plugins/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="{{ asset('/') }}plugins/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="{{ asset('/') }}plugins/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>
<script src="{{ asset('/') }}plugins/sweetalert/dist/sweetalert.min.js"></script>
<script src="{{ asset('/') }}plugins/moment/min/moment.min.js"></script>
<script src="{{ asset('/') }}plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
<script>
    function syncRekapDateRangeFields() {
        const rangeValue = $("#daterange").val() || '';
        if (rangeValue.includes(' - ')) {
            const parts = rangeValue.split(' - ');
            const start = moment(parts[0], 'MM/DD/YYYY', true);
            const end = moment(parts[1], 'MM/DD/YYYY', true);
            if (start.isValid() && end.isValid()) {
                $("#from").val(start.format('YYYY-MM-DD'));
                $("#to").val(end.format('YYYY-MM-DD'));
                return;
            }
        }

        const today = moment().format('YYYY-MM-DD');
        $("#from").val(today);
        $("#to").val(today);
    }

    (function initRekapDateRange() {
        const today = moment();
        let start = today.clone();
        let end = today.clone();
        const rangeValue = $("#daterange").val();

        if (rangeValue && rangeValue.includes(' - ')) {
            const parts = rangeValue.split(' - ');
            const parsedStart = moment(parts[0], 'MM/DD/YYYY', true);
            const parsedEnd = moment(parts[1], 'MM/DD/YYYY', true);
            if (parsedStart.isValid() && parsedEnd.isValid()) {
                start = parsedStart;
                end = parsedEnd;
            }
        } else if ($("#from").val() && $("#to").val()) {
            const parsedFrom = moment($("#from").val(), 'YYYY-MM-DD', true);
            const parsedTo = moment($("#to").val(), 'YYYY-MM-DD', true);
            if (parsedFrom.isValid() && parsedTo.isValid()) {
                start = parsedFrom;
                end = parsedTo;
            }
        }

        $("#daterange").daterangepicker({
            opens: "right",
            autoUpdateInput: true,
            locale: {
                format: "MM/DD/YYYY",
                separator: " - "
            },
            startDate: start,
            endDate: end
        });

        $("#daterange").val(start.format('MM/DD/YYYY') + ' - ' + end.format('MM/DD/YYYY'));
        syncRekapDateRangeFields();

        $("#daterange").on('apply.daterangepicker', function() {
            syncRekapDateRangeFields();
        });
    })();

    $("#btn-print-view").on('click', function(e) {
        e.preventDefault();
        window.print();
    });
</script>
@endpush
