@extends('layouts.pharmacist')

@section('title', 'Dashboard')

@section('page-title', 'Tổng quan')

@section('content')
<!-- Content Row -->
<div class="row">
    <!-- Đơn thuốc chờ xử lý -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Đơn thuốc chờ xử lý</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pendingPrescriptions }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sản phẩm sắp hết -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Sản phẩm sắp hết</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $lowStockItems }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Đơn hàng hôm nay -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Đơn hàng hôm nay</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $todayOrders }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Đơn đổi trả chờ xử lý -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                            Đơn đổi trả chờ xử lý</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pendingReturns }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-exchange-alt fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Content Row -->
<div class="row">
    <!-- Đơn thuốc gần đây -->
    <div class="col-lg-6 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Đơn thuốc gần đây</h6>
                <a href="{{ route('pharmacist.prescriptions.pending') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-arrow-right"></i> Xem tất cả
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Bệnh nhân</th>
                                <th>Bác sĩ</th>
                                <th>Trạng thái</th>
                                <th>Ngày tạo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentPrescriptions as $prescription)
                            <tr>
                                <td>
                                    <a href="{{ route('pharmacist.prescriptions.show', $prescription->id) }}" class="fw-bold text-decoration-none">
                                        #{{ $prescription->id }}
                                    </a>
                                </td>
                                <td>{{ $prescription->patient->name }}</td>
                                <td>{{ $prescription->doctor->name }}</td>
                                <td>
                                    @if(isset($prescription->prescription_status) && $prescription->prescription_status == 'pending')
                                        <span class="badge bg-warning">Chờ xử lý</span>
                                    @elseif(isset($prescription->prescription_status) && $prescription->prescription_status == 'completed')
                                        <span class="badge bg-success">Đã xử lý</span>
                                    @else
                                        <span class="badge bg-secondary">Không xác định</span>
                                    @endif
                                </td>
                                <td>{{ $prescription->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">Không có đơn thuốc nào</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Đơn hàng gần đây -->
    <div class="col-lg-6 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Đơn hàng gần đây</h6>
                <a href="{{ route('pharmacist.orders.index') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-arrow-right"></i> Xem tất cả
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Khách hàng</th>
                                <th>Tổng tiền</th>
                                <th>Thanh toán</th>
                                <th>Ngày tạo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $order)
                            <tr>
                                <td>
                                    <a href="{{ route('pharmacist.orders.show', $order->id_order) }}" class="fw-bold text-decoration-none">
                                        #{{ $order->id_order }}
                                    </a>
                                </td>
                                <td>{{ $order->user->name }}</td>
                                <td class="text-end">{{ number_format($order->total_price) }} VNĐ</td>
                                <td>
                                    @if($order->payment_method == 'cash')
                                        <span class="badge bg-success">Tiền mặt</span>
                                    @elseif($order->payment_method == 'card')
                                        <span class="badge bg-info">Thẻ</span>
                                    @else
                                        <span class="badge bg-primary">Chuyển khoản</span>
                                    @endif
                                </td>
                                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">Không có đơn hàng nào</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Thống kê -->
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Thống kê hoạt động</h6>
            </div>
            <div class="card-body">
                <div class="chart-area">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i> Biểu đồ thống kê sẽ được hiển thị tại đây. Bạn có thể tích hợp Chart.js hoặc các thư viện biểu đồ khác để hiển thị dữ liệu.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Thêm script cho biểu đồ nếu cần
</script>
@endsection 