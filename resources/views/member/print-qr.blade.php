<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $member->nama }} | Print QR Member</title>

    <style>
        @page { size: 80mm 60mm; margin: 0; }
        * { box-sizing: border-box; }
        html, body { margin: 0; width: 80mm; height: 60mm; overflow: hidden; }
        body { font-family: Arial, sans-serif; color: #0f2a3c; }
        .card {
            width: 79mm;
            height: 56mm;
            border: 1px solid #0f2a3c;
            padding: 2.5mm 3mm 3mm 3mm;
            margin: 0.5mm auto 0;
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
            grid-template-columns: 1fr 24mm;
            gap: 3mm;
            align-items: center;
        }
        .label { font-size: 9px; color: #4b5b66; }
        .value { font-size: 11px; font-weight: 600; margin-bottom: 2mm; }
        .qr {
            width: 24mm;
            height: 24mm;
            border: 1px solid #c9d6df;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .footer {
            font-size: 8px;
            color: #4b5b66;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 1.5mm;
        }
        .app-footer {
            font-size: 9px;
            font-weight: 700;
            color: #2a4b5f;
            text-transform: uppercase;
            white-space: nowrap;
        }
        .member-code-big {
            font-size: 13px;
            line-height: 1;
            font-weight: 700;
            color: #0f2a3c;
            letter-spacing: 0.3px;
        }
        @media print {
            html, body {
                width: 80mm !important;
                height: 80mm !important;
                margin: 0 !important;
                overflow: hidden !important;
            }
            .card {
                break-inside: avoid;
                page-break-inside: avoid;
            }
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
            <span class="member-code-big">{{ $membershipCode }}</span>
            <span class="app-footer">{{ $setting->name ?? 'ANWA' }}</span>
        </div>
    </div>


    <script>
        setTimeout(function() {
            window.focus();
            window.print();
        }, 500);

        window.onafterprint = function() {
            try {
                window.close();
            } catch (e) {}
        };
    </script>
</body>

</html>
