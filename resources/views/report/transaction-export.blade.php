@php
    $queryTrxAll = App\Models\Transaction::where(['is_active' => 1])->whereBetween('created_at', [$from, $to]);
    if ($kasir != 'all' && $kasir) {
        $queryTrxAll->where('user_id', $kasir);
    }
    $idTrxAll = $queryTrxAll->pluck('id');
@endphp

<table>
    <thead>
        <tr>
            <th colspan="5" style="font-weight: bold;">Report Transaction Ticket Tanggal {{ Carbon\Carbon::parse($from)->format('d/m/Y') }} - {{ Carbon\Carbon::parse($to)->subDay(1)->format('d/m/Y') }}</th>
        </tr>
        <tr>
            <th style="background-color: #f2f2f2;">Jenis Ticket</th>
            <th style="background-color: #f2f2f2;">Jumlah</th>
            <th style="background-color: #f2f2f2;">Harga Ticket</th>
            <th style="background-color: #f2f2f2;">PBJT</th>
            <th style="background-color: #f2f2f2;">Total Harga Ticket</th>
        </tr>
    </thead>
    <tbody>
        @php
            $queryTrxTicket = App\Models\Transaction::where(['is_active' => 1, 'transaction_type' => 'ticket'])->whereBetween('created_at', [$from, $to]);
            if ($kasir != 'all' && $kasir) {
                $queryTrxTicket->where('user_id', $kasir);
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
                    <td>{{ $qty }}</td>
                    <td>{{ number_format($ticket->harga + $ticket->ppn, 0, ',', '.') }}</td>
                    <td>{{ $ticket->use_ppn == 1 ? 'YA' : '-' }}</td>
                    <td>{{ number_format($totalPerTicket, 0, ',', '.') }}</td>
                </tr>
            @endif
        @endforeach

        <tr>
            <th style="font-weight: bold;">Total Penjualan Ticket :</th>
            <th style="font-weight: bold;">{{ $totalQtyTicket }}</th>
            <th colspan="2"></th>
            <th style="font-weight: bold;">{{ number_format($totalAmountTicket, 0, ',', '.') }}</th>
        </tr>

        <tr><td colspan="5"></td></tr>

        <tr>
            <th colspan="5" style="font-weight: bold;">Report Transaction Non-Ticket Tanggal {{ Carbon\Carbon::parse($from)->format('d/m/Y') }} - {{ Carbon\Carbon::parse($to)->subDay(1)->format('d/m/Y') }}</th>
        </tr>
        <tr>
            <th style="background-color: #f2f2f2;">Jenis Transaksi</th>
            <th style="background-color: #f2f2f2;">Jumlah</th>
            <th style="background-color: #f2f2f2;">Harga</th>
            <th style="background-color: #f2f2f2;">PBJT</th>
            <th style="background-color: #f2f2f2;">Total Harga</th>
        </tr>

        @php
            $totalQtyNonTicket = 0;
            $totalAmountNonTicket = 0;
            $orderedMemberships = $memberships->sortBy('name');
            $membershipTrxTypes = ['registration', 'renewal'];
        @endphp

        @foreach($membershipTrxTypes as $type)
            @foreach($orderedMemberships as $membership)
                @php
                    $queryTrxMembership = App\Models\Transaction::where(['is_active' => 1, 'transaction_type' => $type, 'ticket_id' => $membership->id])->whereBetween('created_at', [$from, $to]);
                    if ($kasir != 'all' && $kasir) {
                        $queryTrxMembership->where('user_id', $kasir);
                    }
                    $qtyMembership = $queryTrxMembership->count();
                    $totalPerMembership = $queryTrxMembership->sum(\DB::raw('(bayar - kembali) + ppn'));
                    $totalQtyNonTicket += $qtyMembership;
                    $totalAmountNonTicket += $queryTrxMembership->sum(\DB::raw('bayar - kembali'));
                @endphp

                @if($qtyMembership > 0)
                    <tr>
                        <td>{{ ucfirst($type) }} ({{ $membership->name }})</td>
                        <td>{{ $qtyMembership }}</td>
                        <td>{{ number_format($membership->price, 0, ',', '.') }}</td>
                        <td>{{ $membership->use_ppn == 1 ? 'YA' : '-' }}</td>
                        <td>{{ number_format($totalPerMembership, 0, ',', '.') }}</td>
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
                if ($kasir != 'all' && $kasir) {
                    $queryTrxRental->where('user_id', $kasir);
                }

                $qtyRental = $queryTrxRental->count();
                $totalPerRental = $queryTrxRental->sum(\DB::raw('bayar - kembali'));

                $totalQtyNonTicket += $qtyRental;
                $totalAmountNonTicket += $totalPerRental;
            @endphp

            @if($qtyRental > 0)
                <tr>
                    <td>Rental ({{ $sewaItem->name }})</td>
                    <td>{{ $qtyRental }}</td>
                    <td>{{ number_format($sewaItem->harga, 0, ',', '.') }}</td>
                    <td>{{ $sewaItem->use_ppn == 1 ? 'YA' : '-' }}</td>
                    <td>{{ number_format($totalPerRental, 0, ',', '.') }}</td>
                </tr>
            @endif
        @endforeach

        <tr>
            <th style="font-weight: bold;">Total Penjualan Non-Ticket :</th>
            <th style="font-weight: bold;">{{ $totalQtyNonTicket }}</th>
            <th colspan="2"></th>
            <th style="font-weight: bold;">{{ number_format($totalAmountNonTicket, 0, ',', '.') }}</th>
        </tr>

        <tr><td colspan="5"></td></tr>

        @php
            $totalSalesQty = App\Models\DetailTransaction::whereIn('transaction_id', $idTrxTicket)->sum('qty') + $totalQtyNonTicket;

            $totalDiscount = $queryTrxAll->sum('disc');
            $totalPPN = App\Models\DetailTransaction::whereIn('transaction_id', $idTrxAll)->sum('ppn') +
                        App\Models\Transaction::whereIn('id', $idTrxAll)->whereIn('transaction_type', ['renewal', 'registration', 'rental'])->sum('ppn');

            $cashid = $queryTrxAll->clone()->where('metode', 'cash')->pluck('id');
            $debitid = $queryTrxAll->clone()->where('metode', 'debit')->pluck('id');
            $kreditid = $queryTrxAll->clone()->whereIn('metode', ['kredit', 'credit', 'credit card'])->pluck('id');
            $qrisid = $queryTrxAll->clone()->whereIn('metode', ['qris', 'qr'])->pluck('id');
            $transferid = $queryTrxAll->clone()->where('metode', 'transfer')->pluck('id');
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
                    ->sum(\DB::raw('bayar - kembali'));
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

            $totalSalesAmount = $grandTotalIncome;
            $totalAmountSetelahDiskon = $grandTotalIncome - $totalDiscount;
            $totalAmountAkhirPlusPPN = $totalAmountSetelahDiskon + $totalPPN;
        @endphp

        <tr>
            <th>Total Penjualan :</th>
            <th>{{ $totalSalesQty }}</th>
            <th></th>
            <th></th>
            <th>{{ number_format($totalSalesAmount, 0, ',', '.') }}</th>
        </tr>
        <tr>
            <th colspan="4">Total Discount :</th>
            <th>{{ number_format($totalDiscount, 0, ',', '.') }}</th>
        </tr>
        <tr>
            <th colspan="4">Total PBJT :</th>
            <th>{{ number_format($totalPPN, 0, ',', '.') }}</th>
        </tr>

        <tr>
            <th></th>
            <th>TOTAL TUNAI</th>
            <th>Rp {{ number_format($cashTotal, 2, ',', '.') }}</th>
            <th>QTY TUNAI</th>
            <th>{{ $cashQty }} Orang</th>
        </tr>
        <tr>
            <th></th>
            <th>TOTAL DEBIT</th>
            <th>Rp {{ number_format($debitTotal, 2, ',', '.') }}</th>
            <th>QTY DEBIT</th>
            <th>{{ $debitQty }} Orang</th>
        </tr>
        <tr>
            <th></th>
            <th>TOTAL QRIS</th>
            <th>Rp {{ number_format($qrisTotal, 2, ',', '.') }}</th>
            <th>QTY QRIS</th>
            <th>{{ $qrisQty }} Orang</th>
        </tr>
        <tr>
            <th></th>
            <th>TOTAL CREDIT CARD</th>
            <th>Rp {{ number_format($kreditTotal, 2, ',', '.') }}</th>
            <th>QTY CREDIT CARD</th>
            <th>{{ $kreditQty }} Orang</th>
        </tr>
        <tr>
            <th></th>
            <th>TOTAL TRANSFER</th>
            <th>Rp {{ number_format($transferTotal, 2, ',', '.') }}</th>
            <th>QTY TRANSFER</th>
            <th>{{ $transferQty }} Orang</th>
        </tr>
        <tr>
            <th></th>
            <th>TOTAL PEMBAYARAN LAINNYA</th>
            <th>Rp {{ number_format($pembayaranLainnyaTotal, 2, ',', '.') }}</th>
            <th>QTY PEMBAYARAN LAINNYA</th>
            <th>{{ $pembayaranLainnyaQty }} Orang</th>
        </tr>
        <tr>
            <th></th>
            <th>GRANDTOTAL INCOME</th>
            <th>Rp {{ number_format($grandTotalIncome, 2, ',', '.') }}</th>
            <th>GRANDTOTAL QTY ALL</th>
            <th>{{ $grandTotalQtyAll }} Orang</th>
        </tr>
        <tr>
            <th colspan="4">Total Amount Akhir (Total Penjualan - Diskon) :</th>
            <th>{{ number_format($totalAmountSetelahDiskon, 0, ',', '.') }}</th>
        </tr>
        <tr>
            <th colspan="4">Total Amount Akhir (Total Penjualan - Diskon) + PBJT:</th>
            <th>{{ number_format($totalAmountAkhirPlusPPN, 0, ',', '.') }}</th>
        </tr>
    </tbody>
</table>
