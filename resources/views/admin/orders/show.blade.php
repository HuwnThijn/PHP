@extends('admin.layouts.app')

@section('title', 'Chi tiết đơn hàng #' . $order->id_order)

@section('styles')
<style>
    .order-status {
        position: relative;
        padding-top: 2rem;
    }
    .order-timeline {
        display: flex;
        justify-content: space-between;
        margin-bottom: 2rem;
        position: relative;
    }
    .order-timeline:before {
        content: '';
        position: absolute;
        top: 24px;
        left: 0;
        right: 0;
        height: 3px;
        background-color: #e3e6f0;
        z-index: 1;
    }
    .timeline-item {
        position: relative;
        z-index: 2;
        text-align: center;
        width: 100px;
    }
    .timeline-dot {
        height: 50px;
        width: 50px;
        border-radius: 50%;
        background-color: #e3e6f0;
        margin: 0 auto 10px;
        color: #858796;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }
    .timeline-dot.active {
        background-color: #4e73df;
        color: white;
    }
    .timeline-dot.completed {
        background-color: #1cc88a;
        color: white;
    }
    .timeline-dot.cancelled {
        background-color: #e74a3b;
        color: white;
    }
    .timeline-label {
        font-size: 12px;
        font-weight: 500;
        margin-top: 5px;
    }
    .address-card {
        border-left: 4px solid #4e73df;
    }
    .badge-xl {
        font-size: 16px;
        padding: 10px 15px;
    }
</style>
@endsection

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Chi tiết đơn hàng #{{ $order->id_order }}</h1>
    <div>
        <a href="{{ route('admin.orders.invoice', $order->id_order) }}" class="btn btn-info btn-sm shadow-sm mr-2" target="_blank">
            <i class="fas fa-file-invoice fa-sm text-white-50 mr-1"></i> In hóa đơn
        </a>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-primary btn-sm shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50 mr-1"></i> Quay lại
        </a>
    </div>
</div>

<div class="row">
    <!-- Order Status Timeline -->
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Trạng thái đơn hàng</h6>
                <div>
                    @if($order->status == 'pending')
                        <span class="badge badge-warning badge-xl">Chờ xử lý</span>
                    @elseif($order->status == 'confirmed')
                        <span class="badge badge-info badge-xl">Đã xác nhận</span>
                    @elseif($order->status == 'shipped')
                        <span class="badge badge-primary badge-xl">Đang vận chuyển</span>
                    @elseif($order->status == 'delivered')
                        <span class="badge badge-success badge-xl">Đã giao hàng</span>
                    @elseif($order->status == 'cancelled')
                        <span class="badge badge-danger badge-xl">Đã hủy</span>
                    @else
                        <span class="badge badge-secondary badge-xl">{{ $order->status }}</span>
                    @endif
                </div>
            </div>
            <div class="card-body order-status">
                <div class="order-timeline">
                    <!-- Pending -->
                    <div class="timeline-item">
                        <div class="timeline-dot {{ in_array($order->status, ['pending', 'confirmed', 'shipped', 'delivered']) ? 'completed' : ($order->status == 'cancelled' ? 'cancelled' : '') }}">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="timeline-label">Chờ xử lý</div>
                        @if($order->created_at)
                            <small class="text-muted">{{ $order->created_at->format('d/m/Y H:i') }}</small>
                        @endif
                    </div>
                    
                    <!-- Confirmed -->
                    <div class="timeline-item">
                        <div class="timeline-dot {{ in_array($order->status, ['confirmed', 'shipped', 'delivered']) ? 'completed' : ($order->status == 'cancelled' ? 'cancelled' : '') }}">
                            <i class="fas fa-check"></i>
                        </div>
                        <div class="timeline-label">Đã xác nhận</div>
                        @if($order->confirmed_at)
                            <small class="text-muted">{{ \Carbon\Carbon::parse($order->confirmed_at)->format('d/m/Y H:i') }}</small>
                        @endif
                    </div>
                    
                    <!-- Shipped -->
                    <div class="timeline-item">
                        <div class="timeline-dot {{ in_array($order->status, ['shipped', 'delivered']) ? 'completed' : ($order->status == 'cancelled' ? 'cancelled' : '') }}">
                            <i class="fas fa-truck"></i>
                        </div>
                        <div class="timeline-label">Đang vận chuyển</div>
                        @if($order->shipped_at)
                            <small class="text-muted">{{ \Carbon\Carbon::parse($order->shipped_at)->format('d/m/Y H:i') }}</small>
                        @endif
                    </div>
                    
                    <!-- Delivered -->
                    <div class="timeline-item">
                        <div class="timeline-dot {{ $order->status == 'delivered' ? 'completed' : ($order->status == 'cancelled' ? 'cancelled' : '') }}">
                            <i class="fas fa-box-open"></i>
                        </div>
                        <div class="timeline-label">Đã giao hàng</div>
                        @if($order->delivered_at)
                            <small class="text-muted">{{ \Carbon\Carbon::parse($order->delivered_at)->format('d/m/Y H:i') }}</small>
                        @endif
                    </div>
                </div>

                @if($order->status != 'delivered' && $order->status != 'cancelled')
                <form action="{{ route('admin.orders.update-status', $order->id_order) }}" method="POST" class="mt-3">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-8">
                            <div class="input-group">
                                <select name="status" class="form-control">
                                    <option value="">Cập nhật trạng thái đơn hàng</option>
                                    @if($order->status == 'pending')
                                        <option value="confirmed">Xác nhận đơn hàng</option>
                                        <option value="cancelled">Hủy đơn hàng</option>
                                    @elseif($order->status == 'confirmed')
                                        <option value="shipped">Đơn hàng đang được vận chuyển</option>
                                        <option value="cancelled">Hủy đơn hàng</option>
                                    @elseif($order->status == 'shipped')
                                        <option value="delivered">Đơn hàng đã được giao</option>
                                        <option value="cancelled">Hủy đơn hàng</option>
                                    @endif
                                </select>
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                @endif

                @if($order->cancellation_reason)
                <div class="alert alert-danger mt-3">
                    <strong>Lý do hủy:</strong> {{ $order->cancellation_reason }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Customer Information -->
    <div class="col-lg-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Thông tin khách hàng</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Tên khách hàng:</strong> {{ $order->user->name ?? 'N/A' }}
                </div>
                <div class="mb-3">
                    <strong>Email:</strong> {{ $order->user->email ?? 'N/A' }}
                </div>
                <div class="mb-3">
                    <strong>Số điện thoại:</strong> {{ $order->user->phone ?? 'N/A' }}
                </div>
                <div class="mb-3">
                    <strong>Ngày đặt hàng:</strong> {{ $order->created_at->format('d/m/Y H:i') }}
                </div>
                <div>
                    <strong>Ghi chú:</strong> {{ $order->notes ?? 'Không có ghi chú' }}
                </div>
            </div>
        </div>
    </div>

    <!-- Shipping Information -->
    <div class="col-lg-6">
        <div class="card shadow mb-4 address-card">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Thông tin giao hàng</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Người nhận:</strong> {{ $order->shipping_name ?? ($order->user->name ?? 'N/A') }}
                </div>
                <div class="mb-3">
                    <strong>Số điện thoại:</strong> {{ $order->shipping_phone ?? ($order->user->phone ?? 'N/A') }}
                </div>
                <div class="mb-3">
                    <strong>Địa chỉ:</strong> 
                    @if($order->shipping_address)
                        {{ $order->shipping_address }}
                    @elseif($order->ship && $order->ship->address)
                        {{ $order->ship->address }}
                    @else
                        N/A
                    @endif
                </div>
                <div class="mb-3">
                    <strong>Phường/Xã:</strong> {{ $order->shipping_ward ?? 'N/A' }}
                </div>
                <div class="mb-3">
                    <strong>Quận/Huyện:</strong> {{ $order->shipping_district ?? 'N/A' }}
                </div>
                <div class="mb-3">
                    <strong>Tỉnh/Thành phố:</strong> {{ $order->shipping_province ?? 'N/A' }}
                </div>
                <div class="mb-3">
                    <strong>Phí vận chuyển:</strong> 
                    @if($order->shipping_fee)
                        {{ number_format($order->shipping_fee, 0, ',', '.') }}đ
                    @elseif($order->ship && $order->ship->shipping_fee)
                        {{ number_format($order->ship->shipping_fee, 0, ',', '.') }}đ
                    @else
                        0đ
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Payment Information -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Thông tin thanh toán</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4 mb-3">
                <strong>Phương thức thanh toán:</strong>
                @if($order->payment_method == 'cash')
                    <span class="badge badge-success">Tiền mặt</span>
                @elseif($order->payment_method == 'credit_card')
                    <span class="badge badge-info">Thẻ tín dụng</span>
                @elseif($order->payment_method == 'bank_transfer')
                    <span class="badge badge-primary">Chuyển khoản</span>
                @else
                    <span class="badge badge-secondary">{{ $order->payment_method }}</span>
                @endif
            </div>
            <div class="col-md-4 mb-3">
                <strong>Trạng thái thanh toán:</strong>
                @if($order->payment_status == 'paid')
                    <span class="badge badge-success">Đã thanh toán</span>
                @elseif($order->payment_status == 'pending')
                    <span class="badge badge-warning">Chưa thanh toán</span>
                @else
                    <span class="badge badge-secondary">{{ $order->payment_status }}</span>
                @endif
            </div>
            <div class="col-md-4 mb-3">
                <strong>Mã giao dịch:</strong>
                {{ $order->transaction_id ?? 'N/A' }}
            </div>
        </div>
    </div>
</div>

<!-- Order Details -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Chi tiết đơn hàng</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th width="80">Ảnh</th>
                        <th>Sản phẩm</th>
                        <th width="120">Đơn giá</th>
                        <th width="80">Số lượng</th>
                        <th width="150">Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($order->orderItems as $item)
                    <tr>
                        <td>
                            @if($item->cosmetic && $item->cosmetic->image)
                                <img src="{{ asset('storage/' . $item->cosmetic->image) }}" alt="{{ $item->cosmetic->name }}" class="img-thumbnail" width="60">
                            @else
                                <img src="{{ asset('images/no-image.png') }}" alt="No Image" class="img-thumbnail" width="60">
                            @endif
                        </td>
                        <td>
                            {{ $item->cosmetic->name ?? 'Sản phẩm không tồn tại' }}
                            @if($item->cosmetic)
                                <div class="text-muted small">Mã: {{ $item->cosmetic->id_cosmetic }}</div>
                            @endif
                        </td>
                        <td>{{ number_format($item->price ?? $item->unit_price ?? 0, 0, ',', '.') }}đ</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format(($item->price ?? $item->unit_price ?? 0) * $item->quantity, 0, ',', '.') }}đ</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">Không có sản phẩm nào</td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot class="font-weight-bold">
                    
                    <tr>
                        <td colspan="4" class="text-right">Phí vận chuyển:</td>
                        <td>{{ number_format($order->shipping_fee ?: ($order->ship->shipping_fee ?? 30000), 0, ',', '.') }}đ</td>
                    </tr>
                    <tr>
                        <td colspan="4" class="text-right">Thuế: </td>
                        <td colspan="4" class="text-left">2000đ</td>
                    </tr>
                    <tr>
                        <td colspan="4" class="text-right">Giảm giá:</td>
                        <td>{{ number_format($order->discount ?: 0, 0, ',', '.') }}đ</td>
                    </tr>
                    <tr class="bg-light">
                        <td colspan="4" class="text-right">Tổng tiền:</td>
                        @php
                            $subtotal = $order->subtotal ?: $calculatedSubtotal;
                            $shipping = $order->shipping_fee ?: ($order->ship->shipping_fee ?? 30000);
                            $tax = $order->tax ?: 2000;
                            $discount = $order->discount ?: 0;
                            $calculatedTotal = $subtotal + $shipping + $tax - $discount;
                        @endphp
                        <td class="text-primary">{{ number_format($order->total_price ?: $calculatedTotal, 0, ',', '.') }}đ</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Confirm status change
    $('form').on('submit', function(e) {
        const status = $('select[name="status"]').val();
        if (!status) {
            e.preventDefault();
            alert('Vui lòng chọn trạng thái đơn hàng!');
            return false;
        }
        
        if (status === 'cancelled') {
            e.preventDefault();
            const reason = prompt('Vui lòng nhập lý do hủy đơn hàng:');
            if (reason === null) {
                return false;
            }
            if (reason.trim() === '') {
                alert('Vui lòng nhập lý do hủy đơn hàng!');
                return false;
            }
            
            // Add cancellation reason to form
            const reasonInput = $('<input>').attr({
                type: 'hidden',
                name: 'cancellation_reason',
                value: reason
            });
            $(this).append(reasonInput);
            this.submit();
        }
    });
});
</script>
@endsection 