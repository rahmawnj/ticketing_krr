@extends('layouts.master', ['title' => $title, 'breadcrumbs' => $breadcrumbs])

@push('style')
<link href="{{ asset('/') }}plugins/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
<link href="{{ asset('/') }}plugins/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" />
<link href="{{ asset('/') }}plugins/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet" />
<style>
    #datatable tbody img {
        cursor: zoom-in;
        transition: transform 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }
    #datatable tbody img:hover {
        transform: scale(1.03);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
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
    <form action="" class="row mb-3">
    {{-- Kolom Kiri: Input Tanggal + Submit --}}
    <div class="col-md-6 d-flex align-items-end">
        <div class="form-group me-3">
            <label for="daterange">Tanggal</label>
            <input type="text" name="daterange" id="daterange" class="form-control"
                   value="{{ request('daterange') ?: now('Asia/Jakarta')->format('m/d/Y') . ' - ' . now('Asia/Jakarta')->format('m/d/Y') }}">
        </div>
        <div class="form-group me-3">
            <label for="transaction_type">Transaction Type</label>
            <select name="transaction_type" id="transaction_type" class="form-control">
                <option value="">All</option>
                @php
                    $types = ['renewal', 'ticket', 'registration', 'rental'];
                @endphp
                @foreach($types as $type)
                    <option value="{{ $type }}" {{ request('transaction_type') == $type ? 'selected' : '' }}>
                        {{ ucfirst($type) }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-success mt-3">Submit</button>
        </div>
        <div class="form-group ms-2">
            <a href="#" id="btn-export-excel" class="btn btn-outline-success mt-3">
                <i class="fas fa-file-excel"></i> Export Excel
            </a>
        </div>
    </div>

    {{-- Kolom Kanan: Tiga Tombol Link Aksi --}}
  <div class="col-md-6 d-flex justify-content-end align-items-end">
    {{-- 1. Registrasi Member (Warna: Success / Hijau, Ikon: User Plus) --}}
    <a href="{{ route('members.create') }}" class="btn btn-success mt-3 ms-2">
        <i class="fas fa-user-plus"></i> Registrasi Member
    </a>

    {{-- 2. Input Transaksi Lainnya (Warna: Secondary / Abu-abu, Ikon: Shopping Cart) --}}
    <a href="{{ route('penyewaan.create') }}" class="btn btn-secondary mt-3 ms-2">
        <i class="fas fa-shopping-cart"></i> Input Transaksi Lainnya
    </a>

    {{-- 3. Input Ticket (Warna: Primary / Biru, Ikon: Ticket Alt) --}}
    <a href="{{ route('transactions.create') }}" class="btn btn-primary mt-3 ms-2">
        <i class="fas fa-ticket-alt"></i> Input Ticket
    </a>

    {{-- 4. Renewal (Warna: Warning / Kuning, Ikon: History) --}}
    <a href="{{ route('members.bulk_renew') }}" class="btn btn-warning mt-3 ms-2">
        <i class="fas fa-history"></i> Renewal
        <span class="badge bg-dark ms-1">{{ $renewalCount ?? 0 }}</span>
    </a>
</div>
</form>

        <table id="datatable" class="table table-striped table-bordered align-middle">
            <thead>
                <tr>
                    <th class="text-nowrap">Tanggal</th>
                    <th class="text-nowrap">No</th>
                    <!-- <th class="text-nowrap">No Trx</th> -->
                    <th class="text-nowrap">Invoice</th>
                    <th class="text-nowrap">Dibuat Oleh (Kasir)</th>
                    <th class="text-nowrap">Data Member</th>
                    <!-- <th class="text-nowrap">Ticket</th> -->
                    <!-- <th class="text-nowrap">Harga</th> -->
<th class="text-nowrap">Transaction Type</th>
                    <th class="text-nowrap">Scanned</th>
                    <th class="text-nowrap">Bayar</th>
                    <th class="text-nowrap">PBJT</th>
                    <th class="text-nowrap">Discount</th>
                    <th class="text-nowrap">Total</th>
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
                    <h4 class="modal-title">Form Transaction</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <form action="" method="post" id="form-transaction">
                    @csrf

                    <div class="modal-body row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="no_trx">No Transaksi</label>
                                <input type="number" name="no_trx" id="no_trx" class="form-control" readonly value="">

                                @error('no_trx')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="name">Name</label>
                                <input type="text" name="name" id="name" class="form-control" value="">

                                @error('name')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="ticket">Ticket</label>
                                <select name="ticket" id="ticket" class="form-control">
                                    <option disabled selected>-- Select Ticket --</option>
                                    @foreach($tickets as $ticket)
                                    <option value="{{ $ticket->id }}" data-harga="{{ $ticket->harga }}">{{ $ticket->name }}</option>
                                    @endforeach
                                </select>

                                @error('ticket')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="amount">Jumlah</label>
                                <input type="number" name="amount" id="amount" class="form-control" value="1">

                                @error('amount')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="print">Jumlah Print</label>
                                <input type="number" name="print" id="print" class="form-control" readonly value="1">

                                @error('print')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="harga_ticket">Harga Tiket</label>
                                <input type="number" name="harga_ticket" id="harga_ticket" class="form-control" value="" readonly>

                                @error('harga_ticket')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="discount">Discount</label>
                                <input type="number" name="discount" id="discount" class="form-control" value="0">

                                @error('discount')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="metode">Metode</label>
                                <select name="metode" id="metode" class="form-control">
                                    <option value="cash">Cash</option>
                                    <option value="debit">Debit</option>
                                    <option value="qris">QRIS</option>
                                    <option value="kredit">Kredit</option>
                                    <option value="transfer">Transfer</option>
                                    <option value="lain-lain">Lain-lain</option>
                                </select>

                                @error('metode')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="cash">Cash</label>
                                <input type="number" name="cash" id="cash" class="form-control" value="">

                                @error('cash')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="kembalian">Kembalian</label>
                                <input type="number" name="kembalian" id="kembalian" class="form-control" value="0" readonly>

                                @error('kembalian')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group mb-3">
                                <label for="jumlah">Jumlah</label>
                                <input type="number" name="jumlah" id="jumlah" class="form-control" value="0" readonly>

                                @error('jumlah')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <a href="javascript:;" id="btn-close" class="btn btn-white" data-bs-dismiss="modal">Close</a>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <form action="" class="d-none" id="form-delete" method="post">
        @csrf
        @method('DELETE')
    </form>
</div>


<div class="modal fade" id="modal-full-scan">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Konfirmasi Selesaikan Scan</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <form action="" method="post" id="form-full-scan">
                @csrf
                <div class="modal-body">
                    <p>Anda yakin ingin menandai transaksi <strong id="trx-code-placeholder"></strong> sebagai **Full Scanned**?</p>
                    <p class="text-danger">Aksi ini akan mengatur jumlah scan menjadi sama dengan total kuantitas tiket.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Ya, Selesaikan Scan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-photo" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Foto</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modal-photo-img" src="" alt="Foto" class="img-fluid rounded">
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script src="{{ asset('/') }}plugins/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="{{ asset('/') }}plugins/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="{{ asset('/') }}plugins/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="{{ asset('/') }}plugins/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>
<script src="{{ asset('/') }}plugins/sweetalert/dist/sweetalert.min.js"></script>
<script src="{{ asset('/') }}plugins/moment/min/moment.min.js"></script>
<script src="{{ asset('/') }}plugins/bootstrap-daterangepicker/daterangepicker.js"></script>

<script>
    let daterange = $("#daterange").val();
    let transactionType = $("#transaction_type").val();

    var table = $('#datatable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: "{{ route('transactions.list') }}",
            type: "GET",
            data: {
                "daterange": daterange,
                "transaction_type": transactionType,
            }
        },
        deferRender: true,
        pagination: true,
        columns: [{
                data: 'tanggal',
                name: 'tanggal'
            },
            {
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                sortable: false,
                searchable: false
            },
            {
                data: 'ticket_code',
                name: 'ticket_code'
            },
            {
        data: 'user_name',
        name: 'user_name' // Harus sesuai dengan nama kolom di Controller
    },
            {
                data: 'member_info',
                name: 'member_info'
            },
           {
                data: 'transaction_type',
                name: 'transaction_type',
                render: function(data, type, row) {
                    return data.charAt(0).toUpperCase() + data.slice(1);
                }
            },
            {
                data: 'scanned',
                name: 'scanned',
                sortable: false,
                searchable: false
            },
            {
                data: 'bayar',
                name: 'bayar'
            },

            {
                data: 'ppn',
                name: 'ppn'
            },
            {
                data: 'discount',
                name: 'discount'
            },
            {
                data: 'harga_ticket',
                name: 'harga_ticket'
            },
            {
                data: 'status_ticket',
                name: 'status_ticket'
            },
            {
                data: 'action',
                name: 'action',
            },
        ]
    });

    (function initDateRange() {
        let today = moment();
        let rangeValue = $("#daterange").val();
        let start = today.clone();
        let end = today.clone();

        if (rangeValue && rangeValue.includes(' - ')) {
            let parts = rangeValue.split(' - ');
            let parsedStart = moment(parts[0], 'MM/DD/YYYY', true);
            let parsedEnd = moment(parts[1], 'MM/DD/YYYY', true);
            if (parsedStart.isValid() && parsedEnd.isValid()) {
                start = parsedStart;
                end = parsedEnd;
            }
        }

        $("#daterange").daterangepicker({
            opens: "right",
            autoUpdateInput: true,
            locale: {
                format: "MM/DD/YYYY",
                separator: " - "
            },
            startDate: start,
            endDate: end
        });

        $("#daterange").val(start.format('MM/DD/YYYY') + ' - ' + end.format('MM/DD/YYYY'));
    })();

    $("#btn-add").on('click', function() {
        let route = $(this).attr('data-route')
        $("#form-transaction").attr('action', route)
    })

    $("#btn-close").on('click', function() {
        $("#form-transaction").removeAttr('action')
    })

    $("#datatable").on('click', '.btn-edit', function() {
        let route = $(this).attr('data-route')
        let id = $(this).attr('id')

        $("#form-transaction").attr('action', route)
        $("#form-transaction").append(`<input type="hidden" name="_method" value="PUT">`);

        $.ajax({
            url: "/tickets/" + id,
            type: 'GET',
            method: 'GET',
            success: function(response) {
                let ticket = response.ticket;

                $("#name").val(ticket.name)
                $("#harga").val(ticket.harga)
            }
        })
    })

    $("#datatable").on('click', '.btn-delete', function(e) {
        e.preventDefault();
        let route = $(this).attr('data-route')
        $("#form-delete").attr('action', route)

        swal({
            title: 'Hapus data transaction?',
            text: 'Menghapus transaction bersifat permanen.',
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
</script>

<script>
    $(document).ready(function() {
        $("#btn-add").on('click', function() {
            $.ajax({
                url: '/api/transactions/no-trx',
                type: "GET",
                method: "GET",
                success: function(response) {
                    $("#no_trx").val(response.no_trx)
                }
            })

            $("#name").attr("autofocus", "autofocus")
        })

        $("#ticket").on('change', function() {
            let element = $(this).find('option:selected');
            let harga = element.attr("data-harga");
            let amount = $("#amount").val();
            let discount = $("#discount").val()
            let harga_ticket = harga * amount;
            let jumlah = (harga * amount) - discount;

            $("#harga_ticket").val(harga_ticket)
            $("#jumlah").val(jumlah)
            $("#cash").val(jumlah)
        })

        $("#amount").on('change', function() {
            let amount = $(this).val();
            let harga = $('#ticket option:selected').attr('data-harga');
            let type = $('#type_customer option:selected').val();
            let discount = $("#discount").val()
            let harga_ticket = harga * amount;
            $("#print").val(amount)

            // if (type == 'group') {
            let jumlah = (harga * amount) - discount;
            $("#harga_ticket").val(harga_ticket)
            $("#jumlah").val(jumlah)
            $("#cash").val(jumlah)
            // } else {
            //     let jumlah = harga - discount;
            //     $("#harga_ticket").val(harga)
            //     $("#jumlah").val(jumlah)
            //     $("#cash").val(jumlah)
            // }

        })

        $("#discount").on('change', function() {
            let discount = $(this).val();
            let harga = $("#harga_ticket").val();
            let jumlah = harga - discount;

            $("#jumlah").val("")
            $("#jumlah").val(jumlah)
            $("#cash").val(jumlah)
        })

        $("#type_customer").on('change', function() {
            let type = $(this).val();

            // if (type == 'group') {
            //     $("#print").removeAttr('readonly')
            // } else {
            //     $("#amount").val(1)
            //     $("#print").val(1)
            //     $("#print").attr('readonly', 'readonly')
            // }
        })

        $("#metode").on('change', function() {
            let metode = $(this).val();

            if (metode != 'cash') {
                $("#cash").val(0);
                $("#cash").attr('readonly', 'readonly')
            } else {
                $("#cash").removeAttr('readonly')
            }
        })
    })

    $("#datatable").on('click', '.btn-full-scan', function(e) {
        e.preventDefault();
        let route = $(this).attr('data-route');
        let ticketCode = $(this).attr('data-ticket-code');

        // Set action form dan placeholder
        $("#form-full-scan").attr('action', route);
        $("#form-full-scan").append(`<input type="hidden" name="_method" value="POST">`); // Asumsi Anda menggunakan POST/PUT di route controller
        $("#trx-code-placeholder").text(ticketCode);

        // Tampilkan modal
        $('#modal-full-scan').modal('show');
    });

    $("#datatable").on('click', 'tbody img', function() {
        let src = $(this).attr('data-full') || $(this).attr('src');
        if (!src) return;

        $("#modal-photo-img").attr('src', src);
        $('#modal-photo').modal('show');
    });

    $("#btn-export-excel").on('click', function(e) {
        e.preventDefault();
        const params = new URLSearchParams({
            daterange: $("#daterange").val() || "",
            transaction_type: $("#transaction_type").val() || ""
        });
        window.location.href = "{{ route('transactions.export.daily') }}" + "?" + params.toString();
    });
</script>
@endpush
