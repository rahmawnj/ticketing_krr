@extends('layouts.master')

@section('content')
<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="widget widget-stats bg-white text-inverse">
            <div class="stats-icon stats-icon-square bg-gradient-cyan-blue text-white"><i class="ion-ios-calculator"></i></div>
            <div class="stats-content">
                <div class="stats-title text-gray-700">Today's Transaction</div>
                <div class="stats-number">{{ $transaction }}</div>
                <div class="stats-progress progress">
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="widget widget-stats bg-white text-inverse">
            <div class="stats-icon stats-icon-square bg-gradient-cyan-blue text-white"><i class="ion-ios-pricetags"></i></div>
            <div class="stats-content">
                <div class="stats-title text-gray-700">Income Sewa</div>
                <div class="stats-number">{{ number_format($rental,0, ',','.') }}</div>
                <div class="stats-progress progress">
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="widget widget-stats bg-white text-inverse">
            <div class="stats-icon stats-icon-square bg-gradient-cyan-blue text-white"><i class="ion-ios-people"></i></div>
            <div class="stats-content">
                <div class="stats-title text-gray-700">Income Membership</div>
                <div class="stats-number">{{ number_format($membership,0, ',','.') }}</div>
                <div class="stats-progress progress">
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="widget widget-stats bg-white text-inverse">
            <div class="stats-icon stats-icon-square bg-gradient-cyan-blue text-white"><i class="ion-ios-bookmark"></i></div>
            <div class="stats-content">
                <div class="stats-title text-gray-700">Ticket</div>
                <div class="stats-number">{{ number_format($ticket,0, ',','.') }}</div>
                <div class="stats-progress progress">
                </div>
            </div>
        </div>
    </div>
    </div>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <canvas id="bar-chart"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection
@push('script')
<script src="{{ asset('/') }}plugins/chart.js/dist/chart.min.js"></script>
<script>
    const chartData = @json($chartData);
    var ctx2 = document.getElementById('bar-chart').getContext('2d');
    var barChart = new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: chartData.labels,
            datasets: [
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
            },
            {
                label: 'Membership (Register & Renewal)',
                borderWidth: 2,
                borderColor: '#6f42c1',
                backgroundColor: 'rgba(111, 66, 193, 0.5)',
                data: chartData.membership
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Rekap Transaksi 7 Hari Terakhir',
                    font: {
                        size: 16
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Amount'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Tanggal'
                    }
                }
            }
        }
    });
</script>
@endpush
