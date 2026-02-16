<!DOCTYPE html>
<html>

<head>
    <title>Print QR</title>
    <style>
        @media print {
            .ticket-row {
                page-break-after: always;
            }
            .ticket-row:last-child {
                page-break-after: auto;
            }
        }
    </style>
</head>

<body>
    <div class="ticket-row" style="margin-top: 10px;">
        <div class="qr-code" style="max-width:80mm !important;  margin: 0 auto 0 auto; vertical-align: top; border-style: solid;border-width: 1px;">
            <div class="detail" style="font-size: 10pt; line-height: 18px; margin-top: 10px; margin-bottom: 10px;">
                <div style="text-align:center; margin-bottom: 10px;">
                    <div style="font-weight: 900; font-size: 12pt; text-transform: uppercase; margin-bottom: 6px;">{{ $name }}</div>
                    @if($use == 1)
                    <img src="{{ $logo }}" width="90" alt="The Logo" class="brand-image" style="opacity: .9; margin-bottom: 6px;">
                    @endif
                    <div style="margin: 6px 10px;"><hr style="border-style: dashed;"></div>
                    <div style="font-weight: 900; font-size: 10pt;">{{ $transaction->ticket_code }}</div>
                    <div style="font-size: 9pt;">{{ $transaction->created_at->format('d/m/Y H:i:s') }}</div>
                </div>
                @if(($printMode ?? 'per_qty') === 'per_ticket')
                <div style="display: flex;font-weight: 900; justify-content: space-between; margin-left: 10px; margin-right: 10px;">
                    <span>Jumlah Jenis : </span>
                    <span>{{ $transaction->detail()->count() }}</span>
                </div>
                @endif
                <div style="display: flex;font-weight: 900; justify-content: space-between; margin-left: 10px; margin-right: 10px;">
                    <span>Jumlah Ticket : </span>
                    <span>{{ $transaction->detail()->sum('qty') }}</span>
                </div>

                <div style="display: flex;font-weight: 900; justify-content: space-between; margin-left: 10px; margin-right: 10px;">
                    <span>Total Harga : </span>
                    <span>Rp. {{ number_format($transaction->detail()->sum('total') + $transaction->detail()->sum('ppn'), 0, ',', '.') }}</span>
                </div>
                <div style="display: flex;font-weight: 900; justify-content: space-between; margin-left: 10px; margin-right: 10px;">
                    <span>Discount : </span>
                    @php
                    $discount = $transaction->discount * ($transaction->detail()->sum('total') + $transaction->detail()->sum('ppn')) / 100;
                    @endphp
                    <span>Rp. {{ number_format($discount, 0, ',', '.') }}</span>
                </div>
                {{-- <div style="display: flex;font-weight: 900; justify-content: space-between; margin-left: 10px; margin-right: 10px;">
                    <span>PPN {{ $ppn . '%' }} : </span>
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
    <div class="ticket-row" style="margin-top: 10px;">
        <div class="qr-code" style="max-width:80mm !important;  margin: 0 auto 0 auto; vertical-align: top; border-style: solid;border-width: 1px;">
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
                <span style="display: block; text-align: center;"></span>
            </div>
            <hr style="border-style: dashed;">
            <p style="text-align: center; margin-top: 15px; margin-bottom: 15px">
                {!! QrCode::size(100)->generate($detail["ticket_code"]) !!}
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
            window.print();
            setTimeout(function() {
                document.location.href = "{{ route('transactions.create') }}";
            }, 3000)
        })
    </script>
</body>

</html>
