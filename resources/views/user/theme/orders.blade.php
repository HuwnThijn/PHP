@extends('user.theme.auth-layout')

@section('title', 'Lịch sử đơn hàng')

@section('content')
<section class="page-title bg-1">
  <div class="overlay"></div>
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="block text-center">
          <span class="text-white">Lịch sử đơn hàng</span>
          <h1 class="text-capitalize mb-5 text-lg">Đơn hàng của tôi</h1>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="section orders-section">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
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

        <div class="card shadow">
          <div class="card-body">
            <h4 class="mb-4">Đơn hàng của tôi</h4>
            
            <!-- Filters and search -->
            <div class="row mb-4">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="status-filter">Lọc theo trạng thái</label>
                  <select class="form-control" id="status-filter">
                    <option value="all">Tất cả đơn hàng</option>
                    <option value="pending">Chờ xử lý</option>
                    <option value="confirmed">Đã xác nhận</option>
                    <option value="shipped">Đang vận chuyển</option>
                    <option value="delivered">Đã giao hàng</option>
                    <option value="cancelled">Đã hủy</option>
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="order-search">Tìm kiếm đơn hàng</label>
                  <input type="text" class="form-control" id="order-search" placeholder="Tìm theo mã đơn hàng hoặc sản phẩm">
                </div>
              </div>
            </div>
            
            <!-- Orders table -->
            <div class="table-responsive">
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th>Mã đơn hàng</th>
                    <th>Ngày đặt</th>
                    <th>Số lượng</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                    <th>Thao tác</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- No orders state -->
                  @if(!isset($orders) || count($orders) == 0)
                  <tr id="no-orders">
                    <td colspan="6" class="text-center py-4">
                      <div class="empty-orders-container">
                        <i class="icofont-file-document" style="font-size: 3rem; color: #e9ecef;"></i>
                        <p class="mt-3 mb-0">Bạn chưa có đơn hàng nào</p>
                        <a href="{{ route('store') }}" class="btn btn-main-2 btn-sm mt-3">Mua sắm ngay</a>
                      </div>
                    </td>
                  </tr>
                  @else
                    @foreach($orders as $order)
                    <tr class="order-row" data-status="{{ $order->status }}">
                      <td>
                        <span class="font-weight-bold">{{ $order->order_number }}</span>
                      </td>
                      <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y') }}</td>
                      <td>{{ $order->item_count }} sản phẩm</td>
                      <td>{{ number_format($order->total, 0, ',', '.') }}đ</td>
                      <td>
                        <span class="badge 
                          @if($order->status == 'delivered') badge-success
                          @elseif($order->status == 'pending') badge-warning
                          @elseif($order->status == 'cancelled') badge-danger
                          @elseif($order->status == 'confirmed') badge-info
                          @elseif($order->status == 'shipped') badge-primary
                          @endif">
                          @if($order->status == 'pending') Chờ xử lý
                          @elseif($order->status == 'confirmed') Đã xác nhận
                          @elseif($order->status == 'shipped') Đang vận chuyển
                          @elseif($order->status == 'delivered') Đã giao hàng
                          @elseif($order->status == 'cancelled') Đã hủy
                          @else {{ ucfirst($order->status) }}
                          @endif
                        </span>
                      </td>
                      <td>
                        <div class="order-payment-status">
                          <span class="label">Trạng thái thanh toán:</span>
                          @if($order->payment_status == 'paid')
                            <span class="badge badge-success">Đã thanh toán</span>
                          @elseif($order->payment_status == 'pending')
                            <span class="badge badge-warning">Chờ thanh toán</span>
                          @else
                            <span class="badge badge-danger">Thanh toán thất bại</span>
                          @endif
                        </div>
                      </td>
                      <td>
                        <button class="btn btn-sm btn-primary view-order-btn" 
                          data-toggle="modal" 
                          data-target="#order-details-modal" 
                          data-order-id="{{ $order->id_order }}"
                          data-order-number="{{ $order->order_number }}"
                          data-order-date="{{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y H:i') }}"
                          data-order-status="{{ $order->status }}"
                          data-payment-method="{{ $order->payment_method }}"
                          data-shipping-address="{{ $order->ship ? $order->ship->address : 'N/A' }}"
                          data-order-total="{{ number_format($order->total, 0, ',', '.') }}đ">
                          <i class="icofont-eye-alt mr-1"></i> Xem chi tiết
                        </button>
                      </td>
                    </tr>
                    @endforeach
                  @endif
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Order details modal -->
<div class="modal fade" id="order-details-modal" tabindex="-1" role="dialog" aria-labelledby="orderDetailsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="orderDetailsModalLabel">Chi tiết đơn hàng</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- Order details content will be loaded here dynamically -->
        <div class="order-details-container">
          <div class="row">
            <div class="col-md-6">
              <h6>Thông tin đơn hàng</h6>
              <p><strong>Mã đơn hàng:</strong> <span id="modal-order-number"></span></p>
              <p><strong>Ngày đặt:</strong> <span id="modal-order-date"></span></p>
              <p><strong>Trạng thái:</strong> <span id="modal-order-status"></span></p>
              <p><strong>Phương thức thanh toán:</strong> <span id="modal-payment-method"></span></p>
            </div>
            <div class="col-md-6">
              <h6>Địa chỉ giao hàng</h6>
              <p id="modal-shipping-address"></p>
            </div>
          </div>
          
          <hr>
          
          <h6>Sản phẩm đã đặt</h6>
          <div class="table-responsive">
            <table class="table table-sm">
              <thead>
                <tr>
                  <th>Sản phẩm</th>
                  <th>Giá</th>
                  <th>Số lượng</th>
                  <th>Thành tiền</th>
                </tr>
              </thead>
              <tbody id="modal-order-items">
                <!-- Order items will be loaded here dynamically -->
              </tbody>
              <tfoot>
                <tr>
                  <td colspan="3" class="text-right font-weight-bold">Tạm tính:</td>
                  <td id="modal-subtotal"></td>
                </tr>
                <tr>
                  <td colspan="3" class="text-right font-weight-bold">Phí vận chuyển:</td>
                  <td id="modal-shipping-fee"></td>
                </tr>
                <tr>
                  <td colspan="3" class="text-right font-weight-bold">Thuế:</td>
                  <td id="modal-tax"></td>
                </tr>
                <tr>
                  <td colspan="3" class="text-right font-weight-bold">Tổng cộng:</td>
                  <td id="modal-total" class="font-weight-bold"></td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
        <button type="button" class="btn btn-danger" id="cancel-order-btn">Hủy đơn hàng</button>
      </div>
    </div>
  </div>
