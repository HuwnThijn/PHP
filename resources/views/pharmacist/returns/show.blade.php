@extends('layouts.pharmacist')

@section('title', 'Chi tiết đơn đổi trả')

@section('page-title', 'Chi tiết đơn đổi trả #' . $return->id)

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Thông tin đơn đổi trả</h6>
                <div>
                    @if($return->status == 'pending')
                        <span class="badge badge-warning">Chờ xử lý</span>
                    @elseif($return->status == 'completed')
                        <span class="badge badge-success">Hoàn thành</span>
                    @elseif($return->status == 'cancelled')
                        <span class="badge badge-danger">Đã hủy</span>
                    @else
                        <span class="badge badge-secondary">{{ $return->status }}</span>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5 class="font-weight-bold">Thông tin đơn hàng</h5>
                        <p><strong>Mã đơn hàng:</strong> 
                            <a href="{{ route('pharmacist.orders.show', $return->order->id_order) }}">
                                #{{ $return->order->id_order }}
                            </a>
                        </p>
                        <p><strong>Ngày mua:</strong> {{ $return->order->created_at->format('d/m/Y H:i') }}</p>
                        <p><strong>Tổng tiền:</strong> {{ number_format($return->order->total_price) }} VNĐ</p>
                        <p><strong>Phương thức thanh toán:</strong> 
                            @if($return->order->payment_method == 'cash')
                                Tiền mặt
                            @elseif($return->order->payment_method == 'card')
                                Thẻ
                            @else
                                Chuyển khoản
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h5 class="font-weight-bold">Thông tin khách hàng</h5>
                        <p><strong>Tên:</strong> {{ $return->order->user->name }}</p>
                        <p><strong>Email:</strong> {{ $return->order->user->email }}</p>
                        <p><strong>Điện thoại:</strong> {{ $return->order->user->phone }}</p>
                        <p><strong>Địa chỉ:</strong> {{ $return->order->user->address }}</p>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5 class="font-weight-bold">Thông tin đổi trả</h5>
                        <p><strong>Loại đổi trả:</strong> 
                            @if($return->return_type == 'refund')
                                <span class="badge badge-info">Hoàn tiền</span>
                            @else
                                <span class="badge badge-primary">Đổi hàng</span>
                            @endif
                        </p>
                        <p><strong>Ngày đổi trả:</strong> {{ $return->created_at->format('d/m/Y H:i') }}</p>
                        <p><strong>Tổng tiền hoàn trả:</strong> {{ number_format($return->total_refund) }} VNĐ</p>
                    </div>
                    <div class="col-md-6">
                        <h5 class="font-weight-bold">Lý do đổi trả</h5>
                        <p>{{ $return->reason }}</p>
                    </div>
                </div>

                <h5 class="font-weight-bold mb-3">Danh sách sản phẩm đổi trả</h5>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Sản phẩm</th>
                                <th>Giá</th>
                                <th>Số lượng đổi trả</th>
                                <th>Thành tiền</th>
                                <th>Lý do</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($return->items as $item)
                            <tr>
                                <td>{{ $item->orderItem->cosmetic->name }}</td>
                                <td>{{ number_format($item->orderItem->price) }} VNĐ</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ number_format($item->refund_amount) }} VNĐ</td>
                                <td>{{ $item->reason }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-right font-weight-bold">Tổng cộng:</td>
                                <td colspan="2" class="font-weight-bold">{{ number_format($return->total_refund) }} VNĐ</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                @if($return->status == 'completed')
                <div class="mt-4">
                    <h5 class="font-weight-bold">Thông tin xử lý</h5>
                    <p><strong>Người xử lý:</strong> {{ $return->processedBy->name ?? 'N/A' }}</p>
                    <p><strong>Thời gian xử lý:</strong> {{ $return->updated_at->format('d/m/Y H:i') }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Thao tác</h6>
            </div>
            <div class="card-body">
                <a href="{{ route('pharmacist.returns.index') }}" class="btn btn-secondary btn-block mb-3">
                    <i class="fas fa-arrow-left"></i> Quay lại danh sách
                </a>
                
                <a href="#" class="btn btn-primary btn-block" onclick="window.print()">
                    <i class="fas fa-print"></i> In phiếu đổi trả
                </a>
            </div>
        </div>
    </div>
</div>
@endsection 