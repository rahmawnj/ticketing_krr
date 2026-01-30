<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $member->nama }} | Print QR Member</title>

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
        <div style="margin-bottom: 20px;">
            <h4>My Member ID</h4>

            {!! QrCode::size(100)->generate($member->qr_code) !!}
        </div>

        <div style="display: flex; justify-content: center; font-size: 11px; padding: 15px;">
            <table>
                <tr style="text-align: left;">
                    <th width="100">Nama: </th>
                    <th width="200">{{ $member->nama }}</th>
                </tr>

                <tr style="text-align: left;">
                    <th width="100">NIK: </th>
                    <th width="200">{{ $member->no_ktp }}</th>
                </tr>

                <tr style="text-align: left;">
                    <th width="100">QR Code: </th>
                    <th width="200">{{ $member->qr_code }}</th>
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