<!doctype html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $appName }} - Nonaktif</title>
        <style>
            :root {
                color-scheme: light;
            }
            body {
                font-family: "Segoe UI", Tahoma, sans-serif;
                margin: 0;
                background: #0f172a;
                color: #f8fafc;
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 24px;
            }
            .card {
                max-width: 560px;
                background: #111827;
                border-radius: 18px;
                padding: 32px;
                box-shadow: 0 24px 50px rgba(15, 23, 42, 0.4);
                text-align: center;
            }
            h1 {
                margin: 0 0 12px;
                font-size: 28px;
            }
            p {
                margin: 0 0 12px;
                color: #cbd5f5;
                line-height: 1.5;
            }
        </style>
    </head>
    <body>
        <div class="card">
            <h1>Website Sedang Dinonaktifkan</h1>
            <p>{{ $appName }} sedang dinonaktifkan sementara.</p>
            <p>Silakan hubungi admin jika membutuhkan akses.</p>
        </div>
    </body>
</html>
