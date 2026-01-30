@php
    $baseQuery = App\Models\Transaction::where('is_active', 1)->whereBetween('created_at', [$from, $to]);
    if ($kasir != 'all' && $kasir) {
        $baseQuery->where('user_id', $kasir);
    }
    $idTrxAll = $baseQuery->pluck('id');

    // Inisialisasi variabel total
    $totalQtyTicket = 0;
    $totalAmountTicketWithPPN = 0;
    $totalAmountTicketTanpaPPN = 0;
@endphp

<table>
    <thead>
        <tr>
            <th colspan="5" style="font-weight: bold;">Report Transaction Ticket Tanggal {{ Carbon\Carbon::parse($from)->format('d/m/Y') }} - {{ Carbon\Carbon::parse($to)->subDay(1)->format('d/m/Y') }}</th>
        </tr>
        <tr>
            <th style="background-color: #f2f2f2;">Jenis Ticket</th>
            <th style="background-color: #f2f2f2;">Jumlah</th>
            <th style="background-color: #f2f2f2;">Harga Ticket (+PPN)</th>
            <th style="background-color: #f2f2f2;">PPN Status</th>
            <th style="background-color: #f2f2f2;">Total Harga Ticket</th>
        </tr>
    </thead>
    <tbody>
        @foreach($tickets as $ticket)
            @php
                $qty = App\Models\DetailTransaction::whereIn('transaction_id', $idTrxAll)->where('ticket_id', $ticket->id)->sum('qty');
                // Hitung total dengan PPN
                $totalWithPPN = App\Models\DetailTransaction::whereIn('transaction_id', $idTrxAll)->where('ticket_id', $ticket->id)->sum(\DB::raw('total + ppn'));
                // Hitung total murni tanpa PPN
                $totalTanpaPPN = App\Models\DetailTransaction::whereIn('transaction_id', $idTrxAll)->where('ticket_id', $ticket->id)->sum('total');
            @endphp
            @if($qty > 0)
                @php
                    $totalQtyTicket += $qty;
                    $totalAmountTicketWithPPN += $totalWithPPN;
                    $totalAmountTicketTanpaPPN += $totalTanpaPPN;
                @endphp
                <tr>
                    <td>{{ $ticket->name }}</td>
                    <td>{{ $qty }}</td>
                    <td>{{ $ticket->harga + $ticket->ppn }}</td>
                    <td>{{ $ticket->use_ppn == 1 ? 'PPN' : '-' }}</td>
                    <td>{{ $totalWithPPN }}</td>
                </tr>
            @endif
        @endforeach
        <tr>
            <th style="font-weight: bold;">Total Penjualan Ticket :</th>
            <th style="font-weight: bold;">{{ $totalQtyTicket }}</th>
            <th colspan="2"></th>
            <th style="font-weight: bold;">{{ $totalAmountTicketWithPPN }}</th>
        </tr>

        <tr><td colspan="5"></td></tr>

        <tr>
            <th colspan="5" style="font-weight: bold;">Report Transaction Non-Ticket</th>
        </tr>
        <tr>
            <th style="background-color: #f2f2f2;">Jenis Transaksi</th>
            <th style="background-color: #f2f2f2;">Jumlah</th>
            <th style="background-color: #f2f2f2;">Harga Satuan</th>
            <th style="background-color: #f2f2f2;">PPN Status</th>
            <th style="background-color: #f2f2f2;">Total Harga</th>
        </tr>
        @php
            $totalQtyNonTicket = 0;
            $totalAmountNonTicketWithPPN = 0;
            $totalAmountNonTicketTanpaPPN = 0;
        @endphp

        @foreach($memberships as $membership)
            @foreach(['renewal', 'registration'] as $type)
                @php
                    $qNon = (clone $baseQuery)->where(['transaction_type' => $type, 'ticket_id' => $membership->id]);
                    $qtyM = $qNon->count();
                    $totalM_WithPPN = $qNon->sum(\DB::raw('(bayar - kembali) + ppn'));
                    $totalM_TanpaPPN = $qNon->sum(\DB::raw('bayar - kembali'));
                @endphp
                @if($qtyM > 0)
                    @php
                        $totalQtyNonTicket += $qtyM;
                        $totalAmountNonTicketWithPPN += $totalM_WithPPN;
                        $totalAmountNonTicketTanpaPPN += $totalM_TanpaPPN;
                    @endphp
                    <tr>
                        <td>{{ ucfirst($type) }} ({{ $membership->name }})</td>
                        <td>{{ $qtyM }}</td>
                        <td>{{ $membership->price }}</td>
                        <td>{{ $membership->use_ppn == 1 ? 'PPN' : '-' }}</td>
                        <td>{{ $totalM_WithPPN }}</td>
                    </tr>
                @endif
            @endforeach
        @endforeach

        @foreach($sewa as $sewaItem)
            @php
                $qSewa = (clone $baseQuery)->where(['transaction_type' => 'rental', 'ticket_id' => $sewaItem->id]);
                $qtyS = $qSewa->count();
                $totalS_WithPPN = $qSewa->sum(\DB::raw('(bayar - kembali) + ppn'));
                $totalS_TanpaPPN = $qSewa->sum(\DB::raw('bayar - kembali'));
            @endphp
            @if($qtyS > 0)
                @php
                    $totalQtyNonTicket += $qtyS;
                    $totalAmountNonTicketWithPPN += $totalS_WithPPN;
                    $totalAmountNonTicketTanpaPPN += $totalS_TanpaPPN;
                @endphp
                <tr>
                    <td>Rental ({{ $sewaItem->name }})</td>
                    <td>{{ $qtyS }}</td>
                    <td>{{ $sewaItem->harga }}</td>
                    <td>{{ $sewaItem->use_ppn == 1 ? 'PPN' : '-' }}</td>
                    <td>{{ $totalS_WithPPN }}</td>
                </tr>
            @endif
        @endforeach
        <tr>
            <th style="font-weight: bold;">Total Penjualan Non-Ticket :</th>
            <th style="font-weight: bold;">{{ $totalQtyNonTicket }}</th>
            <th colspan="2"></th>
            <th style="font-weight: bold;">{{ $totalAmountNonTicketWithPPN }}</th>
        </tr>

        <tr><td colspan="5"></td></tr>

        @php
            $totalSalesQty = $totalQtyTicket + $totalQtyNonTicket;
            $totalSalesWithPPN = $totalAmountTicketWithPPN + $totalAmountNonTicketWithPPN;
            $totalSalesTanpaPPN = $totalAmountTicketTanpaPPN + $totalAmountNonTicketTanpaPPN;

            $totalDiscount = $baseQuery->sum('disc');
            $totalPPN = App\Models\DetailTransaction::whereIn('transaction_id', $idTrxAll)->sum('ppn') +
                        App\Models\Transaction::whereIn('id', $idTrxAll)->whereIn('transaction_type', ['renewal', 'registration', 'rental'])->sum('ppn');

            $calculateMethod = function($m, $ids) {
                $trxIds = App\Models\Transaction::whereIn('id', $ids)->where('metode', $m)->pluck('id');
                $detail = App\Models\DetailTransaction::whereIn('transaction_id', $trxIds)->sum(\DB::raw('total + ppn'));
                $nonDetail = App\Models\Transaction::whereIn('id', $trxIds)->whereIn('transaction_type', ['renewal', 'registration', 'rental'])->sum(\DB::raw('(bayar - kembali) + ppn'));
                return $detail + $nonDetail;
            };
        @endphp

        <tr>
            <th colspan="2" style="font-weight: bold;">Total Penjualan All :</th>
            <th>{{ $totalSalesQty }}</th>
            <th></th>
            <th style="font-weight: bold;">{{ $totalSalesWithPPN }}</th>
        </tr>
        <tr>
            <th colspan="4">Total Discount :</th>
            <th style="font-weight: bold;">{{ $totalDiscount }}</th>
        </tr>
        <tr>
            <th colspan="4">Total PPN :</th>
            <th style="font-weight: bold;">{{ $totalPPN }}</th>
        </tr>

        <tr><td colspan="5"></td></tr>
        <tr>
            <th colspan="2" style="font-weight: bold;">Metode Pembayaran</th>
            <th colspan="3" style="font-weight: bold;">Total</th>
        </tr>
        @foreach(['cash', 'debit', 'kredit', 'qris', 'transfer'] as $method)
        <tr>
            <td colspan="2">{{ ucfirst($method) }}</td>
            <td colspan="3">{{ $calculateMethod($method, $idTrxAll) }}</td>
        </tr>
        @endforeach

        <tr>
            <th colspan="4" style="background-color: #ffff00; font-weight: bold;">TOTAL AKHIR (Sales DPP - Discount)</th>
            <th style="background-color: #ffff00; font-weight: bold;">{{ $totalSalesTanpaPPN - $totalDiscount }}</th>
        </tr>
        <tr>
            <th colspan="4" style="background-color: #ffff00; font-weight: bold;">TOTAL AKHIR (Sales DPP - Discount) + PPN</th>
            <th style="background-color: #ffff00; font-weight: bold;">{{ ($totalSalesTanpaPPN - $totalDiscount) + $totalPPN }}</th>
        </tr>
    </tbody>
</table>
