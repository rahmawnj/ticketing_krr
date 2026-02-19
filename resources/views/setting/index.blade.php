@extends('layouts.master', ['title' => $title, 'breadcrumbs' => $breadcrumbs])

@push('style')
<link href="{{ asset('/') }}plugins/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
<link href="{{ asset('/') }}plugins/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" />
<link href="{{ asset('/') }}plugins/select2/dist/css/select2.min.css" rel="stylesheet" />
<style>
    #preview-container img {
        max-height: 150px;
        border: 1px solid #ddd;
        padding: 5px;
        border-radius: 4px;
    }
</style>
@endpush

@section('content')
<div class="panel panel-inverse">
    <div class="panel-heading">
        <h4 class="panel-title">{{ $title }}</h4>
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand"><i class="fa fa-expand"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-success" data-toggle="panel-reload"><i class="fa fa-redo"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-warning" data-toggle="panel-collapse"><i class="fa fa-minus"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-danger" data-toggle="panel-remove"><i class="fa fa-times"></i></a>
        </div>
    </div>

    <div class="panel-body">
        <form action="{{ route('setting.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group mb-3">
                        <label for="name">Name</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ $setting->name ?? old('name') }}">
                        @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="ucapan">Ucapan</label>
                        <input type="text" name="ucapan" id="ucapan" class="form-control" value="{{ $setting->ucapan ?? old('ucapan') }}">
                        @error('ucapan') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="deskripsi">Deskripsi Singkat</label>
                        <textarea name="deskripsi" id="deskripsi" class="form-control" rows="3">{{ $setting->deskripsi ?? old('deskripsi') }}</textarea>
                        @error('deskripsi') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="show_logo_toggle" name="use_logo" value="1" {{ (isset($setting) && $setting->use_logo == 1) ? 'checked' : '' }} />
                        <label class="form-check-label" for="show_logo_toggle">Show Logo</label>
                    </div>

                    <div class="form-group mb-3">
                        <label for="logo">Logo</label>
                        <input type="file" name="logo" id="logo_input" class="form-control">
                        @error('logo') <small class="text-danger">{{ $message }}</small> @enderror

                        <div id="preview-container" class="mt-3" style="{{ (isset($setting) && $setting->logo && $setting->use_logo == 1) ? '' : 'display:none;' }}">
                            <label class="d-block">Current/Preview Logo:</label>
                            @if(isset($setting) && $setting->logo)
                                <img src="{{ asset('storage/' . $setting->logo) }}" id="logo_preview" alt="Logo">
                            @else
                                <img src="" id="logo_preview" alt="Logo Preview" style="display:none;">
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="form-group mb-3">
                        <label for="ppn">PPN <sup class="text-danger">(%)</sup></label>
                        <input type="number" name="ppn" id="ppn" class="form-control" value="{{ $setting->ppn ?? old('ppn') }}">
                        @error('ppn') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="print_mode">Mode Print Tiket</label>
                        <select name="print_mode" id="print_mode" class="form-control">
                            @php
                                $printMode = $setting->print_mode ?? old('print_mode', 'per_qty');
                            @endphp
                            <option value="per_qty" {{ $printMode === 'per_qty' ? 'selected' : '' }}>
                                Print sesuai jumlah (qty)
                            </option>
                            <option value="per_ticket" {{ $printMode === 'per_ticket' ? 'selected' : '' }}>
                                Print 1x per jenis tiket (barcode sama)
                            </option>
                        </select>
                        <small class="text-muted">Jika barcode sama, bisa pilih 1x per jenis tiket agar tidak berulang.</small>
                        @error('print_mode') <br><small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="dashboard_metric_mode">Mode Nilai Kartu Dashboard</label>
                        <select name="dashboard_metric_mode" id="dashboard_metric_mode" class="form-control">
                            @php
                                $dashboardMetricMode = $setting->dashboard_metric_mode ?? old('dashboard_metric_mode', 'amount');
                            @endphp
                            <option value="amount" {{ $dashboardMetricMode === 'amount' ? 'selected' : '' }}>
                                Tampilkan Jumlah Uang
                            </option>
                            <option value="count" {{ $dashboardMetricMode === 'count' ? 'selected' : '' }}>
                                Tampilkan Jumlah Transaksi
                            </option>
                        </select>
                        <small class="text-muted">Mengatur nilai utama pada kartu Renewal, New Member, Rental, dan Ticket di dashboard.</small>
                        @error('dashboard_metric_mode') <br><small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="member_reminder_days">Reminder Expire Member <sup class="text-danger">(Hari)</sup></label>
                        <div class="input-group">
                            <input type="number" name="member_reminder_days" id="member_reminder_days" class="form-control" value="{{ $setting->member_reminder_days ?? old('member_reminder_days', 7) }}">
                            <span class="input-group-text">Hari</span>
                        </div>
                        <small class="text-muted">Munculkan peringatan X hari sebelum member kedaluwarsa.</small>
                        @error('member_reminder_days') <br><small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="member_delete_grace_days">Hapus Member Setelah Masa Tenggang <sup class="text-danger">(Hari)</sup></label>
                        <div class="input-group">
                            <input type="number" name="member_delete_grace_days" id="member_delete_grace_days" class="form-control" value="{{ $setting->member_delete_grace_days ?? old('member_delete_grace_days', 0) }}">
                            <span class="input-group-text">Hari</span>
                        </div>
                        <small class="text-muted">Member dihapus otomatis jika expired lebih dari X hari (cron harian).</small>
                        @error('member_delete_grace_days') <br><small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="whatsapp_enabled" name="whatsapp_enabled" value="1" {{ old('whatsapp_enabled', isset($setting) ? (int) $setting->whatsapp_enabled : 0) ? 'checked' : '' }}>
                        <label class="form-check-label" for="whatsapp_enabled">Aktifkan Pengiriman WhatsApp</label>
                        <div><small class="text-muted">Default nonaktif. Centang jika notifikasi WhatsApp sudah siap digunakan.</small></div>
                        @error('whatsapp_enabled') <small class="text-danger d-block">{{ $message }}</small> @enderror
                    </div>
                </div>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('script')
<script src="{{ asset('/') }}plugins/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="{{ asset('/') }}plugins/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="{{ asset('/') }}plugins/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="{{ asset('/') }}plugins/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>
<script src="{{ asset('/') }}plugins/sweetalert/dist/sweetalert.min.js"></script>
<script src="{{ asset('/') }}plugins/select2/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        // Fungsi untuk mengatur visibilitas container preview
        function togglePreviewVisibility() {
            const isChecked = $('#show_logo_toggle').is(':checked');
            const hasImage = $('#logo_preview').attr('src') !== "";

            if (isChecked && hasImage) {
                $('#preview-container').fadeIn();
            } else {
                $('#preview-container').fadeOut();
            }
        }

        // Jalankan saat checkbox berubah
        $('#show_logo_toggle').on('change', function() {
            togglePreviewVisibility();
        });

        // Jalankan saat file dipilih (Preview Upload)
        $('#logo_input').on('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#logo_preview').attr('src', e.target.result).show();
                    togglePreviewVisibility();
                }
                reader.readAsDataURL(file);
            }
        });
    });
</script>
@endpush
