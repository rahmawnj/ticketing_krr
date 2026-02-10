<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $member->nama }} | Print QR Member</title>

    <style>
        @page { size: 85.6mm 54mm; margin: 0; }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: Arial, sans-serif; color: #0f2a3c; }
        .card {
            width: 85.6mm;
            height: 54mm;
            border: 1px solid #0f2a3c;
            padding: 6mm;
            position: relative;
        }
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 4mm;
        }
        .brand {
            font-weight: 700;
            font-size: 12px;
            text-transform: uppercase;
        }
        .member-id {
            font-size: 10px;
            color: #2a4b5f;
        }
        .content {
            display: grid;
            grid-template-columns: 1fr 28mm;
            gap: 4mm;
            align-items: center;
        }
        .label { font-size: 9px; color: #4b5b66; }
        .value { font-size: 11px; font-weight: 600; margin-bottom: 2mm; }
        .qr {
            width: 26mm;
            height: 26mm;
            border: 1px solid #c9d6df;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .footer {
            position: absolute;
            left: 6mm;
            right: 6mm;
            bottom: 4mm;
            font-size: 8px;
            color: #4b5b66;
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>

<body>
    <div class="card">
        <div class="header">
            <div class="brand">Member Card</div>
            <div class="member-id">No. Member: {{ $member->qr_code }}</div>
        </div>

        <div class="content">
            <div>
                <div class="label">Nama Member</div>
                <div class="value">{{ $member->nama }}</div>

                <div class="label">Masa Aktif</div>
                <div class="value">
                    {{ \Carbon\Carbon::parse($member->tgl_register)->format('d/m/Y') }}
                    -
                    {{ \Carbon\Carbon::parse($member->tgl_expired)->format('d/m/Y') }}
                </div>

                <div class="label">Membership</div>
                <div class="value">{{ $member->membership->name ?? '-' }}</div>
            </div>

            <div class="qr">
                {!! QrCode::size(90)->margin(0)->generate($member->qr_code) !!}
            </div>
        </div>

        <div class="footer">
            <span>QR berlaku selama masa aktif</span>
            <span>MyMemberID</span>
        </div>
    </div>


    <script>
        setTimeout(function() {
            window.print();
        }, 1000)
    </script>
</body>

</html>
