@php
date_default_timezone_set('Asia/Jakarta')
@endphp
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
            max-width: 77mm !important;
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
    <div class="ticket-row" style="margin-top: 10px;">
        <div class="qr-code ticket-card ticket-portrait" style="margin: 0 auto 0 auto;">
            <div style="font-size: 9.2pt; line-height: 16.5px; margin-top: 10px; margin-bottom: 10px;">
                <div style="text-align:center; margin-bottom: 10px;">
                    <div style="font-weight: 900; font-size: 10.5pt; text-transform: uppercase; margin-bottom: 6px;">{{ $name }}</div>
                    @if($use == 1)
                    <img src="{{ $logo }}" width="90" alt="The Logo" class="brand-image" style="opacity: .9; margin-bottom: 6px;">
                    @endif
                    <div style="margin: 6px 10px;"><hr style="border-style: dashed;"></div>
                    <div style="font-weight: 900; font-size: 9.2pt;">{{ $penyewaan->sewa->name }}</div>
                    <div style="font-size: 8.2pt;">{{ date('d/m/Y H:i:s', strtotime($penyewaan->created_at)) }}</div>
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
                        <div style="display: flex; justify-content: space-between; font-size: 8.2pt;">
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
                <p style="font-size:9.2pt;margin-left:10px;margin-top:5px;margin-bottom:0px; font-weight: bold;">Keterangan</p>
                <p style="font-size:9.2pt;margin-left:10px;margin-top:2px;margin-bottom:0px">{{ $penyewaan->keterangan }}</p>
                @endif
                <div style="margin: 24px 10px 4px 10px;">
                    <hr style="border-style: dashed;">
                </div>
                <br>
                <p style="font-size:8.2pt;text-align: center;margin-top:5px; text-transform: uppercase;">{!! nl2br(e($ucapan)) !!}</p>
                <p style="font-size:8.2pt;text-align: center;margin-bottom:10px; text-transform: uppercase;">{!! nl2br(e($deskripsi)) !!}</p>
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
