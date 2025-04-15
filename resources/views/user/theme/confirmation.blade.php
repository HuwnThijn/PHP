@extends('user.theme.auth-layout')

@section('title', 'Đặt hàng thành công')

@section('content')
<section class="page-title bg-1">
  <div class="overlay"></div>
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="block text-center">
          <span class="text-white">Đặt hàng thành công</span>
          <h1 class="text-capitalize mb-5 text-lg">Xác nhận đơn hàng</h1>
          </div>
      </div>
    </div>
  </div>
</section>

<section class="section confirmation">
	<div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-10">
        <div class="confirmation-content text-center">
          <i class="icofont-check-circled text-lg text-success" style="font-size: 5rem;"></i>
          <h2 class="mt-3 mb-4">Cảm ơn bạn đã đặt hàng!</h2>
          <p class="mb-4">Đơn hàng của bạn đã được xác nhận và đang được xử lý. Chúng tôi sẽ liên hệ với bạn sớm nhất có thể.</p>
          
          @if(session('order'))
            @php $order = session('order'); @endphp
            <div class="order-details mt-5">
              <div class="card shadow">
                <div class="card-header bg-primary text-white">
                  <h3 class="mb-0">Thông tin đơn hàng #{{ $order->id_order }}</h3>
				</div>
                <div class="card-body">
                  <div class="row mb-4">
                    <div class="col-md-6 text-left">
                      <h5>Thông tin giao hàng</h5>
                      <p><strong>Địa chỉ:</strong> {{ $order->ship->address }}</p>
                      <p><strong>Phương thức thanh toán:</strong> 
                        @if($order->payment_method == 'cash')
                          Thanh toán khi nhận hàng (COD)
                        @elseif($order->payment_method == 'card')
                          Thanh toán bằng thẻ tín dụng/ghi nợ
                        @elseif($order->payment_method == 'transfer')
                          Chuyển khoản ngân hàng
                        @endif
                      </p>
			</div>
                    <div class="col-md-6 text-left">
                      <h5>Tóm tắt đơn hàng</h5>
                      <p><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                      <p><strong>Trạng thái:</strong> <span class="badge badge-info">{{ ucfirst($order->status) }}</span></p>
				</div>
			</div>

                  <div class="table-responsive">
                    <table class="table table-bordered">
                      <thead class="bg-light">
                        <tr>
                          <th>Sản phẩm</th>
                          <th>Giá</th>
                          <th>Số lượng</th>
                          <th>Tổng</th>
                        </tr>
                      </thead>
                      <tbody>
                        @php $subtotal = 0; @endphp
                        @foreach($order->orderItems as $item)
                          <tr>
                            <td>{{ $item->cosmetic->name ?? 'Sản phẩm không tồn tại' }}</td>
                            <td>{{ number_format($item->unit_price, 0, ',', '.') }}đ</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ number_format($item->unit_price * $item->quantity, 0, ',', '.') }}đ</td>
                          </tr>
                          @php $subtotal += $item->unit_price * $item->quantity; @endphp
                        @endforeach
                      </tbody>
                      <tfoot>
                        <tr>
                          <td colspan="3" class="text-right font-weight-bold">Tạm tính:</td>
                          <td>{{ number_format($subtotal, 0, ',', '.') }}đ</td>
                        </tr>
                        <tr>
                          <td colspan="3" class="text-right font-weight-bold">Phí vận chuyển:</td>
                          <td>{{ number_format($order->ship->shipping_fee, 0, ',', '.') }}đ</td>
                        </tr>
                        <tr>
                          <td colspan="3" class="text-right font-weight-bold">Tổng cộng:</td>
                          <td class="font-weight-bold text-primary">{{ number_format($order->total_price, 0, ',', '.') }}đ</td>
                        </tr>
                      </tfoot>
                    </table>
					</div>
				</div>
			</div>
		</div>
          @endif
          
          <div class="mt-5">
            <a href="{{ route('index') }}" class="btn btn-main-2 mr-2">
              <i class="icofont-home mr-1"></i> Về trang chủ
            </a>
            <a href="{{ route('store') }}" class="btn btn-main">
              <i class="icofont-shopping-cart mr-1"></i> Tiếp tục mua sắm
					</a>
				</div>
			</div>
		</div>
	</div>
  </div>
</section>
@endsection