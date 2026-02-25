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

        .ticket-card.ticket-landscape {
            width: 88mm;
            min-height: 64mm;
            padding: 2.5mm;
            display: flex;
            flex-direction: column;
        }

        .ticket-landscape .ticket-body {
            display: grid;
            grid-template-columns: 1fr 22mm;
            gap: 2mm;
            align-items: start;
            flex: 1 1 auto;
        }

        .ticket-landscape .ticket-head-global {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1.5mm;
            margin-bottom: 10mm;
            width: 100%;
        }

        .ticket-landscape .ticket-head-global .logo-mini {
            width: 11mm;
            height: 11mm;
            object-fit: contain;
        }

        .ticket-landscape .ticket-head-global .app-name {
            font-size: 11pt;
            font-weight: 900;
            text-transform: uppercase;
            text-align: right;
            line-height: 1.1;
            max-width: 58mm;
            overflow-wrap: anywhere;
            word-break: break-word;
        }

        .ticket-landscape .ticket-qr-wrap {
            text-align: center;
            padding-right: 1.5mm;
            padding-bottom: 1.5mm;
        }

        .ticket-landscape .ticket-qr-wrap p {
            margin: 0;
            transform: translate(-1.5mm, -1.5mm);
        }

        .ticket-landscape .ticket-footer {
            margin-top: auto;
            border-top: 1px dashed #999;
            padding-top: 1.5mm;
        }

        .ticket-landscape .ticket-footer p {
            font-size: 8pt !important;
            line-height: 1.2;
            margin-left: 1mm !important;
            margin-right: 1mm !important;
            word-break: break-word;
            overflow-wrap: anywhere;
        }

        .ticket-row.ticket-row-landscape {
            margin-top: 14mm !important;
            width: 80mm;
            height: 86mm;
            margin-left: auto;
            margin-right: auto;
            position: relative;
            overflow: visible;
        }

        .ticket-rotate-frame {
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%) rotate(90deg);
            transform-origin: center center;
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
        $ticketOrientation = ($ticketPrintOrientation ?? 'portrait') === 'landscape' ? 'landscape' : 'portrait';
        $receiptDetails = $transaction->detail()->with('ticket')->get();
        $jumlahJenis = $receiptDetails->count();
        $jumlahTicket = (int) $receiptDetails->sum('qty');
        $subtotal = (float) $receiptDetails->sum('total') + (float) $receiptDetails->sum('ppn');
        $discount = ((float) $transaction->discount * $subtotal) / 100;
        $transactionDateLabel = $transaction->created_at->format('d/m/Y');
        $transactionDateTimeLabel = $transaction->created_at->format('d/m/Y H:i:s');
    @endphp

    <div class="ticket-row" style="margin-top: 10px;">
        <div class="qr-code ticket-card ticket-portrait" style="max-width:80mm !important; margin: 0 auto 0 auto;">
            <div class="detail" style="font-size: 10pt; line-height: 18px; margin-top: 10px; margin-bottom: 10px;">
                <div style="text-align:center; margin-bottom: 10px;">
                    <div style="font-weight: 900; font-size: 12pt; text-transform: uppercase; margin-bottom: 6px;">{{ $name }}</div>
                    @if($use == 1)
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
                    @forelse($receiptDetails as $item)
                    @php
                    $lineQty = max((int) $item->qty, 1);
                    $lineSubtotal = (float) $item->total + (float) $item->ppn;
                    $lineUnitPrice = $lineSubtotal / $lineQty;
                    @endphp
                    <div style="margin-top: 2px;">
                        <div style="font-weight: 700;">{{ $item->ticket->name ?? '-' }}</div>
                        <div style="display: flex; justify-content: space-between; font-size: 9pt;">
                            <span>{{ $lineQty }} x Rp. {{ number_format($lineUnitPrice, 0, ',', '.') }}</span>
                            <span>Rp. {{ number_format($lineSubtotal, 0, ',', '.') }}</span>
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
                <div style="display: flex;font-weight: 900; justify-content: space-between; margin-left: 10px; margin-right: 10px;">
                    <span>Discount : </span>
                    <span>Rp. {{ number_format($discount, 0, ',', '.') }}</span>
                </div>
                {{-- <div style="display: flex;font-weight: 900; justify-content: space-between; margin-left: 10px; margin-right: 10px;">
                    <span>PBJT {{ $ppn . '%' }} : </span>
                <span>Rp. {{ number_format($transaction->ppn, 0, ',', '.') }}</span>
            </div> --}}
            <div style="display: flex;font-weight: 900; justify-content: space-between; margin-left: 10px; margin-right: 10px;">
                <span>Bayar : </span>
                <span>Rp. {{ number_format($transaction->bayar + $transaction->ppn, 0, ',', '.') }}</span>
            </div>
            <div style="display: flex;font-weight: 900; justify-content: space-between; margin-left: 10px; margin-right: 10px;">
                <span>Metode : </span>
                <span>{{ strtoupper($transaction->metode ?? '-') }}</span>
            </div>
            <div style="display: flex;font-weight: 900; justify-content: space-between; margin-left: 10px; margin-right: 10px;">
                <span>Kasir : </span>
                <span>{{ $transaction->user->name ?? '-' }}</span>
            </div>
            <div style="display: flex;font-weight: 900; justify-content: space-between; margin-left: 10px; margin-right: 10px;">
                <span>Kembali : </span>
                <span>Rp. {{ number_format($transaction->kembali, 0, ',', '.') }}</span>
            </div>

            <hr style="border-style: dashed;">
            <p style="font-size:9pt;text-align: center;margin-bottom:8px; text-transform: uppercase;">{!! nl2br(e($ucapan)) !!}</p>
            <p style="font-size:9pt;text-align: center;margin-bottom:10px; text-transform: uppercase;">{!! nl2br(e($deskripsi)) !!}</p>
        </div>
    </div>
    </div>

    @foreach($tickets as $detail)
    <div class="ticket-row {{ $ticketOrientation === 'landscape' ? 'ticket-row-landscape' : '' }}" style="margin-top: 10px;">
        @if($ticketOrientation === 'landscape')
            <div class="ticket-rotate-frame">
                <div class="qr-code ticket-card ticket-landscape" style="margin: 0 auto 0 auto;">
                <div class="ticket-head-global">
                    @if(($use ?? 0) == 1)
                        <img src="{{ $logo }}" alt="Logo" class="logo-mini">
                    @endif
                    <div class="app-name">{{ $name }}</div>
                </div>
                <div class="ticket-body">
                    <div class="detail" style="font-size: 10pt; line-height: 18px;">
                        <span style="display: block; text-align: left; font-weight: 900;">{{ $detail["name"] }}</span>
                        @if($print == 0)
                        <span style="display: block; text-align: left;">Rp. {{ $detail["harga"] }}</span>
                        @endif
                        @if(($printMode ?? 'per_qty') === 'per_ticket')
                        <span style="display: block; text-align: left; font-size: 9pt;">
                            Jumlah Scan: {{ $detail["qty"] }}x
                        </span>
                        @endif
                        <span style="display: block; text-align: left; font-size: 8pt;">
                            Tanggal: {{ $transactionDateLabel }}
                        </span>
                        <span style="display: block; text-align: left; font-size: 8pt; margin-top: 1mm; word-break: break-all;">
                            {{ $detail["ticket_code"] }}
                        </span>
                    </div>
                    <div class="ticket-qr-wrap">
                        <p>{!! QrCode::size(96)->generate($detail["ticket_code"]) !!}</p>
                    </div>
                </div>
                <div class="ticket-footer">
                    <p style="font-size:9pt;text-align: center;margin:0 0 6px 0; text-transform: uppercase;">{!! nl2br(e($ucapan)) !!}</p>
                    <p style="font-size:9pt;text-align: center;margin:0; text-transform: uppercase;">{!! nl2br(e($deskripsi)) !!}</p>
                </div>
                </div>
            </div>
        @else
            <div class="qr-code ticket-card ticket-portrait" style="margin: 0 auto 0 auto;">
                <div class="detail" style="font-size: 10pt; line-height: 18px;">
                    <span style="display: block; text-align: center; font-weight: 900;">{{ $name }}</span>
                    <span style="display: block; text-align: center; font-weight: 900;">{{ $detail["name"] }}</span>
                    @if($print == 0)
                    <span style="display: block; text-align: center;">Rp. {{ $detail["harga"] }}</span>
                    @endif
                    @if(($printMode ?? 'per_qty') === 'per_ticket')
                    <span style="display: block; text-align: center; font-size: 9pt;">
                        Jumlah Scan: {{ $detail["qty"] }}x
                    </span>
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
        @endif
    </div>
    @endforeach

    <script src="{{ asset('/js/jquery.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            window.print();
            setTimeout(function() {
                document.location.href = "{{ route('transactions.create') }}";
            }, 3000)
        })
    </script>
</body>

</html>
