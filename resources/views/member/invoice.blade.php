<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $member->nama }} | Print Invoice Member</title>

    <style>
        .container {
            max-width: 80mm !important;
            border: 1px solid black;
            text-align: center;
        }
    </style>
</head>

<body>
    @php
        $qtyItem = max((int) ($transaction->amount ?? 1), 1);
        $baseMembershipPrice = (float) ($member->membership->price ?? 0);
        $adminFee = max(0, ((float) ($transaction->bayar ?? 0)) - $baseMembershipPrice);
        $totalBayar = (float) ($transaction->bayar ?? 0) + (float) ($transaction->ppn ?? 0);
        $hargaSatuan = $totalBayar / $qtyItem;
        $membershipName = filled($member->membership->name ?? null) ? $member->membership->name : 'Membership';
    @endphp
    <div class="container">
        <div style="margin-bottom: 10px; text-transform: uppercase; font-weight: bold;">
            <h4 style="margin: 0;">Invoice Membership</h4>
        </div>

        <div style="display: flex; justify-content: center; font-size: 13px; padding: 10px 12px;">
            <table style="width: 100%; border-collapse: collapse;">
                <tr style="text-align: left;">
                    <td width="40%">Jenis</td>
                    <td width="60%">
                        @if($adminFee > 0)
                            Perpanjangan Baru
                        @elseif(($transaction->transaction_type ?? 'registration') === 'renewal')
                            Perpanjangan
                        @else
                            Registrasi
                        @endif
                    </td>
                </tr>
                <tr style="text-align: left;">
                    <td>No Invoice</td>
                    <td>{{ $transaction->ticket_code ?? $member->qr_code }}</td>
                </tr>
                <tr style="text-align: left;">
                    <td>Tanggal</td>
                    <td>{{ $transaction?->created_at?->format('d/m/Y H:i:s') ?? now('Asia/Jakarta')->format('d/m/Y H:i:s') }}</td>
                </tr>
                <tr style="text-align: left;">
                    <td>Nama</td>
                    <td>{{ filled($member->nama) ? $member->nama : '-' }}</td>
                </tr>
                <tr style="text-align: left;">
                    <td>NIK</td>
                    <td>{{ filled($member->no_ktp) ? $member->no_ktp : '-' }}</td>
                </tr>
                <tr style="text-align: left;">
                    <td>No HP</td>
                    <td>{{ filled($member->no_hp) ? $member->no_hp : '-' }}</td>
                </tr>
                <tr style="text-align: left;">
                    <td>Kasir</td>
                    <td>{{ filled($cashierName ?? null) ? $cashierName : '-' }}</td>
                </tr>
                <tr style="text-align: left;">
                    <td>Membership</td>
                    <td>{{ filled($member->membership->name ?? null) ? $member->membership->name : '-' }}</td>
                </tr>
                <tr style="text-align: left;">
                    <td>Masa Aktif</td>
                    <td>{{ filled($member->tgl_register) ? $member->tgl_register : '-' }} - {{ filled($member->tgl_expired) ? $member->tgl_expired : '-' }}</td>
                </tr>
                @if($member->membership && $member->membership->max_access !== null)
                <tr style="text-align: left;">
                    <td>Max Access</td>
                    <td>{{ (int)$member->membership->max_access === 0 ? 'Unlimited' : $member->membership->max_access . ' kali' }}</td>
                </tr>
                @endif
                @if($member->childs && $member->childs->count() > 0)
                <tr style="text-align: left;">
                    <td>Submember</td>
                    <td>{{ $member->childs->pluck('nama')->filter()->implode(', ') }}</td>
                </tr>
                @endif
                <tr style="text-align: left;">
                    <td>Harga</td>
                    <td>{{ "Rp. " .  number_format(($member->membership->price ?? 0), 0, ',', '.') }}</td>
                </tr>
                <tr style="text-align: left;">
                    <td>Jumlah Jenis</td>
                    <td>1</td>
                </tr>
                <tr style="text-align: left;">
                    <td>Jumlah Item</td>
                    <td>{{ $qtyItem }}</td>
                </tr>
                <tr style="text-align: left;">
                    <td>Rincian Item</td>
                    <td>{{ $membershipName }}</td>
                </tr>
                <tr style="text-align: left;">
                    <td>Qty x Satuan</td>
                    <td>{{ $qtyItem }} x Rp. {{ number_format($hargaSatuan, 0, ',', '.') }}</td>
                </tr>
                <tr style="text-align: left;">
                    <td>Subtotal</td>
                    <td>Rp. {{ number_format($totalBayar, 0, ',', '.') }}</td>
                </tr>
                @if($adminFee > 0)
                <tr style="text-align: left;">
                    <td>Biaya Admin</td>
                    <td>Rp. {{ number_format($adminFee, 0, ',', '.') }}</td>
                </tr>
                @endif
                <tr style="text-align: left;">
                    <td>Metode</td>
                    <td>{{ strtoupper($transaction->metode ?? '-') }}</td>
                </tr>
                <tr style="text-align: left;">
                    <td>Total Bayar</td>
                    <td>Rp. {{ number_format($totalBayar, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>
    </div>

    <div style="text-align: center; margin-top: 8px; font-size: 11px; text-transform: uppercase;">
        {!! nl2br(e($ucapan ?? 'Terima Kasih')) !!}
    </div>
    @if(!empty($deskripsi))
    <div style="text-align: center; margin-top: 4px; font-size: 11px; text-transform: uppercase;">
        {!! nl2br(e($deskripsi)) !!}
    </div>
    @endif


    <script>
        setTimeout(function() {
            window.print();
        }, 1000)
    </script>
</body>

</html>
