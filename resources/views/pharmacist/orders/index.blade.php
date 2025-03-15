@extends('layouts.pharmacist')

@section('title', 'Quản lý đơn hàng')

@section('page-title', 'Quản lý đơn hàng')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <a href="{{ route('pharmacist.orders.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tạo đơn hàng mới
        </a>
    </div>
    <div class="col-md-6">
        <form action="{{ route('pharmacist.orders.index') }}" method="GET" class="form-inline float-right">
            <div class="input-group">
                <input type="text" class="form-control" name="search" placeholder="Tìm kiếm đơn hàng..." value="{{ request('search') }}">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Danh sách đơn hàng</h6>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Khách hàng</th>
                        <th>Tổng tiền</th>
                        <th>Phương thức thanh toán</th>
                        <th>Trạng thái</th>
                        <th>Ngày tạo</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td>#{{ $order->id_order }}</td>
                        <td>{{ $order->user->name }}</td>
                        <td>{{ number_format($order->total_price) }} VNĐ</td>
                        <td>
                            @if($order->payment_method == 'cash')
                                <span class="badge badge-success">Tiền mặt</span>
                            @elseif($order->payment_method == 'card')
                                <span class="badge badge-info">Thẻ</span>
                            @else
                                <span class="badge badge-primary">Chuyển khoản</span>
                            @endif
                        </td>
                        <td>
                            @if($order->status == 'completed')
                                <span class="badge badge-success">Hoàn thành</span>
                            @elseif($order->status == 'pending')
                                <span class="badge badge-warning">Chờ xử lý</span>
                            @elseif($order->status == 'cancelled')
                                <span class="badge badge-danger">Đã hủy</span>
                            @else
                                <span class="badge badge-secondary">{{ $order->status }}</span>
                            @endif
                        </td>
                        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <a href="{{ route('pharmacist.orders.show', $order->id_order) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i> Xem
                            </a>
                            @if($order->created_at->diffInDays(now()) <= 7 && $order->status == 'completed')
                            <a href="{{ route('pharmacist.returns.create', $order->id_order) }}" class="btn btn-warning btn-sm mt-1">
                                <i class="fas fa-exchange-alt"></i> Đổi trả
                            </a>
                            @endif
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
        
        {{ $orders->links() }}
    </div>
</div>
@endsection 