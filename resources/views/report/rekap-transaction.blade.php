@extends('layouts.master', ['title' => $title, 'breadcrumbs' => $breadcrumbs])

@push('style')
<link href="{{ asset('/') }}plugins/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
<link href="{{ asset('/') }}plugins/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" />
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

        <form action="" method="get" class="row mb-3">
            <div class="col-md-3">
                <label for="from">From</label>
                <input type="date" name="from" id="from" class="form-control" value="{{ request('from') ?? Carbon\Carbon::now()->format('Y-m-d') }}">
            </div>

            <div class="col-md-3">
                <label for="to">To</label>
                <input type="date" name="to" id="to" class="form-control" value="{{ request('to') ?? Carbon\Carbon::now()->format('Y-m-d') }}">
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
                <a href="{{ route('transactions.download') }}?from={{ request('from') }}&to={{ request('to') }}&kasir={{ request('kasir') }}" class="btn btn-info mt-3"><i class="fas fa-print me-1"></i>Print</a>
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
                        <th class="text-center">PPN</th>
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
                        <th class="text-center">PPN</th>
                        <th class="text-end">Total Harga</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $totalQtyNonTicket = 0;
                        $totalAmountNonTicket = 0;

                        $membershipTrxTypes = ['renewal', 'registration'];
                    @endphp

                    @foreach($memberships as $membership)
                        @foreach($membershipTrxTypes as $type)
                            @php
                                $queryTrxMembership = App\Models\Transaction::where(['is_active' => 1, 'transaction_type' => $type, 'ticket_id' => $membership->id])->whereBetween('created_at', [$from, $to]);
                                if (request('kasir') != 'all' && request('kasir')) {
                                    $queryTrxMembership->where('user_id', request('kasir'));
                                }
                                $qtyMembership = $queryTrxMembership->count();
                                $totalPerMembership = $queryTrxMembership->sum(\DB::raw('(bayar - kembali) + ppn'));
                                $totalQtyNonTicket += $qtyMembership;
$totalAmountNonTicket += $queryTrxMembership->sum(\DB::raw('bayar - kembali'));
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
                            $queryTrxRental = App\Models\Transaction::where(['is_active' => 1, 'transaction_type' => $typeRental, 'ticket_id' => $sewaItem->id])->whereBetween('created_at', [$from, $to]);
                            if (request('kasir') != 'all' && request('kasir')) {
                                $queryTrxRental->where('user_id', request('kasir'));
                            }

                            $qtyRental = $queryTrxRental->count();
                            $totalPerRental = $queryTrxRental->sum(\DB::raw('bayar - kembali'));

                            $totalQtyNonTicket += $qtyRental;
                            $totalAmountNonTicket += $totalPerRental;
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
            $totalSalesAmount = $totalAmountTicket + $totalAmountNonTicket;

            $totalDiscount = $queryTrxAll->sum('disc');
            $totalPPN = App\Models\DetailTransaction::whereIn('transaction_id', $idTrxAll)->sum('ppn') +
                                App\Models\Transaction::whereIn('id', $idTrxAll)->whereIn('transaction_type', ['renewal', 'registration', 'rental'])->sum('ppn');
            $cashid = $queryTrxAll->clone()->where('metode', 'cash')->pluck('id');
            $debitid = $queryTrxAll->clone()->where('metode', 'debit')->pluck('id');
            $kreditid = $queryTrxAll->clone()->where('metode', 'kredit')->pluck('id');
            $qrisid = $queryTrxAll->clone()->where('metode', 'qris')->pluck('id');
            $transferid = $queryTrxAll->clone()->where('metode', 'transfer')->pluck('id');

            $calculateTotalPerMethod = function ($trxIds) {
                $detailTotal = App\Models\DetailTransaction::whereIn('transaction_id', $trxIds)->sum(\DB::raw('total + ppn'));
                $trxNonDetailTotal = App\Models\Transaction::whereIn('id', $trxIds)->whereIn('transaction_type', ['renewal', 'registration', 'rental'])->sum(\DB::raw('bayar - kembali')); // Tambah 'rental'
                return $detailTotal + $trxNonDetailTotal;
            };

            $cashTotal = $calculateTotalPerMethod($cashid);
            $debitTotal = $calculateTotalPerMethod($debitid);
            $kreditTotal = $calculateTotalPerMethod($kreditid);
            $qrisTotal = $calculateTotalPerMethod($qrisid);
            $transferTotal = $calculateTotalPerMethod($transferid);

            $finalTotalAmount = $cashTotal + $debitTotal + $kreditTotal + $qrisTotal + $transferTotal; // Ini harusnya sama dengan totalSalesAmount - totalDiscount

            // Perhitungan baru
            $totalAmountSetelahDiskon = $totalSalesAmount - $totalDiscount;
            $totalAmountAkhirPlusPPN = $totalAmountSetelahDiskon + $totalPPN; // Ini berdasarkan Total Penjualan (sudah termasuk PPN) - Diskon + PPN (ini mungkin TIDAK AKURAT, lihat catatan di bawah)


            $totalAmountAkhirPlusPPN = $totalAmountSetelahDiskon + $totalPPN;
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
                    <th colspan="4">Total PPN :</th>
                    <th class="text-end">
                        <b>{{ number_format($totalPPN, 0, ',', '.') }}</b>
                    </th>
                </tr>
                <tr>
                    <th>Metode Pembayaran :</th>
                    <th class="text-center">Cash</th>
                    <th colspan="3" class="text-end">
                        {{ number_format($cashTotal, 0, ',', '.') }}
                    </th>
                </tr>
                <tr>
                    <th rowspan="4"></th>
                    <th class="text-center">Debit</th>
                    <th colspan="3" class="text-end">
                        {{ number_format($debitTotal, 0, ',', '.') }}
                    </th>
                </tr>
                <tr>
                    <th class="text-center">Kredit</th>
                    <th colspan="3" class="text-end">
                        {{ number_format($kreditTotal, 0, ',', '.') }}
                    </th>
                </tr>
                <tr>
                    <th class="text-center">QRIS</th>
                    <th colspan="3" class="text-end">
                        {{ number_format($qrisTotal, 0, ',', '.') }}
                    </th>
                </tr>
                <tr>
                    <th class="text-center">Transfer</th>
                    <th colspan="3" class="text-end">
                        {{ number_format($transferTotal, 0, ',', '.') }}
                    </th>
                </tr>
                <tr>
                    <th colspan="4">Total Amount Akhir (Total Penjualan - Diskon) :</th>
                    <th class="text-end">
                        <b>{{ number_format($totalAmountSetelahDiskon, 0, ',', '.') }}</b>
                    </th>
                </tr>
                {{-- BARIS BARU DITAMBAHKAN DI SINI --}}
                <tr class="table-primary">
                    <th colspan="4">Total Amount Akhir (Total Penjualan - Diskon) + PPN:</th>
                    <th class="text-end">
                        <b>{{ number_format($totalAmountAkhirPlusPPN, 0, ',', '.') }}</b>
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
@endpush
