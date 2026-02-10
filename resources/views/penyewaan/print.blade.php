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
                    <span>Jumlah Sewa : </span>
                    <span>{{ $penyewaan->qty . ' X ' . number_format($penyewaan->sewa->harga, 0, ',', '.') }}</span>
                </div>
                <div style="display: flex;font-weight: 900; justify-content: space-between; margin-left: 10px; margin-right: 10px;">
                    <span>Jumlah : </span>
                    <span>{{ number_format($penyewaan->jumlah, 0 , ',', '.') }}</span>
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
                    <span>{{ $penyewaan->start_time }}</span>
                </div>
                @endif

                @if(!empty($penyewaan->end_time))
                <div style="display: flex;font-weight: 900; justify-content: space-between; margin-left: 10px; margin-right: 10px;">
                    <span>End Time : </span>
                    <span>{{ $penyewaan->end_time }}</span>
                </div>
                @endif
                <br>
                @if(!empty($penyewaan->keterangan))
                <p style="font-size:10pt;margin-left:10px;margin-top:5px;margin-bottom:0px; font-weight: bold;">Keterangan</p>
                <p style="font-size:10pt;margin-left:10px;margin-top:2px;margin-bottom:0px">{{ $penyewaan->keterangan }}</p>
                @endif
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
