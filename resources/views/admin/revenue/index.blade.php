@extends('admin.layouts.app')

@section('title', 'Quản lý doanh thu')

@section('content')
<div class="row">
    <!-- Thống kê tổng quan -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Doanh thu hôm nay</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ number_format($dailyRevenue->first()->total ?? 0, 0, ',', '.') }} VNĐ
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Doanh thu tháng này</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ number_format($monthlyRevenue->first()->total ?? 0, 0, ',', '.') }} VNĐ
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Biểu đồ doanh thu -->
<div class="row">
    <div class="col-xl-8 col-lg-7">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Doanh thu theo ngày</h6>
            </div>
            <div class="card-body">
                <div class="chart-area">
                    <canvas id="dailyRevenueChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-lg-5">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Doanh thu theo tháng</h6>
            </div>
            <div class="card-body">
                <div class="chart-pie">
                    <canvas id="monthlyRevenueChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bảng doanh thu chi tiết -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Chi tiết doanh thu</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="revenueTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Ngày</th>
                        <th>Doanh thu</th>
                        <th>Số giao dịch</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dailyRevenue as $revenue)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($revenue->date)->format('d/m/Y') }}</td>
                        <td>{{ number_format($revenue->total, 0, ',', '.') }} VNĐ</td>
                        <td>{{ $revenue->transaction_count ?? 0 }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Dữ liệu cho biểu đồ doanh thu theo ngày
const dailyData = {
    labels: {!! json_encode($dailyRevenue->pluck('date')) !!},
    datasets: [{
        label: 'Doanh thu (VNĐ)',
        data: {!! json_encode($dailyRevenue->pluck('total')) !!},
        borderColor: 'rgb(75, 192, 192)',
        tension: 0.1
    }]
};

// Dữ liệu cho biểu đồ doanh thu theo tháng
const monthlyData = {
    labels: {!! json_encode($monthlyRevenue->map(function($item) {
        return \Carbon\Carbon::createFromDate($item->year, $item->month)->format('m/Y');
    })) !!},
    datasets: [{
        data: {!! json_encode($monthlyRevenue->pluck('total')) !!},
        backgroundColor: [
            'rgb(255, 99, 132)',
            'rgb(54, 162, 235)',
            'rgb(255, 205, 86)',
            'rgb(75, 192, 192)',
            'rgb(153, 102, 255)'
        ]
    }]
};

// Khởi tạo biểu đồ doanh thu theo ngày
new Chart(document.getElementById('dailyRevenueChart'), {
    type: 'line',
    data: dailyData,
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return new Intl.NumberFormat('vi-VN').format(value) + ' VNĐ';
                    }
                }
            }
        }
    }
});

// Khởi tạo biểu đồ doanh thu theo tháng
new Chart(document.getElementById('monthlyRevenueChart'), {
    type: 'doughnut',
    data: monthlyData,
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});
</script>
@endpush
@endsection 