@extends('layouts.admin')

@section('title', 'Doanh thu')

@section('page-title', 'Thống kê doanh thu')

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
@endsection

@section('content')
<div class="row">
    <!-- Tổng quan doanh thu -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Tổng doanh thu</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="total-revenue">
                            {{ number_format($revenue['total']) }} VNĐ
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Doanh thu từ thuốc -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Doanh thu từ thuốc</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="medicine-revenue">
                            {{ number_format($revenue['medicines']) }} VNĐ
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-pills fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Doanh thu từ trị liệu -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Doanh thu từ trị liệu</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="treatment-revenue">
                            {{ number_format($revenue['treatments']) }} VNĐ
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-procedures fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bộ lọc thời gian -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Bộ lọc</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label>Khoảng thời gian</label>
                    <input type="text" class="form-control" id="daterange" name="daterange">
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Biểu đồ doanh thu -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Biểu đồ doanh thu</h6>
    </div>
    <div class="card-body">
        <canvas id="revenueChart"></canvas>
    </div>
</div>

<!-- Chi tiết doanh thu -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Chi tiết doanh thu</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="revenueTable">
                <thead>
                    <tr>
                        <th>Ngày</th>
                        <th>Loại</th>
                        <th>Mô tả</th>
                        <th>Số lượng</th>
                        <th>Đơn giá</th>
                        <th>Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($revenue['details'] as $detail)
                    <tr>
                        <td>{{ $detail['date'] }}</td>
                        <td>{{ $detail['type'] }}</td>
                        <td>{{ $detail['description'] }}</td>
                        <td>{{ $detail['quantity'] }}</td>
                        <td>{{ number_format($detail['unit_price']) }} VNĐ</td>
                        <td>{{ number_format($detail['total']) }} VNĐ</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
$(function() {
    // Khởi tạo date range picker
    $('input[name="daterange"]').daterangepicker({
        startDate: moment().startOf('month'),
        endDate: moment(),
        ranges: {
           'Hôm nay': [moment(), moment()],
           'Hôm qua': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           '7 ngày qua': [moment().subtract(6, 'days'), moment()],
           '30 ngày qua': [moment().subtract(29, 'days'), moment()],
           'Tháng này': [moment().startOf('month'), moment().endOf('month')],
           'Tháng trước': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        locale: {
            format: 'DD/MM/YYYY',
            applyLabel: 'Áp dụng',
            cancelLabel: 'Hủy',
            customRangeLabel: 'Tùy chọn'
        }
    }, function(start, end, label) {
        // Gọi API để lấy dữ liệu mới
        fetchRevenueData(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
    });

    // Khởi tạo biểu đồ
    const ctx = document.getElementById('revenueChart').getContext('2d');
    const revenueChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [], // Sẽ được cập nhật từ API
            datasets: [{
                label: 'Doanh thu',
                data: [], // Sẽ được cập nhật từ API
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Hàm lấy dữ liệu doanh thu
    function fetchRevenueData(startDate, endDate) {
        fetch(`/admin/revenue/data?start_date=${startDate}&end_date=${endDate}`)
            .then(response => response.json())
            .then(data => {
                // Cập nhật các thẻ hiển thị
                document.getElementById('total-revenue').textContent = 
                    new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' })
                    .format(data.total);
                document.getElementById('medicine-revenue').textContent = 
                    new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' })
                    .format(data.medicines);
                document.getElementById('treatment-revenue').textContent = 
                    new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' })
                    .format(data.treatments);

                // Cập nhật biểu đồ
                revenueChart.data.labels = data.chart.labels;
                revenueChart.data.datasets[0].data = data.chart.data;
                revenueChart.update();

                // Cập nhật bảng chi tiết
                const tbody = document.querySelector('#revenueTable tbody');
                tbody.innerHTML = data.details.map(detail => `
                    <tr>
                        <td>${detail.date}</td>
                        <td>${detail.type}</td>
                        <td>${detail.description}</td>
                        <td>${detail.quantity}</td>
                        <td>${new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' })
                            .format(detail.unit_price)}</td>
                        <td>${new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' })
                            .format(detail.total)}</td>
                    </tr>
                `).join('');
            });
    }
});
</script>
@endsection 