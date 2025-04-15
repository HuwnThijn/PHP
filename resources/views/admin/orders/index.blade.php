@extends('admin.layouts.app')

@section('title', 'Quản lý đơn hàng')

@section('styles')
<link href="{{ asset('admin/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
<link href="{{ asset('admin/vendor/daterangepicker/daterangepicker.css') }}" rel="stylesheet">
<style>
    .card-stats .icon-box {
        font-size: 2rem;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 65px;
        width: 65px;
        border-radius: 8px;
    }
    .bg-gradient-primary-light {
        background: linear-gradient(to right, #4e73df, #6f86e5);
    }
    .bg-gradient-success-light {
        background: linear-gradient(to right, #1cc88a, #36e3ab);
    }
    .bg-gradient-warning-light {
        background: linear-gradient(to right, #f6c23e, #ffda75);
    }
    .bg-gradient-danger-light {
        background: linear-gradient(to right, #e74a3b, #ff7a6e);
    }
    .bg-gradient-info-light {
        background: linear-gradient(to right, #36b9cc, #5ccadb);
    }
</style>
@endsection

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Quản lý đơn hàng</h1>
    <div>
        <a href="{{ route('admin.orders.export.excel') }}" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm mr-2">
            <i class="fas fa-file-excel fa-sm text-white-50 mr-1"></i> Xuất Excel
        </a>
        <a href="{{ route('admin.orders.export.pdf') }}" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm">
            <i class="fas fa-file-pdf fa-sm text-white-50 mr-1"></i> Xuất PDF
        </a>
    </div>
</div>

<!-- Content Row -->
<div class="row">
    <!-- Tổng đơn hàng -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Tổng đơn hàng</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totalOrders) }}</div>
                    </div>
                    <div class="col-auto">
                        <div class="icon-box text-white bg-gradient-primary-light">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Đơn hàng chờ xử lý -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Chờ xử lý</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($pendingOrders) }}</div>
                    </div>
                    <div class="col-auto">
                        <div class="icon-box text-white bg-gradient-warning-light">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Đơn hàng hoàn thành -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Đã hoàn thành</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($completedOrders) }}</div>
                    </div>
                    <div class="col-auto">
                        <div class="icon-box text-white bg-gradient-success-light">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Đơn hàng đã hủy -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Đã hủy</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($cancelledOrders) }}</div>
                    </div>
                    <div class="col-auto">
                        <div class="icon-box text-white bg-gradient-danger-light">
                            <i class="fas fa-times-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter Card -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Bộ lọc</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.orders.index') }}" method="GET" id="filter-form">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="status">Trạng thái</label>
                    <select class="form-control" id="status" name="status">
                        <option value="">Tất cả trạng thái</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                        <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Đang vận chuyển</option>
                        <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Đã giao hàng</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                    </select>
                </div>
                <div class="col-md-5 mb-3">
                    <label for="date-range">Khoảng thời gian</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="date-range" name="date_range" placeholder="Chọn khoảng thời gian" value="{{ request('date_range') }}">
                        <input type="hidden" name="date_from" id="date-from" value="{{ request('date_from') }}">
                        <input type="hidden" name="date_to" id="date-to" value="{{ request('date_to') }}">
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="search">Tìm kiếm</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="search" name="search" placeholder="Tìm theo mã đơn hàng, tên khách hàng" value="{{ request('search') }}">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search fa-sm"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Orders Table -->
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Danh sách đơn hàng</h6>
        <span class="text-muted">Tổng doanh thu: <strong>{{ number_format($totalRevenue, 0, ',', '.') }}đ</strong></span>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="ordersTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Mã đơn hàng</th>
                        <th>Khách hàng</th>
                        <th>Ngày đặt</th>
                        <th>Tổng tiền</th>
                        <th>Phương thức thanh toán</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td><a href="{{ route('admin.orders.show', $order->id_order) }}" class="font-weight-bold text-primary">#{{ $order->id_order }}</a></td>
                        <td>
                            {{ $order->user->name ?? 'N/A' }}<br>
                            <small class="text-muted">{{ $order->user->email ?? '' }}</small>
                        </td>
                        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ number_format($order->total_price, 0, ',', '.') }}đ</td>
                        <td>
                            @if($order->payment_method == 'cash')
                                <span class="badge badge-success text-black">Tiền mặt</span>
                            @elseif($order->payment_method == 'credit_card')
                                <span class="badge badge-info text-black">Thẻ tín dụng</span>
                            @elseif($order->payment_method == 'bank_transfer')
                                <span class="badge badge-primary">Chuyển khoản</span>
                            @else
                                <span class="badge badge-secondary text-black">{{ $order->payment_method }}</span>
                            @endif
                        </td>
                        <td>
                            @if($order->status == 'pending')
                                <span class="badge badge-warning text-black">Chờ xử lý</span>
                            @elseif($order->status == 'confirmed')
                                <span class="badge badge-info text-black">Đã xác nhận</span>
                            @elseif($order->status == 'shipped')
                                <span class="badge badge-primary text-black">Đang vận chuyển</span>
                            @elseif($order->status == 'delivered')
                                <span class="badge badge-success text-black">Đã giao hàng</span>
                            @elseif($order->status == 'cancelled')
                                <span class="badge badge-danger text-black">Đã hủy</span>
                            @else
                                <span class="badge badge-secondary text-black">{{ $order->status }}</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order->id_order) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i> Xem
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">Không có đơn hàng nào</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="d-flex justify-content-end mt-3">
            {{ $orders->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('admin/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('admin/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('admin/vendor/moment/moment.min.js') }}"></script>
<script src="{{ asset('admin/vendor/daterangepicker/daterangepicker.js') }}"></script>
<script>
$(document).ready(function() {
    // Date Range Picker
    $('#date-range').daterangepicker({
        opens: 'left',
        autoUpdateInput: false,
        locale: {
            format: 'DD/MM/YYYY',
            separator: ' - ',
            applyLabel: 'Áp dụng',
            cancelLabel: 'Hủy',
            fromLabel: 'Từ',
            toLabel: 'Đến',
            customRangeLabel: 'Tùy chọn',
            daysOfWeek: ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'],
            monthNames: ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'],
            firstDay: 1
        },
        ranges: {
           'Hôm nay': [moment(), moment()],
           'Hôm qua': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           '7 ngày qua': [moment().subtract(6, 'days'), moment()],
           '30 ngày qua': [moment().subtract(29, 'days'), moment()],
           'Tháng này': [moment().startOf('month'), moment().endOf('month')],
           'Tháng trước': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    });

    $('#date-range').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
        $('#date-from').val(picker.startDate.format('YYYY-MM-DD'));
        $('#date-to').val(picker.endDate.format('YYYY-MM-DD'));
    });

    $('#date-range').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
        $('#date-from').val('');
        $('#date-to').val('');
    });

    // Apply current date range if exists
    const dateRange = "{{ request('date_range') }}";
    if (dateRange) {
        const dates = dateRange.split(' - ');
        if (dates.length === 2) {
            const startDate = moment(dates[0], 'DD/MM/YYYY');
            const endDate = moment(dates[1], 'DD/MM/YYYY');
            $('#date-range').data('daterangepicker').setStartDate(startDate);
            $('#date-range').data('daterangepicker').setEndDate(endDate);
        }
    }

    // Status change event
    $('#status').change(function() {
        $('#filter-form').submit();
    });
});
</script>
@endsection 