@extends('layouts.master', ['title' => $title, 'breadcrumbs' => $breadcrumbs])

@push('style')
{{-- Sertakan style yang dibutuhkan, misalnya select2 jika ada --}}
@endpush

@section('content')
<div class="panel panel-inverse">
    <div class="panel-heading">
        <h4 class="panel-title">{{ $title }}</h4>
        <div class="panel-heading-btn">
            <a href="{{ route('penyewaan.index') }}" class="btn btn-xs btn-icon btn-default"><i class="fa fa-times"></i> Back</a>
        </div>
    </div>

    <div class="panel-body">
        <form action="{{ route('penyewaan.store') }}" method="post" id="form-penyewaan">
            @csrf

            <div class="row">
                {{-- Kiri --}}
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="ticket">Jenis Sewa</label>
                        <select name="ticket" id="ticket" class="form-control">
                            <option disabled selected>-- Select Penyewaan --</option>
                            @foreach($tickets as $ticket)
                            {{-- MENAMBAH DATA-ATTRIBUTES UNTUK PPN --}}
                            <option
                                value="{{ $ticket->id }}"
                                data-harga="{{ $ticket->harga }}"
                                data-use-ppn="{{ $ticket->use_ppn }}"
                                data-ppn-rate="{{ $ticket->ppn }}"
                                data-use-time="{{ $ticket->use_time ?? 0 }}">
                                {{ $ticket->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('ticket')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="qty">Qty</label>
                        <input type="number" name="qty" id="qty" class="form-control" value="1">
                        @error('qty')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="harga_ticket">Harga Sewa (Per Item, sudah termasuk PPN)</label>
                        {{-- NOTE: Input ini harus diisi oleh JS, pastikan type adalah text atau number yang sesuai --}}
                        <input type="text" name="harga_ticket" id="harga_ticket" class="form-control" value="0" readonly>
                        @error('harga_ticket')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Input Bayar (hanya untuk cash) --}}
                    <div class="form-group mb-3 bayar d-none">
                        <label for="bayar">Bayar (Cash)</label>
                        <input type="text" name="bayar" id="bayar" class="form-control" value="0">
                        @error('bayar')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                {{-- Kanan --}}
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="metode">Metode</label>
                        <select name="metode" id="metode" class="form-control">
                            <option disabled selected>-- Pilih Metode --</option>
                            <option value="cash">Cash</option>
                            <option value="debit">Debit</option>
                            <option value="transfer">Transfer</option>
                            <option value="credit">Credit Card</option>
                            <option value="qr">QR</option>
                            <option value="tap">Emoney (Tap)</option>
                        </select>
                        @error('metode')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="name">RFID (Jika Emoney)</label>
                        <input type="text" name="name" id="name" class="form-control" value="" readonly>
                        @error('name')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="sisa">Saldo (Jika Emoney)</label>
                        <input type="text" name="sisa" id="sisa" class="form-control" value="0" readonly>
                        @error('sisa')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Input Kembali (hanya untuk cash) --}}
                    <div class="form-group mb-3 kembali d-none">
                        <label for="kembali">Kembali</label>
                        <input type="text" name="kembali" id="kembali" class="form-control" value="0" readonly>
                        @error('kembali')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                {{-- Bawah (Total + Description + End Time) --}}
                <div class="col-md-12">
                    <div class="row g-3 align-items-start">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="keterangan">Description</label>
                                <textarea name="keterangan" id="keterangan" class="form-control" rows="3" placeholder="Keterangan penyewaan"></textarea>
                                @error('keterangan')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="jumlah">Total Jumlah Bayar (sudah termasuk PPN)</label>
                                <input type="text" name="jumlah" id="jumlah" class="form-control" value="0" readonly>
                                @error('jumlah')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group mb-3 time-fields d-none">
                                <label for="end_time">End Time</label>
                                <input type="time" name="end_time" id="end_time" class="form-control" value="">
                                @error('end_time')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Submit Penyewaan</button>
                <a href="{{ route('penyewaan.index') }}" class="btn btn-white">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('script')
    <script>
    $(document).ready(function() {

        // --- Fungsi Format Rupiah Universal ---
        /* Fungsi formatRupiah */
        function formatRupiah(angka) {
            var number_string = angka.toString().replace(/[^,\d]/g, ''),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            // tambahkan titik jika yang di input sudah menjadi angka ribuan
            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            // Memastikan tidak ada format yang aneh, hanya menggunakan titik sebagai pemisah ribuan
            return rupiah;
        }

        // --- Fungsi untuk Membersihkan Format Rupiah menjadi Angka Murni (Integer) ---
        function cleanRupiah(angka) {
            // Hapus titik sebagai pemisah ribuan, lalu ubah ke integer
            let cleaned = angka.toString().replace(/\./g, '');
            return parseInt(cleaned) || 0; // Pastikan hasilnya integer atau 0 jika gagal
        }

        // --- Fungsi Kalkulasi Harga (TERMASUK PPN) ---
        function calculatePrice() {
            let selectedOption = $("#ticket").find('option:selected');
            let baseHarga = parseInt(selectedOption.attr("data-harga")) || 0;
            let usePpn = selectedOption.attr("data-use-ppn") == '1' || selectedOption.attr("data-use-ppn") == 'true';
            let ppnRate = parseFloat(selectedOption.attr("data-ppn-rate")) || 0;
            let qty = parseInt($("#qty").val()) || 1;

            if (baseHarga === 0) {
                $("#harga_ticket").val(formatRupiah('0'));
                $("#jumlah").val(formatRupiah('0'));
                $("#bayar").val(formatRupiah('0'));
                $("#kembali").val(formatRupiah('0'));
                return;
            }

            // 1. Hitung Harga Per Item setelah PPN
            let hargaPerItem = baseHarga;

            if (usePpn && ppnRate > 0) {
                let ppnAmountPerItem = ppnRate ;

                hargaPerItem = Math.round(baseHarga + ppnAmountPerItem);
            }


            let jumlahTotal = hargaPerItem * qty;


            $("#harga_ticket").val(formatRupiah(hargaPerItem.toString()));
            $("#jumlah").val(formatRupiah(jumlahTotal.toString()));

            let metode = $("#metode").val();
            if (metode == 'cash') {
                $("#bayar").val(formatRupiah(jumlahTotal.toString()));
                $("#kembali").val(formatRupiah('0'));
            } else if (metode === 'tap') {
            }
        }


        function toggleTimeFields() {
            let selectedOption = $("#ticket").find('option:selected');
            let useTime = selectedOption.attr("data-use-time") == '1';
            if (useTime) {
                $(".time-fields").removeClass('d-none');
            } else {
                $(".time-fields").addClass('d-none');
                $("#end_time").val('');
            }
        }

        $("#ticket").on('change', function() {
            calculatePrice();
            toggleTimeFields();
        });
        $("#qty").on('change', calculatePrice);


        $("#metode").on('change', function() {
            let metode = $(this).val()
            if (metode == 'tap') {
                $("#name").removeAttr('readonly').val('');
                $("#sisa").val('0'); // Kosongkan Saldo
                $(".bayar").addClass('d-none');
                $(".kembali").addClass('d-none');
            } else {
                $("#name").attr("readonly", "readonly").val('');
                $(".bayar").removeClass('d-none');
                $(".kembali").removeClass('d-none');

                // Set Bayar kembali ke Jumlah saat ganti ke Cash
                let jumlah = $("#jumlah").val();
                $("#bayar").val(jumlah);
                $("#kembali").val(formatRupiah('0'));
            }
        })

        // --- Logika Perhitungan Kembalian (Cash) ---
        $("#bayar").on('keyup', function() {
            // Hanya jalankan jika metode cash
            if ($("#metode").val() !== 'cash') return;

            // Bersihkan input bayar saat ini dari format Rupiah (titik)
            let bayar = cleanRupiah($(this).val());

            // Bersihkan input jumlah dari format Rupiah (titik)
            let price = cleanRupiah($("#jumlah").val());

            // Lakukan perhitungan integer
            let kembali = bayar - price;

            // Tampilkan kembali hasil kembalian dalam format Rupiah
            $("#kembali").val(formatRupiah(kembali.toString()));
        })

        // --- Format Rupiah Live untuk Input Bayar ---
        var rupiahInput = document.getElementById('bayar');
        if (rupiahInput) {
            rupiahInput.addEventListener('keyup', function(e) {
                // Perhatian: Ini hanya memformat input, kalkulasi kembalian ada di event keyup sebelumnya
                rupiahInput.value = formatRupiah(this.value);
            });
        }

        // --- Logika RFID (Tetap) ---
        $('#form-penyewaan').on('keyup keypress', function(e) {
            var keyCode = e.keyCode || e.which;
            if (keyCode === 13 && e.target && e.target.tagName !== 'TEXTAREA') {
                e.preventDefault();
                return false;
            }
        });

        $("#name").on('keypress', function(e) {
            if (e.which == 13) {
                e.preventDefault();
                let rfid = $(this).val();

                $.ajax({
                    url: "/api/members",
                    type: "GET",
                    method: "GET",
                    data: {
                        rfid: rfid
                    },
                    success: function(response) {
                        if (response.status == 'success') {
                            let member = response.member;
                            // Asumsi saldo adalah integer
                            $("#sisa").val(formatRupiah(member.saldo.toString()))
                        } else {
                            // Gunakan sweetalert (asumsi library sudah dimuat)
                            swal("Error", "Member tidak ditemukan atau Expired.", "error");
                            $("#name").val("")
                            $("#sisa").val(formatRupiah('0'))

                        }
                    },
                    error: function(response) {
                        swal("Error", "Gagal memproses RFID.", "error");
                        $("#name").val("")
                        $("#sisa").val(formatRupiah('0'))
                    }
                })
            }
        })
    })
</script>
@endpush
