@extends('layouts.master', ['title' => $title, 'breadcrumbs' => $breadcrumbs])

@push('style')
<link href="{{ asset('/') }}plugins/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
<link href="{{ asset('/') }}plugins/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" />

<style>
    .btn-expired {
        cursor: pointer;
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
        <form action="{{ $action }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method($method)

            <div class="form-group mb-3 border-bottom">
                <h5>Member</h5>
            </div>

            @if($member->parent_id == 0)
            <div class="form-group row mb-3">
                <div class="col-md-4">
                    <label for="membership" class="form-label"><sup class="text-danger">*</sup>Jenis Member</label>
                    <select name="membership" id="membership" class="form-control" {{ $member->id != 0 && $member->membership_id != 0 ? 'disabled' : '' }}>
                        <option value="" {{ (old('membership') ?? $member->membership_id) ? '' : 'selected' }}>-- Pilih Jenis Member --</option>
                        @foreach ($memberships as $membership)
                        {{-- MENAMBAH DATA-ATTRIBUTES UNTUK PPN --}}
                        <option
                            {{ (old('membership') ?? $member->membership_id) == $membership->id ? 'selected' : '' }}
                            data-price="{{ $membership->price }}"
                            data-duration="{{ $membership->duration_days }}"
                            data-max-person="{{ $membership->max_person }}"
                            data-use-ppn="{{ $membership->use_ppn }}"
                            data-ppn-rate="{{ $membership->ppn }}"
                            value="{{ $membership->id }}">
                            {{ $membership->name }}
                        </option>
                        @endforeach
                    </select>

                    @error('membership')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="duration" class="form-label">Durasi (Hari)</label>
                    <input type="text" name="" id="duration" class="form-control" disabled value="{{ $member->id != 0 && $member->membership_id != 0 ? $member->membership->duration_days : '' }}">
                </div>

                <div class="col-md-4">
                    <label for="price" class="form-label">Harga Total (Rp.)</label>
                    {{-- Di halaman edit, tampilkan harga member *saat ini* + PPN jika ada (INI DARI PHP, JIKA ADA NILAI) --}}
                    @php
                        $displayPrice = '';
                        if ($member->id && $member->membership_id != 0) {
                            $membershipPrice = $member->membership->price;
                            // PPN Rate yang disimpan di database adalah persentase, jadi bagi 100
                            $ppnRate = $member->membership->use_ppn ? ($member->membership->ppn / 100) : 0;
                            $totalDisplayPrice = $membershipPrice + ($membershipPrice * $ppnRate);
                            // Format harga dari PHP untuk tampilan awal
                            $displayPrice = number_format($totalDisplayPrice, 0, ',', '.');
                        }
                    @endphp
                    <input type="text" name="" id="price" class="form-control" disabled value="{{ $displayPrice }}">
                </div>
            </div>

            <div class="form-group row mb-3">
                <div class="col-md-4">
                    <label for="rfid" class="form-label">RFID</label>
                    <input type="text" name="rfid" id="rfid" class="form-control" value="{{ old('rfid', $member->rfid) }}" placeholder="0192029300">

                    @error('rfid')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="nama" class="form-label"><sup class="text-danger">*</sup>Nama</label>
                        <input type="text" name="nama" id="nama" class="form-control" value="{{ old('nama', $member->nama) }}" placeholder="John Doe">

                        @error('nama')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="no_ktp" class="form-label">No Identitas</label>
                        <input type="number" name="no_ktp" id="no_ktp" class="form-control" value="{{ old('no_ktp', $member->no_ktp) }}" placeholder="xxxxxxxxx">

                        @error('no_ktp')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-group row mb-3">
                <div class="col-md-4">
                    <label for="no_hp" class="form-label"><sup class="text-danger">*</sup>No Hp</label>
                    <input type="number" name="no_hp" id="no_hp" class="form-control" value="{{ old('no_hp', $member->no_hp) }}" placeholder="08xxxxx">

                    @error('no_hp')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="tanggal_lahir" class="form-label"><sup class="text-danger">*</sup>Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir', $member->tgl_lahir) }}">

                    @error('tanggal_lahir')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="jenis_kelamin" class="form-label"><sup class="text-danger">*</sup>Jenis Kelamin</label>
                    <select name="jenis_kelamin" id="jenis_kelamin" class="form-control">
                        <option value="L" {{ old('jenis_kelamin', $member->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-Laki</option>
                        <option value="P" {{ old('jenis_kelamin', $member->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>

                    @error('jenis_kelamin')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="form-group mb-3">
                <label for="alamat" class="form-label"><sup class="text-danger">*</sup>Alamat</label>
                <textarea name="alamat" id="alamat" cols="30" rows="3" class="form-control" placeholder="Jakarta">{{ old('alamat', $member->alamat) }}</textarea>

                @error('alamat')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="image_profile" class="form-label">Foto</label>
                <input type="file" name="image_profile" id="image_profile" class="form-control" accept="image/*">

                @error('image_profile')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group mb-3">
                <img src="{{ $member->image_profile ? asset('/storage/' . $member->image_profile) : '' }}" alt="Preview" id="image-preview" width="100" style="{{ $member->image_profile ? 'display: block;' : 'display: none;' }}">
            </div>

            <div class="form-group mb-3">
                <label for="tgl_expired" class="form-label">Tanggal Expired</label>
                <input type="date" name="tgl_expired" id="tgl_expired" class="form-control" value="{{ $member->tgl_expired }}" readonly>

                @error('tgl_expired')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="status" name="is_active" {{ ($member->is_active ?? true) ? 'checked' : '' }} />
                    <label class="form-check-label" for="status">Status Membership</label>
                </div>
                <small class="text-secondary"><sup class="text-danger">*</sup> Uncheck jika ingin menonaktifkan membership.</small>
            </div>

            @else
            {{-- Bagian untuk Child Member --}}
            <div class="form-group member-group row mb-3">
                <div class="col-md-4">
                    <label for="rfid" class="form-label">RFID</label>
                    <input type="number" name="rfid" id="rfid" class="form-control" placeholder="xxxxxxxxx" value="{{ $member->rfid }}">
                </div>
                <div class="col-md-4">
                    <label for="nama" class="form-label">Nama</label>
                    <input type="text" name="nama" id="nama" class="form-control" placeholder="John Doe" value="{{ $member->nama }}">
                </div>
                <div class="col-md-4">
                    <label for="image_profile" class="form-label">Foto</label>
                    <input type="file" name="image_profile" id="image_profile" class="form-control" accept="image/*">
                </div>
            </div>
            @endif

            <div class="form-group mb-3" id="submit-btn">
                <button type="submit" class="btn btn-primary">Save</button>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

<script>
    // Helper function to format number as Rupiah (simple version)
    function formatRupiah(angka) {
        var reverse = angka.toString().split('').reverse().join(''),
            ribuan = reverse.match(/\d{1,3}/g);
        ribuan = ribuan.join('.').split('').reverse().join('');
        return ribuan;
    }

    $("#rfid").on('keypress', function(e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) {
            e.preventDefault();
            return false;
        }
    })

    function calculateAndDisplayPrice() {
        var selectedOption = $('#membership').find('option:selected');
        var durationStr = selectedOption.data('duration');
        var basePrice = parseFloat(selectedOption.data('price')) || 0;
        var max_person = parseInt(selectedOption.data('max-person')) || 0;
        var usePpn = selectedOption.data('use-ppn');
        var ppnRate = parseFloat(selectedOption.data('ppn-rate')) || 0;

        if ($('#membership').val()) { // Jika memilih opsi yang valid
            var duration = parseInt(durationStr);
            if (isNaN(duration)) {
                duration = 0;
            }

            // --- Kalkulasi Harga Total (Harga Dasar + PPN jika ada) ---
            var totalPrice = basePrice;
            if (usePpn) {
                // PPN Rate dari data-attribute sudah berupa persentase, tapi perlu dibagi 100 untuk kalkulasi
                var ppnAmount =  ppnRate;
                totalPrice = basePrice + ppnAmount;
            }

            $('#duration').val(duration);
            $('#price').val(formatRupiah(Math.round(totalPrice))); // Tampilkan harga total dengan format Rupiah

            // --- Kalkulasi Tanggal Expired (hanya di create/saat ganti member) ---
            if ("{{ $method }}" === "POST") {
                var today = new Date();
                today.setDate(today.getDate() + duration);

                var year = today.getFullYear();
                var month = (today.getMonth() + 1).toString().padStart(2, '0');
                var day = today.getDate().toString().padStart(2, '0');
                var formattedDate = year + '-' + month + '-' + day;

                $('#tgl_expired').val(formattedDate);
            }


            // --- Loop untuk Anggota Grup (hanya di create/saat ganti member) ---
            if ("{{ $method }}" === "POST") {
                $('.form-group.title-group').remove();
                $(".form-group.member-group").remove();

                for (var i = 1; i < max_person; i++) {
                    var rfidGroupField = `
                    <div class="form-group member-group row mb-3">
                        <div class="col-md-4">
                            <label for="rfid_${i}" class="form-label">RFID Anggota</label>
                            <input type="text" name="rfid_group[]" id="rfid_${i}" class="form-control" placeholder="0192029300">
                        </div>
                        <div class="col-md-4">
                            <label for="nama_${i}" class="form-label">Nama Anggota</label>
                            <input type="text" name="name_group[]" id="nama_${i}" class="form-control" placeholder="John Doe">
                        </div>
                        <div class="col-md-4">
                            <label for="image_profile_${i}" class="form-label">Foto Anggota</label>
                            <input type="file" name="image_group[]" id="image_profile_${i}" class="form-control" accept="image/*">
                        </div>
                    </div>
                    `;

                    var title = `
                    <div class="form-group title-group mb-3 border-bottom">
                        <h5>Anggota Grup</h5>
                    </div>
                    `;

                    if (i === 1) {
                        $(title).insertBefore($("#submit-btn"));
                    }
                    $(rfidGroupField).insertBefore($("#submit-btn"));
                }
            }
        } else {
            // Jika user memilih "-- Pilih --", kosongkan field
            $('#duration').val('');
            $('#price').val('');
            $('#tgl_expired').val('');
            if ("{{ $method }}" === "POST") {
                $('.form-group.title-group').remove();
                $(".form-group.member-group").remove();
            }
        }
    }


    $(document).ready(function() {

        // --- DETEKSI HALAMAN CREATE ---
        var isCreatePage = "{{ $method }}" === "POST";

        // --- UNTUK HALAMAN EDIT (PUT) ---
        // Panggil fungsi kalkulasi saat halaman edit dimuat.
        // Ini akan memastikan harga tampil (jika dropdown tidak di-disable)
        // Jika disabled, harga sudah ditampilkan oleh PHP, tapi fungsi ini tetap aman.
        if ($('#membership').val()) {
            calculateAndDisplayPrice();
        }


        // --- LOGIKA PERUBAHAN JENIS MEMBER (BERLAKU DI CREATE, dan di EDIT JIKA INPUT TIDAK DI-DISABLE) ---
        $('#membership').on('change', function() {
            calculateAndDisplayPrice();
        });

        // ========================================================
        // LOGIC PREVIEW GAMBAR (BERJALAN DI CREATE & EDIT)
        // ========================================================
        $('#image_profile').on('change', function(event) {
            var reader = new FileReader();
            var output = document.getElementById('image-preview');

            reader.onload = function() {
                output.src = reader.result;
                output.style.display = 'block';
            };

            if (event.target.files && event.target.files[0]) {
                reader.readAsDataURL(event.target.files[0]);
            } else {
                // Check if there was an existing image to keep displaying it on edit page
                var existingImagePath = "{{ $member->image_profile ? asset('/storage/' . $member->image_profile) : '' }}";
                if ("{{ $method }}" === "PUT" && existingImagePath) {
                     output.src = existingImagePath;
                     output.style.display = 'block';
                } else {
                     output.src = '';
                     output.style.display = 'none';
                }
            }
        });
    });
</script>
@endpush
