@extends('layouts.master', ['title' => $title, 'breadcrumbs' => $breadcrumbs])

@push('style')
<link href="{{ asset('/') }}plugins/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
@endpush

@section('content')
<div class="panel panel-inverse">
    <div class="panel-heading">
        <h4 class="panel-title">{{ $title }}</h4>
    </div>

    <div class="panel-body">
        <div class="row mb-3">
            <div class="col-md-3">
                <label for="renew-metode" class="form-label">Metode Pembayaran Renewal</label>
                <select id="renew-metode" class="form-control">
                    <option value="cash" selected>Cash</option>
                    <option value="debit">Debit</option>
                    <option value="kredit">Kredit</option>
                    <option value="qris">QRIS</option>
                    <option value="transfer">Transfer</option>
                    <option value="tap">RFID / Tap</option>
                    <option value="lain-lain">Lain-lain</option>
                </select>
            </div>
        </div>

        <table id="datatable-renew" class="table table-striped table-bordered align-middle">
            <thead>
                <tr>
                    <th class="text-nowrap">No</th>
                    <th class="text-nowrap">Nama</th>
                    <th class="text-nowrap">Membership</th>
                    <th class="text-nowrap">Harga Perpanjangan</th> {{-- KOLOM BARU --}}
                    <th class="text-nowrap">Tgl. Expired Lama</th>
                    <th class="text-nowrap">Status</th>
                    <th class="text-nowrap">Aksi</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

{{-- Form tersembunyi untuk mengirim request perpanjangan --}}
<form id="form-single-renew" method="POST" action="{{ route('members.process_bulk_renew') }}" style="display: none;">
    @csrf
    <input type="hidden" name="member_ids[]" id="member-id-input">
    <input type="hidden" name="metode" id="metode-input" value="cash">
</form>

@endsection

@push('script')
<script src="{{ asset('/') }}plugins/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="{{ asset('/') }}plugins/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="{{ asset('/') }}plugins/sweetalert/dist/sweetalert.min.js"></script>

<script>
    var table = $('#datatable-renew').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: "{{ route('members.get_renewable') }}",
        },
        deferRender: true,
        pagination: true,
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'nama', name: 'nama' },
            { data: 'membership.name', name: 'membership.name' },
            { data: 'gross_price', name: 'gross_price' }, // DATA BARU
            { data: 'tgl_expired', name: 'tgl_expired' },
            { data: 'renewal_status', name: 'renewal_status', orderable: false, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ]
    });

    // --- Logika Perpanjangan Individual ---

    $('#datatable-renew').on('click', '.btn-renew-single', function(e) {
        e.preventDefault();
        var memberId = $(this).data('id');
        var memberName = $(this).data('name');
        var memberPrice = $(this).data('price'); // AMBIL DATA HARGA

        swal({
            title: "Konfirmasi Perpanjangan",
            text: "Anda yakin ingin memperpanjang keanggotaan " + memberName +
                  "(beserta submember-nya)?\n\nBiaya Perpanjangan: Rp " + memberPrice + "", // TAMPILKAN HARGA
            icon: "warning",
            buttons: ["Batal", "Ya, Perpanjang!"],
            dangerMode: true,
        })
        .then((willRenew) => {
            if (willRenew) {
                // Set ID member ke form tersembunyi
                $('#member-id-input').val(memberId);
                $('#metode-input').val($('#renew-metode').val() || 'cash');

                // Submit form
                $('#form-single-renew').submit();
            }
        });
    });

</script>
@endpush
