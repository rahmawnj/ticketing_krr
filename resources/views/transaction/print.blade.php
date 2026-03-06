<!DOCTYPE html>
<html>

<head>
    <title>Print QR</title>
    <style>
        * {
            box-sizing: border-box;
        }

        @page {
            size: 80mm auto;
            margin: 0;
        }

        body {
            margin: 0;
        }

        .ticket-card {
            margin: 0 auto;
            vertical-align: top;
            border-style: solid;
            border-width: 1px;
            background: #fff;
        }

        .ticket-card.ticket-portrait {
            max-width: 80mm !important;
        }

        @media print {
            .ticket-row {
                break-after: page;
                page-break-after: always;
                break-inside: avoid;
                page-break-inside: avoid;
            }
            .ticket-row:last-child {
                break-after: auto;
                page-break-after: auto;
            }
        }
    </style>
</head>

<body>
    @php
        $ticketPrintModeRaw = (string) ($ticketPrintOrientation ?? 'without_summary');
        if ($ticketPrintModeRaw === 'portrait') {
            $ticketPrintModeRaw = 'with_summary';
        } elseif ($ticketPrintModeRaw === 'portrait_with_first_qr') {
            $ticketPrintModeRaw = 'without_summary';
        }
        $ticketPrintMode = in_array($ticketPrintModeRaw, ['with_summary', 'without_summary'], true)
            ? $ticketPrintModeRaw
            : 'without_summary';
        $shouldPrintSummary = $ticketPrintMode === 'with_summary';
        $receiptDetails = $transaction->detail()->with('ticket')->get();
        $groupedSummaryItems = $receiptDetails
            ->groupBy(function ($item) {
                $ticketId = (int) ($item->ticket_id ?? 0);
                $ticketName = trim((string) ($item->ticket->name ?? '-'));
                return $ticketId . '|' . $ticketName;
            })
            ->map(function ($items) {
                $first = $items->first();
                $qty = (int) $items->sum(function ($item) {
                    return max((int) ($item->qty ?? 1), 1);
                });
                $subtotalLine = (float) $items->sum(function ($item) {
                    return ((float) ($item->total ?? 0)) + ((float) ($item->ppn ?? 0));
                });
                $unitPrice = $qty > 0 ? ($subtotalLine / $qty) : $subtotalLine;

                return [
                    'name' => (string) ($first->ticket->name ?? '-'),
                    'qty' => $qty,
                    'unit_price' => $unitPrice,
                    'subtotal' => $subtotalLine,
                ];
            })
            ->values();

        $jumlahJenis = (int) $groupedSummaryItems->count();
        $jumlahTicket = (int) $groupedSummaryItems->sum('qty');
        $subtotal = (float) $receiptDetails->sum('total') + (float) $receiptDetails->sum('ppn');
        $discount = ((float) $transaction->discount * $subtotal) / 100;
        $subtotalAfterDiscount = max(0, $subtotal - $discount);
        $paidGross = (float) $transaction->bayar + (float) $transaction->ppn;
        $nonCashMethods = ['debit', 'kredit', 'qris', 'transfer'];
        $metodeLower = strtolower((string) ($transaction->metode ?? ''));
        $displayPaid = in_array($metodeLower, $nonCashMethods, true) ? $subtotalAfterDiscount : $paidGross;
        if ($displayPaid <= 0) {
            $displayPaid = $subtotalAfterDiscount;
        }
        $transactionDateLabel = $transaction->created_at->format('d/m/Y');
        $transactionDateTimeLabel = $transaction->created_at->format('d/m/Y H:i:s');
        $printTickets = $tickets;
    @endphp

    @if($shouldPrintSummary)
    <div class="ticket-row" style="margin-top: 10px;">
        <div class="qr-code ticket-card ticket-portrait" style="max-width:80mm !important; margin: 0 auto 0 auto;">
            <div class="detail" style="font-size: 10pt; line-height: 18px; margin-top: 10px; margin-bottom: 10px;">
                <div style="text-align:center; margin-bottom: 10px;">
                    <div style="font-weight: 900; font-size: 12pt; text-transform: uppercase; margin-bottom: 6px;">{{ $name }}</div>
                    @if(!empty($logo))
                    <img src="{{ $logo }}" width="90" alt="The Logo" class="brand-image" style="opacity: .9; margin-bottom: 6px;">
                    @endif
                    <div style="margin: 6px 10px;"><hr style="border-style: dashed;"></div>
                    <div style="font-weight: 900; font-size: 10pt;">{{ $transaction->ticket_code }}</div>
                    <div style="font-size: 9pt;">{{ $transactionDateTimeLabel }}</div>
                </div>
                <div style="display: flex;font-weight: 900; justify-content: space-between; margin-left: 10px; margin-right: 10px;">
                    <span>Jumlah Jenis : </span>
                    <span>{{ $jumlahJenis }}</span>
                </div>
                <div style="display: flex;font-weight: 900; justify-content: space-between; margin-left: 10px; margin-right: 10px;">
                    <span>Jumlah Ticket : </span>
                    <span>{{ $jumlahTicket }}</span>
                </div>
                <div style="margin: 6px 10px;">
                    <div style="font-weight: 900;">Rincian Pembelian:</div>
                    @forelse($groupedSummaryItems as $item)
                    <div style="margin-top: 2px;">
                        <div style="font-weight: 700;">{{ $item['name'] }} x {{ $item['qty'] }}</div>
                        <div style="display: flex; justify-content: space-between; font-size: 9pt;">
                            <span>{{ $item['qty'] }} x Rp. {{ number_format($item['unit_price'], 0, ',', '.') }}</span>
                            <span>Rp. {{ number_format($item['subtotal'], 0, ',', '.') }}</span>
                        </div>
                    </div>
                    @empty
                    <div style="font-size: 9pt;">-</div>
                    @endforelse
                </div>

                <div style="display: flex;font-weight: 900; justify-content: space-between; margin-left: 10px; margin-right: 10px;">
                    <span>Total Harga : </span>
                    <span>Rp. {{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>
                {{-- <div style="display: flex;font-weight: 900; justify-content: space-between; margin-left: 10px; margin-right: 10px;">
                    <span>PBJT {{ $ppn . '%' }} : </span>
                <span>Rp. {{ number_format($transaction->ppn, 0, ',', '.') }}</span>
            </div> --}}
            <div style="display: flex;font-weight: 900; justify-content: space-between; margin-left: 10px; margin-right: 10px;">
                <span>Bayar : </span>
                <span>Rp. {{ number_format($displayPaid, 0, ',', '.') }}</span>
            </div>
            <div style="display: flex;font-weight: 900; justify-content: space-between; margin-left: 10px; margin-right: 10px;">
                <span>Metode : </span>
                <span>{{ strtoupper($transaction->metode ?? '-') }}</span>
            </div>
            <div style="display: flex;font-weight: 900; justify-content: space-between; margin-left: 10px; margin-right: 10px;">
                <span>Kasir : </span>
                <span>{{ $transaction->user->name ?? '-' }}</span>
            </div>
            <hr style="border-style: dashed;">
            <p style="font-size:9pt;text-align: center;margin-bottom:8px; text-transform: uppercase;">{!! nl2br(e($ucapan)) !!}</p>
            <p style="font-size:9pt;text-align: center;margin-bottom:10px; text-transform: uppercase;">{!! nl2br(e($deskripsi)) !!}</p>
        </div>
    </div>
    </div>
    @endif

    @foreach($printTickets as $ticketIndex => $detail)
    <div class="ticket-row" style="margin-top: 10px;">
        <div class="qr-code ticket-card ticket-portrait" style="margin: 0 auto 0 auto;">
            <div class="detail" style="font-size: 10pt; line-height: 18px;">
                <span style="display: block; text-align: center; font-weight: 900;">{{ $detail["name"] }}</span>
                @if($print == 0)
                <span style="display: block; text-align: center;">Rp. {{ $detail["harga"] }}</span>
                @endif
                <span style="display: block; text-align: center; font-size: 8pt;">Tanggal: {{ $transactionDateLabel }}</span>
                <span style="display: block; text-align: center;"></span>
            </div>
            <hr style="border-style: dashed;">
            <p style="text-align: center; margin-top: 15px; margin-bottom: 15px">
                {!! QrCode::size(110)->generate($detail["ticket_code"]) !!}
                <br>
                <span>{{ $detail["ticket_code"] }}</span>
            </p>

            <hr style="border-style: dashed;">
            <p style="font-size:9pt;text-align: center;margin-bottom:8px; text-transform: uppercase;">{!! nl2br(e($ucapan)) !!}</p>
            <p style="font-size:9pt;text-align: center;margin-bottom:10px; text-transform: uppercase;">{!! nl2br(e($deskripsi)) !!}</p>
        </div>
    </div>
    @endforeach

    <script src="{{ asset('/js/jquery.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            let hasRedirected = false;
            const backToTransaction = function() {
                if (hasRedirected) return;
                hasRedirected = true;
                document.location.href = "{{ route('transactions.create') }}";
            };

            window.onafterprint = backToTransaction;
            window.print();

            setTimeout(backToTransaction, 10000);
        })
    </script>
</body>

</html>
