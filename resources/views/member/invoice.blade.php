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
    <div class="container">
        <div style="margin-bottom: 20px; text-transform: uppercase;">
            <h4>Invoice My Member ID</h4>
        </div>

        <div style="display: flex; justify-content: center; font-size: 14px; padding: 15px;">
            <table>
                <tr style="text-align: left;">
                    <td width="100">Nama: </td>
                    <td width="200">{{ $member->nama }}</td>
                </tr>

                <tr style="text-align: left;">
                    <td width="100">NIK: </td>
                    <td width="200">{{ $member->no_ktp }}</td>
                </tr>

                <tr style="text-align: left;">
                    <td width="100">QR Code: </td>
                    <td width="200">{{ $member->qr_code }}</td>
                </tr>

                <tr style="text-align: left;">
                    <td width="100">Membership: </td>
                    <td width="200">{{ $member->membership->name }}</td>
                </tr>

                <tr style="text-align: left;">
                    <td width="100">Price: </td>
                    <td width="200">{{ "Rp. " .  number_format($member->membership->price, 0, ',', '.') }}</td>
                </tr>

                <tr style="text-align: left;">
                    <td width="100">Reg Date: </td>
                    <td width="200">{{ $member->tgl_register }}</td>
                </tr>

                <tr style="text-align: left;">
                    <td width="100">Exp Date: </td>
                    <td width="200">{{ $member->tgl_expired }}</td>
                </tr>
            </table>
        </div>
    </div>


    <script>
        setTimeout(function() {
            window.print();
        }, 1000)
    </script>
</body>

</html>