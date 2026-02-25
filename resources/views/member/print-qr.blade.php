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
            padding: 3.5mm 6mm 6mm 6mm;
            position: relative;
        }
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 2.5mm;
        }
        .brand-wrap {
            display: flex;
            align-items: center;
            gap: 2mm;
            min-width: 0;
        }
        .brand {
            font-weight: 700;
            font-size: 12px;
            text-transform: uppercase;
        }
        .brand-logo {
            max-height: 8mm;
            max-width: 28mm;
            width: auto;
            height: auto;
            object-fit: contain;
            display: block;
        }
        .member-id {
            font-size: 10px;
            color: #2a4b5f;
            text-align: right;
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
            justify-content: flex-end;
        }
        .app-footer {
            font-size: 9px;
            font-weight: 700;
            color: #2a4b5f;
            text-transform: uppercase;
            white-space: nowrap;
        }
        .member-code-big {
            position: absolute;
            left: 6mm;
            bottom: 3.5mm;
            font-size: 13px;
            line-height: 1;
            font-weight: 700;
            color: #0f2a3c;
            letter-spacing: 0.3px;
        }
    </style>
</head>

<body>
    @php
        $membershipCode = $member->membership->code ?? '-';
        $showLogo = (int) ($setting->use_logo ?? 0) === 1 && !empty($setting->logo ?? null);
        $logoUrl = $showLogo ? asset('storage/' . $setting->logo) : null;
    @endphp

    <div class="card">
        <div class="header">
            <div class="brand-wrap">
                @if ($showLogo)
                    <img src="{{ $logoUrl }}" alt="Logo" class="brand-logo">
                @endif
            </div>
            <div class="member-id">{{ $member->display_member_code ?? '-' }}</div>
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
                {!! QrCode::size(100)->margin(0)->errorCorrection('H')->generate($member->qr_code) !!}
            </div>
        </div>

        <div class="footer">
            <span class="app-footer">{{ $setting->name ?? 'ANWA' }}</span>
        </div>

        <div class="member-code-big">{{ $membershipCode }}</div>
    </div>


    <script>
        setTimeout(function() {
            window.print();
        }, 1000)
    </script>
</body>

</html>
