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
                justify-content: center;
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
                background-color: #e12d39;
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
                background-color: #1f7a1f;
            }
            .switch input:checked + .slider:before {
                transform: translateX(24px);
            }
            .autohint {
                font-size: 13px;
                color: #6b7280;
                margin-top: 6px;
                text-align: center;
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
            .toggle-stack {
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 10px;
                text-align: center;
            }
            .pin-grid {
                display: grid;
                grid-template-columns: repeat(4, 56px);
                gap: 12px;
                justify-content: center;
                margin: 0 auto;
            }
            .pin-row {
                display: flex;
                flex-direction: column;
                align-items: center;
                text-align: center;
            }
            .pin-row label {
                text-align: center;
            }
            .pin-input {
                width: 56px;
                height: 56px;
                padding: 0;
                text-align: center;
                font-size: 20px;
                line-height: 1;
                border-radius: 10px;
                border: 1px solid #cbd2d9;
                box-sizing: border-box;
            }
            .pin-input:focus {
                outline: none;
                border-color: #0f62fe;
                box-shadow: 0 0 0 3px rgba(15, 98, 254, 0.15);
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
                        <div class="row pin-row">
                            <label for="pin_digit_1">Masukkan PIN</label>
                            <div class="pin-grid" role="group" aria-label="Masukkan 4 digit PIN">
                                <input class="pin-input" type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" id="pin_digit_1" autocomplete="one-time-code" required>
                                <input class="pin-input" type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" id="pin_digit_2" required>
                                <input class="pin-input" type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" id="pin_digit_3" required>
                                <input class="pin-input" type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" id="pin_digit_4" required>
                            </div>
                            <input type="hidden" name="pin" id="pin" required>
                            <div class="autohint">Isi 4 digit, form akan terkirim otomatis.</div>
                        </div>
                    </form>
                @else
                    <form id="secret-setting-form" method="post" action="{{ route('secret-setting.store') }}">
                        @csrf
                        <div class="row toggle-stack">
                            <div class="status {{ $isSuspended ? 'suspended' : 'active' }}">
                                Website sedang {{ $isSuspended ? 'Nonaktif' : 'Aktif' }}
                            </div>
                            <div class="toggle">
                                <span class="toggle-label inactive">Nonaktif</span>
                                <label class="switch" for="site_active">
                                    <input type="checkbox" id="site_active" {{ $isSuspended ? '' : 'checked' }}>
                                    <span class="slider"></span>
                                </label>
                                <span class="toggle-label active">Aktif</span>
                            </div>
                            <input type="hidden" name="site_suspended" id="site_suspended" value="1" {{ $isSuspended ? '' : 'disabled' }}>
                            <div class="autohint">Sentuh toggle untuk mengubah. Tersimpan otomatis.</div>
                        </div>
                    </form>
                @endif
            </div>
        </div>
        <script src="{{ asset('/') }}plugins/sweetalert/dist/sweetalert.min.js"></script>
        <script>
            const pinInputs = Array.from(document.querySelectorAll('.pin-input'));
            const pinField = document.getElementById('pin');
            const unlockForm = pinField ? pinField.closest('form') : null;

            if (pinInputs.length && pinField && unlockForm) {
                let isSubmitting = false;

                const updatePinAndSubmit = () => {
                    const pinValue = pinInputs.map((input) => input.value).join('');
                    pinField.value = pinValue;

                    if (pinValue.length === 4 && /^\d{4}$/.test(pinValue) && !isSubmitting) {
                        isSubmitting = true;
                        unlockForm.submit();
                    }
                };

                pinInputs.forEach((input, index) => {
                    input.addEventListener('input', (event) => {
                        const digit = event.target.value.replace(/\D/g, '').slice(-1);
                        event.target.value = digit;

                        if (digit && pinInputs[index + 1]) {
                            pinInputs[index + 1].focus();
                        }

                        updatePinAndSubmit();
                    });

                    input.addEventListener('keydown', (event) => {
                        if (event.key === 'Backspace' && !event.target.value && pinInputs[index - 1]) {
                            pinInputs[index - 1].focus();
                        }
                    });

                    input.addEventListener('paste', (event) => {
                        event.preventDefault();
                        const pasted = (event.clipboardData || window.clipboardData).getData('text');
                        const digits = pasted.replace(/\D/g, '').slice(0, 4).split('');

                        if (!digits.length) {
                            return;
                        }

                        let cursor = index;
                        digits.forEach((digit) => {
                            if (pinInputs[cursor]) {
                                pinInputs[cursor].value = digit;
                                cursor += 1;
                            }
                        });

                        if (pinInputs[Math.min(cursor, pinInputs.length - 1)]) {
                            pinInputs[Math.min(cursor, pinInputs.length - 1)].focus();
                        }

                        updatePinAndSubmit();
                    });
                });

                pinInputs[0].focus();
            }

            const toggle = document.getElementById('site_active');
            const suspendedField = document.getElementById('site_suspended');
            const form = document.getElementById('secret-setting-form');

            if (toggle && form && suspendedField) {
                let lastState = toggle.checked;
                const syncSuspendedField = (isActive) => {
                    suspendedField.disabled = isActive;
                };

                syncSuspendedField(toggle.checked);

                toggle.addEventListener('change', () => {
                    const isActive = toggle.checked;
                    const title = isActive ? 'Aktifkan website?' : 'Nonaktifkan website?';
                    const text = isActive
                        ? 'Website akan kembali aktif dan bisa diakses.'
                        : 'Website akan menampilkan halaman nonaktif untuk semua user.';
                    const confirmText = isActive ? 'Aktifkan' : 'Nonaktifkan';

                    if (typeof swal !== 'function') {
                        syncSuspendedField(isActive);
                        form.submit();
                        return;
                    }

                    swal({
                        title: title,
                        text: text,
                        icon: isActive ? 'info' : 'warning',
                        buttons: {
                            cancel: 'Batal',
                            confirm: {
                                text: confirmText,
                                value: true,
                                closeModal: true,
                            }
                        },
                        dangerMode: !isActive,
                    }).then((willProceed) => {
                        if (willProceed) {
                            syncSuspendedField(isActive);
                            form.submit();
                        } else {
                            toggle.checked = lastState;
                            syncSuspendedField(lastState);
                        }
                    });
                });
            }
        </script>
    </body>
</html>
