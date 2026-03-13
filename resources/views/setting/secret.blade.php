<!doctype html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Setting Rahasia</title>
        <style>
            :root {
                color-scheme: light;
            }
            body {
                font-family: "Segoe UI", Tahoma, sans-serif;
                margin: 0;
                background: #f4f6f8;
                color: #1f2933;
            }
            .container {
                max-width: 520px;
                margin: 10vh auto;
                padding: 24px;
            }
            .card {
                background: #ffffff;
                border-radius: 16px;
                padding: 28px;
                box-shadow: 0 18px 40px rgba(31, 41, 51, 0.12);
            }
            h1 {
                margin: 0 0 12px;
                font-size: 22px;
            }
            p {
                margin: 0 0 16px;
                color: #52606d;
            }
            label {
                display: block;
                font-weight: 600;
                margin-bottom: 6px;
            }
            input[type="password"],
            input[type="text"] {
                width: 100%;
                padding: 10px 12px;
                border-radius: 10px;
                border: 1px solid #cbd2d9;
                font-size: 14px;
            }
            .row {
                margin-bottom: 16px;
            }
            .btn {
                display: inline-block;
                border: none;
                border-radius: 10px;
                padding: 10px 16px;
                font-weight: 600;
                cursor: pointer;
            }
            .btn-primary {
                background: #0f62fe;
                color: #fff;
            }
            .btn-danger {
                background: #e12d39;
                color: #fff;
            }
            .alert {
                border-radius: 10px;
                padding: 10px 12px;
                margin-bottom: 14px;
                font-size: 14px;
            }
            .alert-success {
                background: #e6fffa;
                color: #0c6b58;
                border: 1px solid #6ee7b7;
            }
            .alert-error {
                background: #ffecec;
                color: #8b2c2c;
                border: 1px solid #fca5a5;
            }
            .toggle {
                display: flex;
                align-items: center;
                gap: 14px;
            }
            .toggle-label {
                font-weight: 600;
                font-size: 14px;
            }
            .toggle-label.active {
                color: #1f7a1f;
            }
            .toggle-label.inactive {
                color: #b42318;
            }
            .switch {
                position: relative;
                display: inline-block;
                width: 52px;
                height: 28px;
            }
            .switch input {
                opacity: 0;
                width: 0;
                height: 0;
            }
            .slider {
                position: absolute;
                cursor: pointer;
                inset: 0;
                background-color: #cbd2d9;
                transition: 0.25s ease;
                border-radius: 999px;
            }
            .slider:before {
                position: absolute;
                content: "";
                height: 22px;
                width: 22px;
                left: 3px;
                top: 3px;
                background-color: #ffffff;
                transition: 0.25s ease;
                border-radius: 50%;
                box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
            }
            .switch input:checked + .slider {
                background-color: #e12d39;
            }
            .switch input:checked + .slider:before {
                transform: translateX(24px);
            }
            .autohint {
                font-size: 13px;
                color: #6b7280;
                margin-top: 6px;
            }
            .status {
                font-weight: 600;
            }
            .status.active {
                color: #1f7a1f;
            }
            .status.suspended {
                color: #b42318;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="card">
                <h1>Setting Rahasia</h1>
                <p>Halaman ini mengatur status website. Pilih dengan jelas: aktif atau nonaktif.</p>

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-error">{{ $errors->first() }}</div>
                @endif

                @if (!$isUnlocked)
                    <form method="post" action="{{ route('secret-setting.unlock') }}">
                        @csrf
                        <div class="row">
                            <label for="pin">Masukkan PIN</label>
                            <input type="password" name="pin" id="pin" placeholder="PIN rahasia" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Buka Akses</button>
                    </form>
                @else
                    <form id="secret-setting-form" method="post" action="{{ route('secret-setting.store') }}">
                        @csrf
                        <div class="row toggle">
                            <span class="toggle-label active">Aktif</span>
                            <label class="switch" for="site_suspended">
                                <input type="checkbox" id="site_suspended" name="site_suspended" value="1" {{ $isSuspended ? 'checked' : '' }}>
                                <span class="slider"></span>
                            </label>
                            <span class="toggle-label inactive">Nonaktif</span>
                            <div>
                                <div class="status {{ $isSuspended ? 'suspended' : 'active' }}">
                                    Website sedang {{ $isSuspended ? 'Nonaktif' : 'Aktif' }}
                                </div>
                                <div class="autohint">Sentuh toggle untuk mengubah. Tersimpan otomatis.</div>
                            </div>
                        </div>
                    </form>
                @endif
            </div>
        </div>
        <script src="{{ asset('/') }}plugins/sweetalert/dist/sweetalert.min.js"></script>
        <script>
            const toggle = document.getElementById('site_suspended');
            const form = document.getElementById('secret-setting-form');

            if (toggle && form) {
                let lastState = toggle.checked;

                toggle.addEventListener('change', () => {
                    const nextState = toggle.checked;
                    const title = nextState ? 'Nonaktifkan website?' : 'Aktifkan website?';
                    const text = nextState
                        ? 'Website akan menampilkan halaman nonaktif untuk semua user.'
                        : 'Website akan kembali aktif dan bisa diakses.';
                    const confirmText = nextState ? 'Nonaktifkan' : 'Aktifkan';

                    if (typeof swal !== 'function') {
                        form.submit();
                        return;
                    }

                    swal({
                        title: title,
                        text: text,
                        icon: nextState ? 'warning' : 'info',
                        buttons: {
                            cancel: 'Batal',
                            confirm: {
                                text: confirmText,
                                value: true,
                                closeModal: true,
                            }
                        },
                        dangerMode: nextState,
                    }).then((willProceed) => {
                        if (willProceed) {
                            form.submit();
                        } else {
                            toggle.checked = lastState;
                        }
                    });
                });
            }
        </script>
    </body>
</html>
