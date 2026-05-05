@extends('layouts.master', ['title' => $title, 'breadcrumbs' => $breadcrumbs])

@push('style')
<link href="{{ asset('/') }}plugins/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet" />
<style>
    .detail-report-header {
        text-align: center;
        margin-bottom: 1.5rem;
    }

    .detail-report-header h5,
    .detail-report-header h6 {
        margin-bottom: 0.35rem;
    }

    .detail-report-table th,
    .detail-report-table td {
        vertical-align: middle;
        white-space: nowrap;
    }

    .detail-report-table tfoot th,
    .detail-report-table tfoot td {
        background-color: #f5f5f5;
        font-weight: 700;
    }
</style>
@endpush

@section('content')
@php
    $itemColumnCount = max(count($columns), 1);
    $summaryColumnCount = 3 + ($showAdminFee ? 1 : 0);
    $emptyColspan = 1 + $itemColumnCount + $summaryColumnCount;
@endphp
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
        <form action="" method="get" class="row mb-3">
            <div class="col-md-4">
                <label for="daterange">Tanggal</label>
                <input type="text" name="daterange" id="daterange" class="form-control"
                    value="{{ request('daterange') ?: \Carbon\Carbon::parse($from)->format('m/d/Y') . ' - ' . \Carbon\Carbon::parse($to)->format('m/d/Y') }}">
                <input type="hidden" name="from" id="from" value="{{ $from }}">
                <input type="hidden" name="to" id="to" value="{{ $to }}">
            </div>
            <div class="col-md-4">
                <label for="kasir">Admin/Kasir</label>
                <select name="kasir" id="kasir" class="form-control">
                    <option value="all" {{ ($kasir ?? 'all') === 'all' ? 'selected' : '' }}>All</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ (string) ($kasir ?? 'all') === (string) $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2">Submit</button>
                <a href="{{ route('reports.detail.export', ['scope' => $scopeMeta['key'], 'from' => $from, 'to' => $to, 'kasir' => $kasir]) }}" class="btn btn-success">
                    <i class="fas fa-file-excel me-1"></i>Download
                </a>
            </div>
        </form>

        <div class="detail-report-header">
            <h5>{{ $reportHeading }}</h5>
            <h6>{{ strtoupper($scopeMeta['title']) }}</h6>
            <div>PERIODE {{ \Carbon\Carbon::parse($from)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($to)->format('d/m/Y') }}</div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle detail-report-table">
                <thead>
                    <tr>
                        <th rowspan="2" style="min-width: 140px;">Tanggal</th>
                        <th colspan="{{ $itemColumnCount }}" class="text-center">{{ strtoupper($scopeMeta['group_label']) }}</th>
                        <th rowspan="2" class="text-end">Total</th>
                        <th rowspan="2" class="text-end">DPP</th>
                        <th rowspan="2" class="text-end">PBJT</th>
                        @if($showAdminFee)
                            <th rowspan="2" class="text-end">Biaya Admin</th>
                        @endif
                    </tr>
                    <tr>
                        @forelse($columns as $column)
                            <th class="text-end">{{ $column['label'] }}</th>
                        @empty
                            <th class="text-center text-muted">-</th>
                        @endforelse
                    </tr>
                </thead>
                <tbody>
                    @forelse($rows as $row)
                        <tr>
                            <td>{{ $row['tanggal'] }}</td>
                            @forelse($columns as $column)
                                <td class="text-end">{{ number_format($row['items'][$column['key']] ?? 0, 0, ',', '.') }}</td>
                            @empty
                                <td class="text-center text-muted">-</td>
                            @endforelse
                            <td class="text-end">{{ number_format($row['total'], 0, ',', '.') }}</td>
                            <td class="text-end">{{ number_format($row['dpp'], 0, ',', '.') }}</td>
                            <td class="text-end">{{ number_format($row['ppn'], 0, ',', '.') }}</td>
                            @if($showAdminFee)
                                <td class="text-end">{{ number_format($row['admin_fee'], 0, ',', '.') }}</td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $emptyColspan }}" class="text-center text-muted">Tidak ada data transaksi pada rentang tanggal ini.</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <th>TOTAL</th>
                        @forelse($columns as $column)
                            <th class="text-end">{{ number_format($footer['items'][$column['key']] ?? 0, 0, ',', '.') }}</th>
                        @empty
                            <th class="text-center">-</th>
                        @endforelse
                        <th class="text-end">{{ number_format($footer['total'], 0, ',', '.') }}</th>
                        <th class="text-end">{{ number_format($footer['dpp'], 0, ',', '.') }}</th>
                        <th class="text-end">{{ number_format($footer['ppn'], 0, ',', '.') }}</th>
                        @if($showAdminFee)
                            <th class="text-end">{{ number_format($footer['admin_fee'], 0, ',', '.') }}</th>
                        @endif
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection

@push('script')
<script src="{{ asset('/') }}plugins/moment/min/moment.min.js"></script>
<script src="{{ asset('/') }}plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
<script>
    function syncDateRangeFields() {
        const value = $("#daterange").val() || '';
        if (!value.includes(' - ')) return;

        const parts = value.split(' - ');
        const start = moment(parts[0], 'MM/DD/YYYY', true);
        const end = moment(parts[1], 'MM/DD/YYYY', true);
        if (!start.isValid() || !end.isValid()) return;

        $("#from").val(start.format('YYYY-MM-DD'));
        $("#to").val(end.format('YYYY-MM-DD'));
    }

    (function initDateRange() {
        const start = moment("{{ \Carbon\Carbon::parse($from)->format('Y-m-d') }}", 'YYYY-MM-DD', true);
        const end = moment("{{ \Carbon\Carbon::parse($to)->format('Y-m-d') }}", 'YYYY-MM-DD', true);

        $("#daterange").daterangepicker({
            opens: "right",
            autoUpdateInput: true,
            startDate: start,
            endDate: end,
            locale: {
                format: "MM/DD/YYYY",
                separator: " - "
            }
        });
    })();

    $("form").on('submit', function() {
        syncDateRangeFields();
    });
</script>
@endpush