</div>

@endsection

@section('scripts')
<script>
  $(document).ready(function() {
    // Order search functionality
    $('#order-search').on('keyup', function() {
      const searchTerm = $(this).val().toLowerCase();
      
      $('.order-row').each(function() {
        const rowText = $(this).text().toLowerCase();
        
        if (rowText.includes(searchTerm)) {
          $(this).show();
        } else {
          $(this).hide();
        }
      });
      
      checkVisibleOrders();
    });
    
    // Status filter functionality
    $('#status-filter').on('change', function() {
      const selectedStatus = $(this).val().toLowerCase();
      
      if (selectedStatus === 'all') {
        $('.order-row').show();
      } else {
        $('.order-row').each(function() {
          if ($(this).data('status') === selectedStatus) {
            $(this).show();
          } else {
            $(this).hide();
          }
        });
      }
      
      checkVisibleOrders();
    });
    
    // Function to check if any orders are visible
    function checkVisibleOrders() {
      if ($('.order-row:visible').length === 0) {
        if ($('#no-orders').length === 0) {
          $('tbody').append(`
            <tr id="no-orders-filtered">
              <td colspan="6" class="text-center py-4">
                <div class="empty-orders-container">
                  <i class="icofont-filter" style="font-size: 3rem; color: #e9ecef;"></i>
                  <p class="mt-3 mb-0">Không tìm thấy đơn hàng nào phù hợp</p>
                </div>
              </td>
            </tr>
          `);
        }
      } else {
        $('#no-orders-filtered').remove();
      }
    }
    
    // View order details functionality
    $('.view-order-btn').on('click', function() {
      const orderId = $(this).data('order-id');
      const orderNumber = $(this).data('order-number');
      const orderDate = $(this).data('order-date');
      const orderStatus = $(this).data('order-status');
      const paymentMethod = $(this).data('payment-method');
      const shippingAddress = $(this).data('shipping-address');
      
      // Set basic order information
      $('#modal-order-number').text(orderNumber);
      $('#modal-order-date').text(orderDate);
      
      // Set status with appropriate translation
      let statusText = '';
      switch(orderStatus) {
        case 'pending': statusText = 'Chờ xử lý'; break;
        case 'confirmed': statusText = 'Đã xác nhận'; break;
        case 'shipped': statusText = 'Đang vận chuyển'; break;
        case 'delivered': statusText = 'Đã giao hàng'; break;
        case 'cancelled': statusText = 'Đã hủy'; break;
        default: statusText = orderStatus;
      }
      $('#modal-order-status').text(statusText);
      
      // Set payment method with appropriate translation
      let paymentText = '';
      switch(paymentMethod) {
        case 'cash': paymentText = 'Thanh toán khi nhận hàng'; break;
        case 'credit_card': paymentText = 'Thẻ tín dụng/ghi nợ'; break;
        case 'bank_transfer': paymentText = 'Chuyển khoản ngân hàng'; break;
        default: paymentText = paymentMethod;
      }
      $('#modal-payment-method').text(paymentText);
      
      $('#modal-shipping-address').text(shippingAddress);
      
      // Load order items via AJAX
      $.ajax({
        url: "{{ route('api.order.items') }}",
        type: "GET",
        data: { id: orderId },
        success: function(response) {
          if (response.success) {
            // Clear previous items
            $('#modal-order-items').empty();
            
            // Add order items to the table
            let subtotal = 0;
            response.items.forEach(function(item) {
              const itemTotal = item.quantity * item.unit_price;
              subtotal += itemTotal;
              
              $('#modal-order-items').append(`
                <tr>
                  <td>${item.product_name}</td>
                  <td>${formatCurrency(item.unit_price)}</td>
                  <td>${item.quantity}</td>
                  <td>${formatCurrency(itemTotal)}</td>
        </tr>
              `);
            });
            
            // Set order totals
            $('#modal-subtotal').text(formatCurrency(subtotal));
            $('#modal-shipping-fee').text(formatCurrency(response.shipping_fee));
            $('#modal-tax').text(formatCurrency(response.tax));
            $('#modal-total').text(formatCurrency(response.total));
      
            // Show/hide cancel button based on order status
            if (orderStatus === 'pending' || orderStatus === 'confirmed') {
              $('#cancel-order-btn').show();
      } else {
              $('#cancel-order-btn').hide();
      }
          } else {
            alert('Không thể tải thông tin đơn hàng. Vui lòng thử lại sau.');
          }
        },
        error: function() {
          alert('Đã xảy ra lỗi khi tải thông tin đơn hàng. Vui lòng thử lại sau.');
        }
      });
    });
      
    // Helper function to format currency
    function formatCurrency(amount) {
      return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount).replace('₫', 'đ');
    }
    
    // Cancel order functionality
    $('#cancel-order-btn').on('click', function() {
      const orderId = $('.view-order-btn').data('order-id');
      
      if (confirm('Bạn có chắc chắn muốn hủy đơn hàng này không?')) {
        $.ajax({
          url: "{{ route('order.cancel') }}",
          type: "POST",
          data: {
            _token: "{{ csrf_token() }}",
            id: orderId
          },
          success: function(response) {
            if (response.success) {
              alert('Đơn hàng đã được hủy thành công.');
              location.reload();
            } else {
              alert(response.message || 'Không thể hủy đơn hàng. Vui lòng thử lại sau.');
            }
          },
          error: function() {
            alert('Đã xảy ra lỗi khi hủy đơn hàng. Vui lòng thử lại sau.');
          }
        });
      }
    });
  });
</script>

<style>
.order-payment-status {
  margin-top: 10px;
}
.order-payment-status .label {
  font-weight: 500;
  margin-right: 5px;
}
</style>
@endsection
