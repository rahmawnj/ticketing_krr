<!DOCTYPE html>
<html>
<head>
    <title>Print Transaction Report</title>
    <style>
        body { font-family: 'Courier New', Courier, monospace; font-size: 12px; }
        .table {
            width: 100%;
            max-width: 80mm !important;
            margin: 0 auto;
            border-collapse: collapse;
        }
        .table border, .table th, .table td {
            border: 1px solid black;
            padding: 5px;
        }
        .text-center { text-align: center; }
        .text-end { text-align: right; }
        .mb-3 { margin-bottom: 15px; }
        .mt-3 { margin-top: 15px; }

        @media print {
            .table {
                max-width: 72mm !important;
            }
            @page { margin: 0; }
        }
    </style>
</head>
<body>

@php
    // Penentuan Nama Kasir
    $namaKasir = 'All';
    if($kasir != 'all') {
        $u = $users->where('id', $kasir)->first();
        $namaKasir = $u ? $u->name : 'Unknown';
    }

    // Query Dasar Transaksi
    $baseQuery = App\Models\Transaction::where('is_active', 1)->whereBetween('created_at', [$from, $to]);
    if ($kasir != 'all') {
        $baseQuery->where('user_id', $kasir);
    }

    $idTrxAll = $baseQuery->pluck('id');
@endphp

