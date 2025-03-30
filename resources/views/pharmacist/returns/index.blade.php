@extends('layouts.pharmacist')

@section('title', 'Quản lý đổi trả')

@section('page-title', 'Quản lý đổi trả')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Danh sách đơn đổi trả</h6>
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
                        <th>Đơn hàng</th>
                        <th>Khách hàng</th>
                        <th>Loại đổi trả</th>
                        <th>Tổng hoàn trả</th>
                        <th>Trạng thái</th>
                        <th>Ngày tạo</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($returns as $return)
                    <tr>
                        <td>#{{ $return->id }}</td>
                        <td>
                            <a href="{{ route('pharmacist.orders.show', $return->order->id_order) }}">
                                #{{ $return->order->id_order }}
                            </a>
                        </td>
                        <td>{{ $return->order->user->name }}</td>
                        <td>
                            @if($return->return_type == 'refund')
                                <span class="badge badge-info">Hoàn tiền</span>
                            @else
                                <span class="badge badge-primary">Đổi hàng</span>
                            @endif
                        </td>
                        <td>{{ number_format($return->total_refund) }} VNĐ</td>
                        <td>
                            @if($return->status == 'pending')
                                <span class="badge badge-warning">Chờ xử lý</span>
                            @elseif($return->status == 'completed')
                                <span class="badge badge-success">Hoàn thành</span>
                            @elseif($return->status == 'cancelled')
                                <span class="badge badge-danger">Đã hủy</span>
                            @else
                                <span class="badge badge-secondary">{{ $return->status }}</span>
                            @endif
                        </td>
                        <td>{{ $return->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <a href="{{ route('pharmacist.returns.show', $return->id) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i> Xem
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">Không có đơn đổi trả nào</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{ $returns->links() }}
    </div>
</div>
@endsection 