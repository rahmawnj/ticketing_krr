@php
date_default_timezone_set('Asia/Jakarta')
@endphp
<!DOCTYPE html>
<html>

<head>
    <title>Print QR</title>
    <style>
        @media print {
            .row {
                page-break-after: always;
            }

            .row .qr-code {
                page-break-after: always;
            }
        }
    </style>
</head>

<body>
    @php
        $qty = max((int) ($penyewaan->qty ?? 0), 1);
        $lineSubtotal = (float) ($penyewaan->jumlah ?? 0);
        $lineUnitPrice = $lineSubtotal / $qty;
        $startTimeRaw = $penyewaan->start_time ? substr((string) $penyewaan->start_time, 0, 5) : null;
        $endTimeRaw = $penyewaan->end_time ? substr((string) $penyewaan->end_time, 0, 5) : null;
        $masaSewaLabel = null;
        if ($startTimeRaw && $endTimeRaw) {
            try {
                $startTimeObj = \Carbon\Carbon::createFromFormat('H:i', $startTimeRaw);
                $endTimeObj = \Carbon\Carbon::createFromFormat('H:i', $endTimeRaw);
                if ($endTimeObj->lessThanOrEqualTo($startTimeObj)) {
                    $endTimeObj->addDay();
                }
                $diffMinutes = $startTimeObj->diffInMinutes($endTimeObj);
                $hours = intdiv($diffMinutes, 60);
                $minutes = $diffMinutes % 60;
                $durationParts = [];
                if ($hours > 0) {
                    $durationParts[] = $hours . ' jam';
                }
                if ($minutes > 0) {
                    $durationParts[] = $minutes . ' menit';
                }
                if (empty($durationParts)) {
                    $durationParts[] = '0 menit';
                }
                $masaSewaLabel = implode(' ', $durationParts);
            } catch (\Throwable $e) {
                $masaSewaLabel = null;
            }
        }
    @endphp
    <div class="row" style="max-height:150mm !important;">
        <div style="max-width:80mm !important; margin: 0 auto 0 auto; vertical-align: top; border-style: solid;border-width: 1px;">
            <div style="font-size: 10pt; line-height: 18px; margin-top: 10px; margin-bottom: 10px;">
                <div style="text-align:center; margin-bottom: 10px;">
                    <div style="font-weight: 900; font-size: 12pt; text-transform: uppercase; margin-bottom: 6px;">{{ $name }}</div>
                    @if($use == 1)
                    <img src="{{ $logo }}" width="90" alt="The Logo" class="brand-image" style="opacity: .9; margin-bottom: 6px;">
                    @endif
                    <div style="margin: 6px 10px;"><hr style="border-style: dashed;"></div>
                    <div style="font-weight: 900; font-size: 10pt;">{{ $penyewaan->sewa->name }}</div>
                    <div style="font-size: 9pt;">{{ date('d/m/Y H:i:s', strtotime($penyewaan->created_at)) }}</div>
                </div>

                <div style="display: flex;font-weight: 900; justify-content: space-between; margin-left: 10px; margin-right: 10px;">
                    <span>Jumlah Jenis : </span>
                    <span>1</span>
                </div>
                <div style="display: flex;font-weight: 900; justify-content: space-between; margin-left: 10px; margin-right: 10px;">
                    <span>Jumlah Item : </span>
                    <span>{{ $qty }}</span>
                </div>
                <div style="display: flex;font-weight: 900; justify-content: space-between; margin-left: 10px; margin-right: 10px;">
                    <span>No Transaksi : </span>
                    <span>{{ $transaction->ticket_code ?? '-' }}</span>
                </div>
                <div style="margin: 6px 10px;">
                    <div style="font-weight: 900;">Rincian Pembelian:</div>
                    <div style="margin-top: 2px;">
                        <div style="font-weight: 700;">{{ $penyewaan->sewa->name ?? '-' }}</div>
                        <div style="display: flex; justify-content: space-between; font-size: 9pt;">
                            <span>{{ $qty }} x Rp. {{ number_format($lineUnitPrice, 0, ',', '.') }}</span>
                            <span>Rp. {{ number_format($lineSubtotal, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
                <div style="display: flex;font-weight: 900; justify-content: space-between; margin-left: 10px; margin-right: 10px;">
                    <span>Subtotal : </span>
                    <span>Rp. {{ number_format($lineSubtotal, 0 , ',', '.') }}</span>
                </div>
                <div style="display: flex;font-weight: 900; justify-content: space-between; margin-left: 10px; margin-right: 10px;">
                    <span>Total Bayar : </span>
                    <span>Rp. {{ number_format($penyewaan->jumlah, 0 , ',', '.') }}</span>
                </div>
                <div style="display: flex;font-weight: 900; justify-content: space-between; margin-left: 10px; margin-right: 10px;">
                    <span>Metode : </span>
                    <span>{{ strtoupper($penyewaan->metode ?? '-') }}</span>
                </div>

                @if(!empty($penyewaan->start_time))
                <div style="display: flex;font-weight: 900; justify-content: space-between; margin-left: 10px; margin-right: 10px;">
                    <span>Start Time : </span>
                    <span>{{ $startTimeRaw }}</span>
                </div>
                @endif

                @if(!empty($penyewaan->end_time))
                <div style="display: flex;font-weight: 900; justify-content: space-between; margin-left: 10px; margin-right: 10px;">
                    <span>End Time : </span>
                    <span>{{ $endTimeRaw }}</span>
                </div>
                @endif
                @if($masaSewaLabel)
                <div style="display: flex;font-weight: 900; justify-content: space-between; margin-left: 10px; margin-right: 10px;">
                    <span>Masa Sewa : </span>
                    <span>{{ $masaSewaLabel }}</span>
                </div>
                @endif
                <br>
                @if(!empty($penyewaan->keterangan))
                <p style="font-size:10pt;margin-left:10px;margin-top:5px;margin-bottom:0px; font-weight: bold;">Keterangan</p>
                <p style="font-size:10pt;margin-left:10px;margin-top:2px;margin-bottom:0px">{{ $penyewaan->keterangan }}</p>
                @endif
                <div style="margin: 24px 10px 4px 10px;">
                    <hr style="border-style: dashed;">
                </div>
                <p style="font-size:10pt;margin-left:10px;margin-top:5px;margin-bottom:0px"></p>
                <br>
                <p style="font-size:9pt;text-align: center;margin-top:5px; text-transform: uppercase;">{!! nl2br(e($ucapan)) !!}</p>
                <p style="font-size:9pt;text-align: center;margin-bottom:10px; text-transform: uppercase;">{!! nl2br(e($deskripsi)) !!}</p>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/jquery.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            window.print()
        })
    </script>
</body>

</html>