<div class="table">
    <div class="text-center mb-3">
        <strong>REPORT TRANSACTION</strong><br>
        {{ Carbon\Carbon::parse($from)->format('d/m/Y') }} - {{ Carbon\Carbon::parse($to)->subDay(1)->format('d/m/Y') }}<br>
        Kasir: {{ $namaKasir }}
    </div>

    <table class="table" border="1" style="margin-bottom: 10px;">
        <thead>
            <tr>
                <th colspan="3">Report Transaction Ticket</th>
            </tr>
            <tr>
                <th>Jenis</th>
                <th class="text-center">Qty</th>
                <th class="text-end">Total</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalQtyTicket = 0;
                $totalAmountTicket = 0;
                $queryTrxTicket = (clone $baseQuery)->where('transaction_type', 'ticket');
                $idTrxTicket = $queryTrxTicket->pluck('id');
            @endphp
            @foreach($tickets as $ticket)
                @php
                    $qty = App\Models\DetailTransaction::whereIn('transaction_id', $idTrxTicket)->where('ticket_id', $ticket->id)->sum('qty');
                    $totalPerTicket = App\Models\DetailTransaction::whereIn('transaction_id', $idTrxTicket)->where('ticket_id', $ticket->id)->sum(\DB::raw('total + ppn'));
                @endphp
                @if($qty > 0)
                @php $totalQtyTicket += $qty; $totalAmountTicket += $totalPerTicket; @endphp
                <tr>
                    <td>{{ $ticket->name }}</td>
                    <td class="text-center">{{ $qty }}</td>
                    <td class="text-end">{{ number_format($totalPerTicket, 0, ',', '.') }}</td>
                </tr>
                @endif
            @endforeach
            <tr>
                <th>Subtotal Ticket</th>
                <th class="text-center">{{ $totalQtyTicket }}</th>
                <th class="text-end">{{ number_format($totalAmountTicket, 0, ',', '.') }}</th>
            </tr>
        </tbody>
    </table>

    <table class="table" border="1" style="margin-bottom: 10px;">
        <thead>
            <tr>
                <th colspan="3">Report Transaction Non-Ticket</th>
            </tr>
            <tr>
                <th>Jenis</th>
                <th class="text-center">Qty</th>
                <th class="text-end">Total</th>
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
                        $queryM = (clone $baseQuery)->where(['transaction_type' => $type, 'ticket_id' => $membership->id]);
                        $qtyM = $queryM->count();
                        $totalM = $queryM->sum(\DB::raw('(bayar - kembali) + ppn'));
                    @endphp
                    @if($qtyM > 0)
                    @php $totalQtyNonTicket += $qtyM; $totalAmountNonTicket += $totalM; @endphp
                    <tr>
                        <td>{{ ucfirst($type) }} {{ $membership->name }}</td>
                        <td class="text-center">{{ $qtyM }}</td>
                        <td class="text-end">{{ number_format($totalM, 0, ',', '.') }}</td>
                    </tr>
                    @endif
                @endforeach
            @endforeach

            @foreach($sewa as $sewaItem)
                @php
                    $queryR = (clone $baseQuery)->where(['transaction_type' => 'rental', 'ticket_id' => $sewaItem->id]);
                    $qtyR = $queryR->count();
                    $totalR = $queryR->sum(\DB::raw('(bayar - kembali) + ppn'));
                @endphp
                @if($qtyR > 0)
                @php $totalQtyNonTicket += $qtyR; $totalAmountNonTicket += $totalR; @endphp
                <tr>
                    <td>Rental {{ $sewaItem->name }}</td>
                    <td class="text-center">{{ $qtyR }}</td>
                    <td class="text-end">{{ number_format($totalR, 0, ',', '.') }}</td>
                </tr>
                @endif
            @endforeach
            <tr>
                <th>Subtotal Non-Ticket</th>
                <th class="text-center">{{ $totalQtyNonTicket }}</th>
                <th class="text-end">{{ number_format($totalAmountNonTicket, 0, ',', '.') }}</th>
            </tr>
        </tbody>
    </table>

    <table class="table" border="1">
        @php
            $totalSalesAmount = $totalAmountTicket + $totalAmountNonTicket;
            $totalDiscount = (clone $baseQuery)->sum('disc');
            $totalPPN = App\Models\DetailTransaction::whereIn('transaction_id', $idTrxAll)->sum('ppn') +
                        App\Models\Transaction::whereIn('id', $idTrxAll)->whereIn('transaction_type', ['renewal', 'registration', 'rental'])->sum('ppn');

            // Per Method Calculation
            $calculateMethod = function($m, $idAll) {
                $ids = App\Models\Transaction::whereIn('id', $idAll)->where('metode', $m)->pluck('id');
                $d = App\Models\DetailTransaction::whereIn('transaction_id', $ids)->sum(\DB::raw('total + ppn'));
                $n = App\Models\Transaction::whereIn('id', $ids)->whereIn('transaction_type', ['renewal', 'registration', 'rental'])->sum(\DB::raw('bayar - kembali'));
                return $d + $n;
            };

            $cashTotal = $calculateMethod('cash', $idTrxAll);
            $debitTotal = $calculateMethod('debit', $idTrxAll);
            $kreditTotal = $calculateMethod('kredit', $idTrxAll);
            $qrisTotal = $calculateMethod('qris', $idTrxAll);
            $transferTotal = $calculateMethod('transfer', $idTrxAll);

            $finalTotalAmount = $totalSalesAmount - $totalDiscount;
        @endphp
        <tbody>
            <tr>
                <td colspan="2">Total Penjualan</td>
                <td class="text-end">{{ number_format($totalSalesAmount, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="2">Total Diskon</td>
<td class="text-end">
    {{ $totalDiscount > 0 ? '-' : '' }}{{ number_format($totalDiscount, 0, ',', '.') }}
</td>            </tr>
            <tr>
                <td colspan="2">Total PPN</td>
                <td class="text-end">{{ number_format($totalPPN, 0, ',', '.') }}</td>
            </tr>
            <tr style="background-color: #eee;">
                <th colspan="2">TOTAL NET SALES</th>
                <th class="text-end">{{ number_format($finalTotalAmount, 0, ',', '.') }}</th>
            </tr>
            <tr>
                <th colspan="3" class="text-center">Metode Pembayaran</th>
            </tr>
            <tr>
                <td colspan="2">Cash</td>
                <td class="text-end">{{ number_format($cashTotal, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="2">Debit</td>
                <td class="text-end">{{ number_format($debitTotal, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="2">Kredit</td>
                <td class="text-end">{{ number_format($kreditTotal, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="2">QRIS</td>
                <td class="text-end">{{ number_format($qrisTotal, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="2">Transfer</td>
                <td class="text-end">{{ number_format($transferTotal, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
</div>

<script src="{{ asset('/js/jquery.min.js') }}"></script>
<script>
    $(document).ready(function() {
        window.print();
        // Optional: window.close(); setelah print jika ingin menutup tab otomatis
    })
</script>
</body>
</html>
