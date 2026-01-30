@extends('layouts.master', ['title' => $title, 'breadcrumbs' => $breadcrumbs])

@push('style')
<link href="{{ asset('/') }}plugins/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
<link href="{{ asset('/') }}plugins/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" />

<style>
    .btn-expired {
        cursor: pointer;
    }
    /* Menambahkan styling untuk tombol filter */
    .btn-filter.active {
        background-color: #007bff; /* Primary color */
        color: white;
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
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <a href="{{ route('members.create') }}" class="btn btn-primary"><i class="ion-ios-add"></i> Add Member</a>
                @include("member.import")
            </div>

            {{-- START: BUTTON FILTER BARU --}}
            <div class="btn-group" role="group" aria-label="Filter Member">
                <button type="button" class="btn btn-default btn-filter active" data-filter="all">All</button>
                <button type="button" class="btn btn-default btn-filter" data-filter="member">Member</button>
                <button type="button" class="btn btn-default btn-filter" data-filter="submember">Submember</button>
            </div>
            {{-- END: BUTTON FILTER BARU --}}
        </div>

        <table id="datatable" class="table table-striped table-bordered align-middle">
            <thead>
                <tr>
                    <th class="text-nowrap">No</th>
                    <th class="text-nowrap">Foto</th>
                    <th class="text-nowrap">No. Identitas</th> {{-- Tambah ini --}}
                    <th class="text-nowrap">RFID</th>
                    <th class="text-nowrap">Nama</th>
                    <th class="text-nowrap">No. HP</th>        {{-- Tambah ini --}}
                    <th class="text-nowrap">Tipe</th>
                    <th class="text-nowrap">Membership</th>
                    <th class="text-nowrap">Masa Berlaku</th>
                    <th class="text-nowrap">Sisa Hari</th>
                    <th class="text-nowrap">Status</th>
                    <th class="text-nowrap">Action</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <div class="modal fade" id="modal-dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Form Member</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <form method="post" class="form-member" id="form-member" enctype="multipart/form-data">
                    @csrf

                    <div class="modal-body" id="modal-form-input"></div>

                    <div class="modal-footer">
                        <a href="javascript:;" id="btn-close" class="btn btn-white" data-bs-dismiss="modal">Close</a>
                        <button type="submit" class="btn btn-primary" id="btn-submit">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<form action="" class="d-none" id="form-delete" method="post">
    @csrf
    @method('DELETE')
</form>
<form action="" class="d-none" id="form-expired" method="post">
    @csrf
</form>

@include("member.show")
@include("member.membership")
@endsection

@push('script')
<script src="{{ asset('/') }}plugins/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="{{ asset('/') }}plugins/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="{{ asset('/') }}plugins/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="{{ asset('/') }}plugins/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>
<script src="{{ asset('/') }}plugins/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

<script>
    // Menyimpan nilai filter saat ini
    var currentFilter = 'all';

    var table = $('#datatable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        // Mengirim data tambahan, yaitu 'filter'
        ajax: {
            url: "{{ route('members.list') }}",
            data: function (d) {
                d.filter = currentFilter;
            }
        },
        deferRender: true,
        pagination: true,
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', sortable: false, searchable: false },
            { data: 'image_profile', name: 'image_profile' },
            { data: 'no_ktp', name: 'no_ktp' }, // Kolom Identitas
            { data: 'rfid', name: 'rfid' },
            { data: 'nama', name: 'nama' },
            { data: 'no_hp', name: 'no_hp' },    // Kolom No HP
            {
                data: 'member_type', // Nama kolom yang didefinisikan di controller
                name: 'member_type',
                sortable: false,
                searchable: false
            },
            { data: 'membership_name', name: 'membership_name' },
            { data: 'masa_berlaku', name: 'masa_berlaku', sortable: false, searchable: false },
            { data: 'sisa_hari', name: 'sisa_hari', sortable: false, searchable: false },
            { data: 'expired', name: 'expired', sortable: false, searchable: false },
            { data: 'action', name: 'action', sortable: false, searchable: false },
        ]
    });

    // START: LOGIC FILTER BARU
    $('.btn-filter').on('click', function() {
        // Hapus class 'active' dari semua tombol
        $('.btn-filter').removeClass('active');
        // Tambahkan class 'active' ke tombol yang diklik
        $(this).addClass('active');

        // Ambil nilai data-filter
        currentFilter = $(this).data('filter');

        // Reload DataTables dengan parameter baru
        table.ajax.reload();
    });
    // END: LOGIC FILTER BARU


    $('#image_profile').change(function() {
        if (this.files && this.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#image-preview')
                    .attr('src', e.target.result)
                    .show();
            };
            reader.readAsDataURL(this.files[0]);
        } else {
            $('#image-preview').attr('src', '').hide();
        }
    });

    $("#btn-add").on('click', function() {
        $("#rfid").removeAttr('disabled');
        let route = $(this).attr('data-route')
        $("#form-member").attr('action', route)
        $("#form-member")[0].reset();
        $.ajax({
            url: "{{ route('members.create') }}",
            type: 'GET',
            method: 'GET',
            success: function(response) {
                $("#modal-form-input").html(response);
                $('#image-preview').attr('src', '').hide();
            }
        })
    })

    $("#btn-close").on('click', function() {
        $("#form-member").removeAttr('action')
    })

    $("#datatable").on('click', '.btn-edit', function() {
        $("#form-member")[0].reset();
        $("#form-member input[name='_method']").remove();

        let route = $(this).attr('data-route')
        let id = $(this).attr('id')

        $(".form-member").attr('action', route)
        $(".modal-footer").append(`<input type="hidden" name="_method" value="PUT">`);

    })

    $("#datatable").on('click', '.btn-delete', function(e) {
        e.preventDefault();
        let route = $(this).attr('data-route')
        $("#form-delete").attr('action', route)

        swal({
            title: 'Hapus data member?',
            text: 'Menghapus member bersifat permanen.',
            icon: 'error',
            buttons: {
                cancel: {
                    text: 'Cancel',
                    value: null,
                    visible: true,
                    className: 'btn btn-default',
                    closeModal: true,
                },
                confirm: {
                    text: 'Yes',
                    value: true,
                    visible: true,
                    className: 'btn btn-danger',
                    closeModal: true
                }
            }
        }).then((result) => {
            if (result) {
                $("#form-delete").submit()
            } else {
                $("#form-delete").attr('action', '')
            }
        });
    })

    $("#datatable").on('click', '.btn-expired', function(e) {
        e.preventDefault();
        let route = $(this).attr('data-route')
        $("#form-expired").attr('action', route)

        swal({
            title: 'Perpanjang data member?',
            icon: 'warning',
            buttons: {
                cancel: {
                    text: 'Cancel',
                    value: null,
                    visible: true,
                    className: 'btn btn-default',
                    closeModal: true,
                },
                confirm: {
                    text: 'Yes',
                    value: true,
                    visible: true,
                    className: 'btn btn-primary',
                    closeModal: true
                }
            }
        }).then((result) => {
            if (result) {
                $("#form-expired").submit()
            } else {
                $("#form-expired").attr('action', '')
            }
        });
    })

    $("#datatable").on('click', '.btn-show', function() {
        let route = $(this).attr('data-route')
        let id = $(this).attr('id')

        $.ajax({
            url: "/members/" + id,
            type: 'GET',
            method: 'GET',
            success: function(response) {
                console.log(response)
                let member = response.member;

                $("#info-name").text(member.nama)
                $("#info-id").text(member.no_ktp)
                $("#info-phone").text(member.no_hp)
                $("#info-birth").text(member.tgl_lahir)
                $("#info-gender").text(member.jenis_kelamin)
                $("#info-address").text(member.alamat)
                $("#info-rfid").text(member.rfid)
                $("#info-register").text(member.tgl_register)
                $("#info-expired").text(member.tgl_expired)

                $("#image-member").attr("src", member.image_profile)

                if (member.membership_id != 0) {
                    $("#info-membership").text(member.membership.name)
                } else {
                    $("#info-membership").text("-")
                }

                $("#qrcode").empty();

                qr = new QRCode(document.getElementById("qrcode"), {
                    text: member.qr_code,
                    width: 100,
                    height: 100,
                    colorDark: "#000000",
                    colorLight: "#ffffff",
                    correctLevel: QRCode.CorrectLevel.H
                });

                $("#btn-print-qr").attr("href", `/members/${member.id}/print-qr`)
            }
        })
    })

    $("#rfid").on('keypress', function(e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) {
            e.preventDefault();
            return false;
        }
    })

    $("#datatable").on('click', '.btn-membership', function() {
        let route = $(this).attr('data-route')
        let id = $(this).attr('id')

        $.ajax({
            url: "/members/" + id,
            type: 'GET',
            method: 'GET',
            success: function(response) {
                console.log(response)
                let member = response.member;

                $("#membership-name").val(member.nama)

                if (member.membership_id != 0) {
                    $("#membership-id").val(member.membership_id)
                } else {
                    $("#membership-id").val("")
                }
            }
        })
    });

    $('#membership-id').on('change', function() {

        var selectedOption = $(this).find('option:selected');
        var max_person = parseInt(selectedOption.data('max-person')) || 0;

        if (this.value) {
            // --- Loop untuk Anggota Grup ---
            $('.form-group.title-group').remove();
            $(".form-group.member-group").remove();

            for (var i = 1; i < max_person; i++) {
                // 2. PERBAIKAN DUPLIKAT ID
                // (Kode string HTML Anda di sini sudah benar)
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

                // 3. TAMBAHKAN TITLE HANYA SEKALI
                if (i === 1) {
                    // Cari elemen sebelum field membership, atau sesuaikan dengan struktur form Anda
                    $('#modal-dialog #form-member .modal-body').append(title);
                }
                // 4. INSERT FIELD SETELAH FIELD TERAKHIR
                $('#modal-dialog #form-member .modal-body').append(rfidGroupField);
            }
        } else {
            // Jika user memilih "-- Pilih --", kosongkan field
            $('#duration').val('');
            $('#price').val('');
            $('#tgl_expired').val('');
            $('.form-group.title-group').remove(); // Hapus title
            $(".form-group.member-group").remove(); // Hapus field
        }
    });
</script>
@endpush
