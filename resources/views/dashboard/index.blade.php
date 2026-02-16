@extends('layouts.master')

@push('style')
<style>
    .dashboard-kpi-card {
        position: relative;
        overflow: hidden;
        border: 0;
        border-radius: 16px;
        box-shadow: 0 10px 24px rgba(20, 33, 61, 0.08);
        transition: transform .2s ease, box-shadow .2s ease;
        background: #fff;
    }
    .dashboard-kpi-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 14px 30px rgba(20, 33, 61, 0.14);
    }
    .dashboard-kpi-card::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        height: 4px;
    }
    .dashboard-kpi-card::after {
        content: '';
        position: absolute;
        right: -30px;
        bottom: -35px;
        width: 120px;
        height: 120px;
        border-radius: 50%;
        opacity: .08;
    }
    .kpi-renewal::before,
    .kpi-renewal .stats-icon { background: linear-gradient(135deg, #1590ff, #3e47ff); }
    .kpi-renewal::after { background: #1590ff; }
    .kpi-member::before,
    .kpi-member .stats-icon { background: linear-gradient(135deg, #ff8b1f, #ff4f6d); }
    .kpi-member::after { background: #ff8b1f; }
    .kpi-rental::before,
    .kpi-rental .stats-icon { background: linear-gradient(135deg, #00a86b, #11c9a5); }
    .kpi-rental::after { background: #00a86b; }
    .kpi-ticket::before,
    .kpi-ticket .stats-icon { background: linear-gradient(135deg, #7c4dff, #4f7bff); }
    .kpi-ticket::after { background: #7c4dff; }
    .dashboard-kpi-card .stats-content {
        padding-right: 86px;
    }
    .dashboard-kpi-card .stats-icon {
        width: 58px;
        height: 58px;
        line-height: 58px;
        border-radius: 14px;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
        top: 16px;
        right: 16px;
    }
    .dashboard-kpi-card .stats-title {
        font-size: 13px;
        font-weight: 700;
        letter-spacing: .4px;
        text-transform: uppercase;
        color: #5a6672 !important;
        margin-bottom: 8px;
    }
    .dashboard-kpi-card .stats-period {
        display: inline-block;
        margin-left: 6px;
        font-size: 10px;
        font-weight: 700;
        text-transform: none;
        letter-spacing: .2px;
        color: #24538a;
        background: #e9f3ff;
        border: 1px solid #cfe3ff;
        border-radius: 999px;
        padding: 2px 8px;
        vertical-align: middle;
    }
    .dashboard-kpi-card .stats-number {
        font-size: 30px;
        font-weight: 800;
        line-height: 1.1;
        color: #1d2734;
        margin-bottom: 12px;
    }
    .dashboard-kpi-card .stats-progress {
        margin: 0 0 12px;
        height: 2px;
        background: rgba(28, 44, 64, 0.08);
    }
    .dashboard-kpi-card .stats-count {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 12px;
        font-weight: 700;
        color: #314257;
        background: #eef4ff;
        border: 1px solid #d9e7ff;
        border-radius: 999px;
        padding: 5px 11px;
    }
    @media (max-width: 767px) {
        .dashboard-kpi-card .stats-content {
            padding-right: 0;
        }
        .dashboard-kpi-card .stats-icon {
            position: relative;
            top: 0;
            right: 0;
            margin-bottom: 12px;
        }
        .dashboard-kpi-card .stats-number {
            font-size: 26px;
        }
    }
    .dashboard-chart-card {
        border: 0;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 12px 28px rgba(18, 34, 61, 0.10);
        background: #fff;
    }
    .dashboard-chart-head {
        padding: 18px 22px 14px;
        border-bottom: 1px solid #edf1f7;
        background: linear-gradient(135deg, #f7fbff 0%, #ffffff 65%);
    }
    .dashboard-chart-topbar {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
    }
    .dashboard-chart-title {
        margin: 0;
        font-size: 18px;
        font-weight: 800;
        color: #1d2a3a;
    }
    .dashboard-chart-subtitle {
        margin: 4px 0 0;
        font-size: 12px;
        color: #6c7b8a;
        font-weight: 600;
        letter-spacing: .2px;
    }
    .dashboard-chart-date {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 12px;
        font-weight: 700;
        color: #275287;
        background: #eaf3ff;
        border: 1px solid #cfe3ff;
        border-radius: 999px;
        padding: 5px 9px;
        white-space: nowrap;
    }
    .dashboard-date-input {
        border: 0;
        background: #fff;
        color: #1f3e66;
        font-size: 12px;
        font-weight: 700;
        border-radius: 999px;
        padding: 4px 8px;
        min-width: 138px;
        outline: none;
    }
    .dashboard-date-input:focus {
        box-shadow: 0 0 0 2px rgba(47, 124, 255, 0.2);
    }
    .dashboard-date-label {
        font-size: 12px;
        color: #4b6380;
        font-weight: 700;
        margin-top: 6px;
        text-align: right;
    }
    .dashboard-chart-body {
        padding: 16px 18px 20px;
    }
    .chart-wrap {
        position: relative;
        height: 360px;
        border: 1px solid #eef2f8;
        border-radius: 14px;
        background:
            radial-gradient(circle at 100% 0, rgba(63, 136, 255, 0.08), transparent 45%),
            linear-gradient(180deg, #ffffff 0%, #fbfdff 100%);
        padding: 12px;
    }
    @media (max-width: 767px) {
        .dashboard-chart-head {
            padding: 14px 16px 12px;
        }
        .dashboard-chart-title {
            font-size: 15px;
        }
        .dashboard-chart-topbar {
            flex-direction: column;
            align-items: flex-start;
        }
        .dashboard-chart-body {
            padding: 12px;
        }
        .chart-wrap {
            height: 300px;
            padding: 8px;
        }
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="widget widget-stats text-inverse dashboard-kpi-card kpi-renewal">
            <div class="stats-icon stats-icon-square text-white"><i class="ion-ios-calculator"></i></div>
            <div class="stats-content">
                <div class="stats-title text-gray-700">Renewal <span class="stats-period">{{ $periodBadge }}</span></div>
                @if($dashboardMetricMode === 'count')
                <div class="stats-number">{{ number_format($renewalCount,0, ',','.') }}</div>
                @else
                <div class="stats-number">Rp {{ number_format($renewal,0, ',','.') }}</div>
                @endif
                <div class="stats-progress progress">
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="widget widget-stats text-inverse dashboard-kpi-card kpi-member">
            <div class="stats-icon stats-icon-square text-white"><i class="ion-ios-pricetags"></i></div>
            <div class="stats-content">
                <div class="stats-title text-gray-700">New Member <span class="stats-period">{{ $periodBadge }}</span></div>
                @if($dashboardMetricMode === 'count')
                <div class="stats-number">{{ number_format($newMemberCount,0, ',','.') }}</div>
                @else
                <div class="stats-number">Rp {{ number_format($newMember,0, ',','.') }}</div>
                @endif
                <div class="stats-progress progress">
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="widget widget-stats text-inverse dashboard-kpi-card kpi-rental">
            <div class="stats-icon stats-icon-square text-white"><i class="ion-ios-people"></i></div>
            <div class="stats-content">
                <div class="stats-title text-gray-700">Rental <span class="stats-period">{{ $periodBadge }}</span></div>
                @if($dashboardMetricMode === 'count')
                <div class="stats-number">{{ number_format($rentalCount,0, ',','.') }}</div>
                @else
                <div class="stats-number">Rp {{ number_format($rental,0, ',','.') }}</div>
                @endif
                <div class="stats-progress progress">
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="widget widget-stats text-inverse dashboard-kpi-card kpi-ticket">
            <div class="stats-icon stats-icon-square text-white"><i class="ion-ios-bookmark"></i></div>
            <div class="stats-content">
                <div class="stats-title text-gray-700">Ticket <span class="stats-period">{{ $periodBadge }}</span></div>
                @if($dashboardMetricMode === 'count')
                <div class="stats-number">{{ number_format($ticketCount,0, ',','.') }}</div>
                @else
                <div class="stats-number">Rp {{ number_format($ticket,0, ',','.') }}</div>
                @endif
                <div class="stats-progress progress">
                </div>
            </div>
        </div>
    </div>
    </div>
<div class="row">
    <div class="col-xl-12">
        <div class="card dashboard-chart-card">
            <div class="dashboard-chart-head">
                <div class="dashboard-chart-topbar">
                    <div>
                        <h4 class="dashboard-chart-title">{{ $chartTitle }}</h4>
                        <p class="dashboard-chart-subtitle">{{ $chartSubtitle }}</p>
                    </div>
                    <div>
                        <form method="GET" class="dashboard-chart-date">
                            <span>Tanggal</span>
                            <input type="date" name="date" value="{{ $selectedDateYmd }}" class="dashboard-date-input" onchange="this.form.submit()">
                        </form>
                        <div class="dashboard-date-label">{{ $todayLabel }}</div>
                    </div>
                </div>
            </div>
            <div class="dashboard-chart-body">
                <div class="chart-wrap">
                    <canvas id="bar-chart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('script')
<script src="{{ asset('/') }}plugins/chart.js/dist/chart.min.js"></script>
<script>
    const chartData = @json($chartData);
    const dashboardMetricMode = @json($dashboardMetricMode);
    const isCountMode = dashboardMetricMode === 'count';
    const formatNumberID = (value) => new Intl.NumberFormat('id-ID').format(Number(value || 0));
    var ctx2 = document.getElementById('bar-chart').getContext('2d');
    var barChart = new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: chartData.labels,
            datasets: [
            {
                label: 'Renewal',
                borderWidth: 2,
                borderColor: '#17a2b8',
                backgroundColor: 'rgba(23, 162, 184, 0.5)',
                data: chartData.renewal
            },
            {
                label: 'New Member',
                borderWidth: 2,
                borderColor: '#fd7e14',
                backgroundColor: 'rgba(253, 126, 20, 0.5)',
                data: chartData.new_member
            },
            {
                label: 'Ticket',
                borderWidth: 2,
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.5)',
                data: chartData.ticket
            },
            {
                label: 'Penyewaan',
                borderWidth: 2,
                borderColor: '#28a745',
                backgroundColor: 'rgba(40, 167, 69, 0.5)',
                data: chartData.rental
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        pointStyle: 'rectRounded',
                        boxWidth: 12,
                        boxHeight: 12,
                        padding: 14,
                        color: '#425466',
                        font: {
                            size: 12,
                            weight: '600'
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const val = context.parsed?.y ?? 0;
                            return isCountMode
                                ? context.dataset.label + ': ' + formatNumberID(val)
                                : context.dataset.label + ': Rp ' + formatNumberID(val);
                        }
                    }
                },
                title: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        color: '#5f7085',
                        callback: function(value) {
                            return isCountMode
                                ? formatNumberID(value)
                                : 'Rp ' + formatNumberID(value);
                        }
                    },
                    grid: {
                        color: 'rgba(125, 141, 163, 0.18)',
                        drawBorder: false
                    },
                    title: {
                        display: true,
                        text: isCountMode ? 'Jumlah Transaksi' : 'Amount (Rp)',
                        color: '#506175',
                        font: {
                            weight: '700'
                        }
                    }
                },
                x: {
                    ticks: {
                        color: '#5f7085'
                    },
                    grid: {
                        color: 'rgba(125, 141, 163, 0.12)',
                        drawBorder: false
                    },
                    title: {
                        display: true,
                        text: 'Jam',
                        color: '#506175',
                        font: {
                            weight: '700'
                        }
                    }
                }
            }
        }
    });
</script>
@endpush
