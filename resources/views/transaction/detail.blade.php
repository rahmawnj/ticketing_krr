@php
date_default_timezone_set('Asia/Jakarta')
@endphp
<!DOCTYPE html>
<html>

<head>
    <title>Print QR</title>
    <style>
        @media print {
            .ticket-row {
                page-break-after: always;
            }
        }
    </style>
</head>

<body>
    <div class="ticket-row" style="margin-top: 10px;">
        <div class="qr-code" style="max-width:80mm !important;  margin: 0 auto 0 auto; vertical-align: top; border-style: solid;border-width: 1px;">
            <div class="detail" style="font-size: 10pt; line-height: 18px;">
                <span style="display: block; text-align: center; font-weight: 900;">{{ $detail->ticket->name }}</span>
                <span style="display: block; text-align: center;">Rp. {{ number_format($detail->ticket->harga, 0, ',', '.') }}</span>
                <span style="display: block; text-align: center;"></span>
            </div>
            <!-- <p style="font-size:8pt;text-align: center;margin-top:5px">RIO WATERPARK " Tiket berlaku satu kali masuk "</p> -->
            <hr style="border-style: dashed;">
            <p style="text-align: center; margin-top: 15px; margin-bottom: 15px">
                <img src="data:image/png;base64,{{ base64_encode(QrCode::format('png')->size(100)->generate($detail->ticket_code)) }}" alt="QR Code">
                <br><br>
                <span>{{ $detail->ticket_code }}</span>
            </p>

            <hr style="border-style: dashed;">
            <p style="font-size:9pt;text-align: center;margin-bottom:10px; text-transform: uppercase;">*QRCODE hanya untuk buka gate*</p>
        </div>
    </div>
</body>

</html>