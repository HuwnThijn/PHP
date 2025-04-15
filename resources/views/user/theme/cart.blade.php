@extends('user.theme.auth-layout')

@section('title', 'Giỏ hàng của tôi')

@section('content')
<section class="page-title bg-1">
  <div class="overlay"></div>
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="block text-center">
          <span class="text-white">Giỏ hàng</span>
          <h1 class="text-capitalize mb-5 text-lg">Giỏ hàng của tôi</h1>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="section cart-section">
  <div class="container">
    <div class="row">
      <div class="col-lg-12" id="alert-container">
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
      </div>
    </div>

    <div class="row">
      <div class="col-lg-8">
        <div class="card shadow">
          <div class="card-body">
            <h4 class="mb-4">Giỏ hàng của tôi</h4>
            
            <!-- Bảng sản phẩm -->
            <div class="table-responsive">
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th>Hình ảnh</th>
                    <th>Tên</th>
                    <th>Giá</th>
                    <th>Số lượng</th>
                    <th>Tổng</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody id="cart-items">
                  <!-- Trạng thái giỏ hàng trống -->
                  @if(!isset($cartItems) || count($cartItems) == 0)
                  <tr id="empty-cart">
                    <td colspan="6" class="text-center py-4">
                      <div class="empty-cart-message">
                        <i class="icofont-shopping-cart" style="font-size: 3rem; color: #e9ecef;"></i>
                        <p class="mt-3 mb-0">Giỏ hàng của bạn trống</p>
                        <a href="{{ route('store') }}" class="btn btn-main-2 btn-sm mt-3">Tiếp tục mua sắm</a>
                      </div>
                    </td>
                  </tr>
                  @endif
                  
                  <!-- Các sản phẩm trong giỏ hàng -->
                  @if(isset($cartItems) && count($cartItems) > 0)
                    @foreach($cartItems as $id => $item)
                    <tr class="cart-item" data-id="{{ $id }}">
                      <td>
                        <img src="{{ asset($item['image']) }}" alt="{{ $item['name'] }}" class="img-fluid cart-img">
                      </td>
                      <td>
                        <h6 class="mb-0">{{ $item['name'] }}</h6>
                      </td>
                      <td class="item-price">{{ number_format($item['price'], 0, ',', '.') }}đ</td>
                      <td>
                        <div class="quantity-control">
                          <button type="button" class="btn-minus">-</button>
                          <input type="text" class="quantity-input" value="{{ $item['quantity'] }}" min="1" max="10">
                          <button type="button" class="btn-plus">+</button>
                        </div>
                      </td>
                      <td class="item-total">{{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}đ</td>
                      <td>
                        <button class="btn-remove" data-id="{{ $id }}">
                          <i class="icofont-trash"></i>
                        </button>
                      </td>
                    </tr>
                    @endforeach
                  @endif
                </tbody>
              </table>
            </div>
            
            <!-- Nút điều hướng -->
            <div class="d-flex justify-content-between mt-4">
              <a href="{{ route('store') }}" class="btn btn-secondary">
                <i class="icofont-arrow-left mr-1"></i> Tiếp tục mua sắm
              </a>
              
              {{-- <button id="update-cart" class="btn btn-primary" @if(!isset($cartItems) || count($cartItems) == 0) disabled @endif>
                <i class="icofont-refresh mr-1"></i> Cập nhật giỏ hàng
              </button> --}}
            </div>
          </div>
        </div>
      </div>
      
      <!-- Thông tin đơn hàng -->
      <div class="col-lg-4 mt-4 mt-lg-0">
        <div class="card shadow">
          <div class="card-body">
            <h4 class="mb-4">Thông tin đơn hàng</h4>
            
            <div class="d-flex justify-content-between mb-2">
              <span>Tạm tính</span>
              <span class="font-weight-bold" id="subtotal">
                @if(isset($cartItems) && count($cartItems) > 0)
                  @php
                    $subtotal = 0;
                    foreach($cartItems as $item) {
                      $subtotal += $item['price'] * $item['quantity'];
                    }
                  @endphp
                  {{ number_format($subtotal, 0, ',', '.') }}đ
                @else
                  @php
                    $subtotal = 0;
                  @endphp
                  0đ
                @endif
              </span>
            </div>
            
            <div class="d-flex justify-content-between mb-2">
              <span>Phí vận chuyển</span>
              <span class="font-weight-bold" id="shipping">
                @if(isset($cartItems) && count($cartItems) > 0)
                  @php
                    $shipping = 30000;
                  @endphp
                  {{ number_format($shipping, 0, ',', '.') }}đ
                @else
                  @php
                    $shipping = 0;
                  @endphp
                  0đ
                @endif
              </span>
            </div>
            
            <div class="d-flex justify-content-between mb-2">
              <span>Thuế</span>
              <span class="font-weight-bold" id="tax">
                @if(isset($cartItems) && count($cartItems) > 0)
                  @php
                    $tax = 2000;
                  @endphp
                  {{ number_format($tax, 0, ',', '.') }}đ
                @else
                  @php
                    $tax = 0;
                  @endphp
                  0đ
                @endif
              </span>
            </div>
            
            <hr>
            
            <div class="d-flex justify-content-between mb-3">
              <span class="h5">Tổng cộng</span>
              <span class="h5 text-primary" id="total">
                @if(isset($cartItems) && count($cartItems) > 0)
                  @php
                    $total = $subtotal + $shipping + $tax;
                  @endphp
                  {{ number_format($total, 0, ',', '.') }}đ
                @else
                  @php
                    $total = 0;
                  @endphp
                  0đ
                @endif
              </span>
            </div>
            
            <button id="checkout-btn" class="btn btn-main btn-block" @if(!isset($cartItems) || count($cartItems) == 0) disabled @endif>
              Tiến hành thanh toán
            </button>
          </div>
        </div>
        
        <!-- Mã giảm giá -->
        {{-- <div class="card shadow mt-4">
          <div class="card-body">
            <h5>Mã giảm giá</h5>
            <div class="input-group mt-3">
              <input type="text" class="form-control" placeholder="Nhập mã giảm giá">
              <div class="input-group-append">
                <button class="btn btn-secondary" type="button">Áp dụng</button>
              </div>
            </div>
          </div>
        </div> --}}
      </div>
    </div>
  </div>
</section>

<!-- Hộp thoại xác nhận xóa -->
<div class="modal fade" id="delete-modal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalLabel">Xác nhận xóa</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
        <button type="button" class="btn btn-danger" id="confirm-delete">Xóa sản phẩm</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal thanh toán -->
<div class="modal fade" id="checkout-modal" tabindex="-1" role="dialog" aria-labelledby="checkoutModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="checkoutModalLabel">Xác nhận thông tin thanh toán</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="checkout-form">
          <div class="form-group">
            <label for="shipping-address">Địa chỉ giao hàng <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="shipping-address" name="address" placeholder="Nhập địa chỉ giao hàng đầy đủ">
            <small id="address-error" class="form-text text-danger" style="display: none;"></small>
          </div>
          
          <div class="form-group">
            <label for="shipping-phone">Số điện thoại liên hệ <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="shipping-phone" name="phone" placeholder="Nhập số điện thoại liên hệ">
            <small id="phone-error" class="form-text text-danger" style="display: none;"></small>
          </div>
          
          <div class="form-group">
            <label for="payment_method">Phương thức thanh toán:</label>
            <div class="payment-methods">
              <div class="payment-method">
                <input type="radio" id="payment_cod" name="payment_method" value="cod" checked>
                <label for="payment_cod">Tiền mặt khi nhận hàng</label>
                <small class="text-muted d-block ml-4">Thanh toán khi nhận hàng (COD)</small>
              </div>
              <div class="payment-method mt-2">
                <input type="radio" id="payment_card" name="payment_method" value="card">
                <label for="payment_card">Thẻ tín dụng/ghi nợ</label>
                <small class="text-muted d-block ml-4">Thanh toán trực tuyến an toàn.</small>
              </div>
              <div class="payment-method mt-2">
                <input type="radio" id="payment_bank" name="payment_method" value="bank_transfer">
                <label for="payment_bank">Chuyển khoản ngân hàng</label>
                <small class="text-muted d-block ml-4">Chuyển khoản trước khi nhận hàng</small>
              </div>
            </div>
          </div>
          
          <div id="card-payment-section" style="display: none;">
            <div class="form-group">
                <label for="card-element">Thông tin thẻ</label>
                <div id="card-element" class="form-control">
                    <!-- Stripe Card Element will be inserted here -->
                </div>
                <div id="card-errors" class="text-danger mt-2" role="alert"></div>
            </div>
          </div>
          
          <div id="bank-transfer-section" style="display: none;">
            <div class="alert alert-info">
                <p><strong>Thông tin chuyển khoản:</strong></p>
                <p>Ngân hàng: VietcomBank</p>
                <p>Số tài khoản: 1234567890</p>
                <p>Chủ tài khoản: BeautyCosmetic</p>
                <p>Nội dung: Thanh toán đơn hàng [Họ tên của bạn]</p>
            </div>
          </div>
          
          <div class="form-group">
            <label>Tóm tắt đơn hàng</label>
            <div class="card">
              <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                  <span>Tổng tiền hàng:</span>
                  <span id="modal-subtotal">{{ isset($subtotal) ? number_format($subtotal, 0, ',', '.') : 0 }}đ</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                  <span>Phí vận chuyển:</span>
                  <span id="modal-shipping">{{ isset($shipping) ? number_format($shipping, 0, ',', '.') : 0 }}đ</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                  <span>Thuế:</span>
                  <span id="modal-tax">{{ isset($tax) ? number_format($tax, 0, ',', '.') : 0 }}đ</span>
                </div>
                <div class="d-flex justify-content-between font-weight-bold mt-2 pt-2 border-top">
                  <span>Tổng thanh toán:</span>
                  <span id="modal-total">{{ isset($total) ? number_format($total, 0, ',', '.') : 0 }}đ</span>
                </div>
              </div>
            </div>
          </div>
          
          <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" id="agreement" required>
            <label class="form-check-label" for="agreement">Tôi đã đọc và đồng ý với <a href="#">điều khoản dịch vụ</a> và <a href="#">chính sách bảo mật</a></label>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
        <button type="submit" class="btn btn-primary" id="checkout-submit" form="checkout-form">Xác nhận đặt hàng</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('styles')
<style>
  /* Bảng giỏ hàng */
  .table th, .table td {
    vertical-align: middle;
  }
  
  /* Hình ảnh sản phẩm */
  .cart-img {
    max-width: 80px;
    max-height: 80px;
    object-fit: cover;
  }
  
  /* Điều khiển số lượng */
  .quantity-control {
    display: flex;
    align-items: center;
    max-width: 100px;
    position: relative;
  }
  
  .quantity-input {
    width: 40px;
    height: 30px;
    text-align: center;
    margin: 0 5px;
    border: 1px solid #ddd;
    border-radius: 4px;
  }
  
  .btn-minus, .btn-plus {
    width: 30px;
    height: 30px;
    background-color: #f8f9fa;
    border: 1px solid #ddd;
    border-radius: 4px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    transition: all 0.2s;
  }
  
  .btn-minus:hover, .btn-plus:hover {
    background-color: #e9ecef;
  }
  
  .btn-minus:active, .btn-plus:active {
    transform: scale(0.95);
  }
  
  /* Nút xóa */
  .btn-remove {
    background-color: #dc3545;
    color: white;
    border: none;
    border-radius: 4px;
    width: 30px;
    height: 30px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
  }
  
  .btn-remove:hover {
    background-color: #c82333;
  }
  
  .btn-remove:active {
    transform: scale(0.95);
  }
  
  /* Giỏ hàng trống */
  .empty-cart-message {
    padding: 40px 0;
    text-align: center;
  }
  
  /* Hiệu ứng khi giá trị thay đổi */
  .highlight {
    animation: highlight-animation 1s ease;
  }
  
  @keyframes highlight-animation {
    0% { background-color: rgba(255, 193, 7, 0.3); }
    100% { background-color: transparent; }
  }
  
  /* Hiệu ứng khi xóa sản phẩm */
  .deleting {
    opacity: 0.5;
    position: relative;
  }
  
  .deleting:after {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.7);
    z-index: 1;
  }
</style>
@endsection

@section('scripts')
<!-- Stripe JS -->
<script src="https://js.stripe.com/v3/"></script>

<script>
  $(document).ready(function() {
  console.log('Cart script initialized'); // Debug log
  
  // Thêm sự kiện bắt lỗi AJAX toàn cục để xử lý tất cả các lỗi
  $(document).ajaxError(function(event, jqxhr, settings, thrownError) {
    console.error('Global AJAX error handler:', jqxhr.status, thrownError);
    console.error('Response text:', jqxhr.responseText);
    
    // Hiển thị thông báo lỗi bằng function showAlert
    showAlert('danger', 'Có lỗi xảy ra: ' + (jqxhr.status === 500 ? 'Lỗi máy chủ nội bộ' : thrownError));
  });
  
  // Initialize Stripe
  const stripe = Stripe('{{ config('services.stripe.key') }}');
  const elements = stripe.elements();
  
  // Create card element
  const cardElement = elements.create('card', {
    style: {
      base: {
        fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
        fontSmoothing: 'antialiased',
        fontSize: '16px',
        '::placeholder': {
          color: '#aab7c4'
        }
      },
      invalid: {
        color: '#fa755a',
        iconColor: '#fa755a'
      }
    }
  });
  
  // Mount the card element
  cardElement.mount('#card-element');
  
  // Handle real-time validation errors
  cardElement.on('change', function(event) {
    const displayError = document.getElementById('card-errors');
    if (event.error) {
      displayError.textContent = event.error.message;
    } else {
      displayError.textContent = '';
    }
  });
  
  // Toggle payment sections based on selected payment method
  $('input[name="payment_method"]').change(function() {
    const paymentMethod = $(this).val();
    
    // Hide all payment sections first
    $('#card-payment-section').hide();
    $('#bank-transfer-section').hide();
    
    // Show the relevant section
    if (paymentMethod === 'card') {
      $('#card-payment-section').show();
    } else if (paymentMethod === 'bank_transfer') {
      $('#bank-transfer-section').show();
    }
  });
  
  // Thiết lập token CSRF cho tất cả các yêu cầu AJAX
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
  // Ghi log token CSRF để debug
  console.log('CSRF Token found:', $('meta[name="csrf-token"]').attr('content') ? 'Yes' : 'No');
  
  // === CHỨC NĂNG CẬP NHẬT SỐ LƯỢNG ===
  
  // Xử lý nút tăng số lượng
  $(document).on('click', '.btn-plus', function() {
    console.log('Increase button clicked');
    const $input = $(this).siblings('.quantity-input');
    let value = parseInt($input.val());
    
    if (!isNaN(value) && value < 10) {
      // Tăng giá trị và cập nhật
      $input.val(value + 1);
      updateCartItem($(this).closest('.cart-item'));
    }
  });
  
  // Xử lý nút giảm số lượng
  $(document).on('click', '.btn-minus', function() {
    console.log('Decrease button clicked');
    const $input = $(this).siblings('.quantity-input');
    let value = parseInt($input.val());
    
    if (!isNaN(value) && value > 1) {
      // Giảm giá trị và cập nhật
      $input.val(value - 1);
      updateCartItem($(this).closest('.cart-item'));
    }
  });
  
  // Xử lý khi thay đổi input số lượng
  $(document).on('change', '.quantity-input', function() {
    console.log('Quantity input changed');
      let value = parseInt($(this).val());
    
    // Kiểm tra giá trị hợp lệ
      if (isNaN(value) || value < 1) {
        $(this).val(1);
      } else if (value > 10) {
        $(this).val(10);
      }
    
    // Cập nhật giỏ hàng
    updateCartItem($(this).closest('.cart-item'));
  });
  
  // Chỉ cho phép nhập số vào ô số lượng
  $(document).on('keypress', '.quantity-input', function(e) {
    if (!/^\d$/.test(e.key)) {
      e.preventDefault();
    }
  });
  
  // Hàm cập nhật số lượng sản phẩm trong giỏ hàng
  function updateCartItem($item) {
    const id = $item.data('id');
    const quantity = parseInt($item.find('.quantity-input').val());
    
    console.log('Updating cart item:', id, 'quantity:', quantity);
    
    // Hiển thị trạng thái đang cập nhật
    $item.find('.quantity-control').append('<div class="spinner-border spinner-border-sm ml-2 text-primary update-spinner" role="status"><span class="sr-only">Đang cập nhật...</span></div>');
    
    // Đường dẫn chính xác từ route Laravel
    const updateUrl = "/cart/update";
    console.log('Update URL:', updateUrl);
    
    // Gửi yêu cầu AJAX
    $.ajax({
      url: updateUrl,
      type: 'POST',
      data: { 
        id: id, 
        quantity: quantity,
        _token: $('meta[name="csrf-token"]').attr('content') // Thêm token CSRF rõ ràng
      },
      dataType: 'json',
      success: function(response) {
        console.log('Update response:', response);
        
        // Xóa hiệu ứng đang tải
        $('.update-spinner').remove();
        
        if (response && response.success) {
          // Cập nhật tổng giá tiền của sản phẩm
          $item.find('.item-total').text(formatNumber(response.item_total) + 'đ');
          
          // Cập nhật tổng giá trị đơn hàng
          $('#subtotal').text(formatNumber(response.subtotal) + 'đ');
          $('#total').text(formatNumber(response.total) + 'đ');
          
          // Hiệu ứng nhấp nháy để chỉ ra sự thay đổi
          $item.find('.item-total').addClass('highlight');
          $('#subtotal, #total').addClass('highlight');
          
          setTimeout(function() {
            $('.highlight').removeClass('highlight');
          }, 1000);
        } else {
          console.error('Invalid response format or success=false');
          showAlert('danger', 'Có lỗi xảy ra khi cập nhật giỏ hàng');
        }
      },
      error: function(xhr, status, error) {
        // Xóa hiệu ứng đang tải
        $('.update-spinner').remove();
        
        // Hiển thị lỗi chi tiết
        console.error('AJAX error:', status, error);
        console.error('Response text:', xhr.responseText);
        console.error('Response status:', xhr.status);
        
        // Thông báo lỗi cho người dùng
        showAlert('danger', 'Có lỗi xảy ra khi cập nhật giỏ hàng. Vui lòng thử lại!');
      }
    });
  }
  
  // === CHỨC NĂNG XÓA SẢN PHẨM ===
  
  // Biến lưu ID sản phẩm sẽ xóa
  let itemToRemove = null;
  
  // Xử lý nút xóa sản phẩm
  $(document).on('click', '.btn-remove', function() {
    console.log('Remove button clicked');
    itemToRemove = $(this).data('id');
    console.log('Item to remove:', itemToRemove);
    $('#delete-modal').modal('show');
  });
  
  // Xử lý xác nhận xóa
  $('#confirm-delete').on('click', function() {
    console.log('Confirm delete clicked, item ID:', itemToRemove);
    
    if (!itemToRemove) {
      console.error('No item ID to remove');
      return;
    }
    
    // Thêm hiệu ứng đang tải
    const $row = $(`.cart-item[data-id="${itemToRemove}"]`);
    $row.addClass('deleting');
    
    // Đường dẫn chính xác từ route Laravel
    const removeUrl = "/cart/remove";
    console.log('Remove URL:', removeUrl);
    
    // Gửi yêu cầu AJAX
        $.ajax({
      url: removeUrl,
      type: 'POST',
          data: {
        id: itemToRemove,
        _token: $('meta[name="csrf-token"]').attr('content') // Thêm token CSRF rõ ràng
          },
      dataType: 'json',
          success: function(response) {
        console.log('Remove response:', response);
        
        if (response && response.success) {
          // Xóa sản phẩm khỏi bảng với hiệu ứng
          $row.fadeOut(300, function() {
            $(this).remove();
            
            // Cập nhật tổng giá trị đơn hàng
            $('#subtotal').text(formatNumber(response.subtotal) + 'đ');
            $('#shipping').text(formatNumber(response.shipping) + 'đ');
            $('#tax').text(formatNumber(response.tax) + 'đ');
            $('#total').text(formatNumber(response.total) + 'đ');
            
            // Hiển thị giỏ hàng trống nếu không còn sản phẩm
            if (response.cart_empty) {
              $('#cart-items').html(`
                <tr id="empty-cart">
                  <td colspan="6" class="text-center py-4">
                    <div class="empty-cart-message">
                      <i class="icofont-shopping-cart" style="font-size: 3rem; color: #e9ecef;"></i>
                      <p class="mt-3 mb-0">Giỏ hàng của bạn trống</p>
                      <a href="{{ route('store') }}" class="btn btn-main-2 btn-sm mt-3">Tiếp tục mua sắm</a>
                    </div>
                  </td>
                </tr>
              `);
              
              // Vô hiệu hóa các nút
              $('#update-cart, #checkout-btn').prop('disabled', true);
            }
          });
          
          // Đóng modal và hiển thị thông báo
          $('#delete-modal').modal('hide');
          showAlert('success', 'Đã xóa sản phẩm khỏi giỏ hàng');
        } else {
          console.error('Invalid response format or success=false');
          $row.removeClass('deleting');
          $('#delete-modal').modal('hide');
          showAlert('danger', 'Có lỗi xảy ra khi xóa sản phẩm');
        }
      },
      error: function(xhr, status, error) {
        // Xóa hiệu ứng đang tải
        $row.removeClass('deleting');
        
        // Hiển thị lỗi chi tiết
        console.error('AJAX error:', status, error);
        console.error('Response text:', xhr.responseText);
        console.error('Response status:', xhr.status);
        
        // Đóng modal và thông báo lỗi
        $('#delete-modal').modal('hide');
        showAlert('danger', 'Có lỗi xảy ra khi xóa sản phẩm. Vui lòng thử lại!');
          }
        });
      });
  
  // === CHỨC NĂNG CẬP NHẬT TOÀN BỘ GIỎ HÀNG ===
  
  // Xử lý nút cập nhật giỏ hàng
  $('#update-cart').on('click', function() {
    console.log('Update cart button clicked');
    
    // Hiển thị thông báo cập nhật
    $(this).prop('disabled', true).html('<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span> Đang cập nhật...');
    
    // Giả lập cập nhật để tạo trải nghiệm người dùng tốt hơn
    setTimeout(function() {
      $('#update-cart').prop('disabled', false).html('<i class="icofont-refresh mr-1"></i> Cập nhật giỏ hàng');
      showAlert('success', 'Giỏ hàng đã được cập nhật thành công');
    }, 800);
  });
  
  // === CHỨC NĂNG THANH TOÁN ===
  
  // Xử lý nút thanh toán
  $('#checkout-btn').on('click', function() {
    console.log('Checkout button clicked');
    
    // Kiểm tra xem giỏ hàng có sản phẩm không
    if ($('.cart-item').length > 0) {
      // Hiển thị modal thanh toán
      $('#checkout-modal').modal('show');
    } else {
      showAlert('warning', 'Giỏ hàng của bạn trống, vui lòng thêm sản phẩm vào giỏ hàng');
    }
  });

  // Xử lý form thanh toán
  $('#checkout-form').on('submit', async function(e) {
    e.preventDefault();
    
    const paymentMethod = $('input[name="payment_method"]:checked').val();
    const address = $('#shipping-address').val().trim();
    const phone = $('#shipping-phone').val().trim();
    
    // Kiểm tra dữ liệu
    let isValid = true;
    
    if (!paymentMethod) {
      isValid = false;
      $('#payment-method-error').text('Vui lòng chọn phương thức thanh toán').show();
    } else {
      $('#payment-method-error').hide();
    }
    
    if (!address) {
      isValid = false;
      $('#address-error').text('Vui lòng nhập địa chỉ giao hàng').show();
    } else {
      $('#address-error').hide();
    }
    
    if (!phone) {
      isValid = false;
      $('#phone-error').text('Vui lòng nhập số điện thoại').show();
    } else {
      $('#phone-error').hide();
    }
    
    if (!isValid) {
      showAlert('danger', 'Vui lòng điền đầy đủ thông tin thanh toán');
      return;
    }
    
    // Hiển thị trạng thái đang xử lý
    $('#checkout-submit').prop('disabled', true).html('<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span> Đang xử lý...');
    
    // In ra console để debug
    console.log('Sending order with:', {
      payment_method: paymentMethod,
      address: address,
      phone: phone
    });
    
    try {
      // Chỉnh sửa phương thức thanh toán để phù hợp với định nghĩa trong database
      let paymentMethodValue;
      if (paymentMethod === 'cod') {
        paymentMethodValue = 'cash';
      } else if (paymentMethod === 'card') {
        paymentMethodValue = 'credit_card';
      } else {
        paymentMethodValue = paymentMethod;
      }
      
      if (paymentMethod === 'card') {
        // Create payment intent
        const response = await $.ajax({
          url: '/create-payment-intent',
          method: 'POST',
          data: {
            _token: '{{ csrf_token() }}',
            amount: {{ isset($total) ? $total : 0 }},
            currency: 'vnd'
          }
        });
        
        if (!response.success) {
          throw new Error(response.message || 'Không thể tạo thanh toán');
        }
        
        // Confirm card payment
        const result = await stripe.confirmCardPayment(response.client_secret, {
          payment_method: {
            card: cardElement,
            billing_details: {
              name: '{{ Auth::check() ? Auth::user()->name : "" }}',
              email: '{{ Auth::check() ? Auth::user()->email : "" }}',
              phone: phone,
              address: {
                line1: address
              }
            }
          }
        });
        
        if (result.error) {
          throw new Error(result.error.message);
        }
        
        // Add payment ID to form data
        $('#checkout-form').append('<input type="hidden" name="payment_id" value="' + result.paymentIntent.id + '">');
      }
      
      // Submit the form data via AJAX
      $.ajax({
        url: "/order/store",
        type: 'POST',
        data: {
          _token: '{{ csrf_token() }}',
          payment_method: paymentMethodValue,
          address: address,
          phone: phone
        },
        success: function(response) {
          console.log('Order response:', response);
          
          if (response.success) {
            // Ẩn modal thanh toán
            $('#checkout-modal').modal('hide');
            
            // Xóa tất cả sản phẩm khỏi giỏ hàng
            $('#cart-items').html(`
              <tr id="empty-cart">
                <td colspan="6" class="text-center py-4">
                  <div class="empty-cart-message">
                    <i class="icofont-shopping-cart" style="font-size: 3rem; color: #e9ecef;"></i>
                    <p class="mt-3 mb-0">Giỏ hàng của bạn trống</p>
                    <a href="{{ route('store') }}" class="btn btn-main-2 btn-sm mt-3">Tiếp tục mua sắm</a>
                  </div>
                </td>
              </tr>
            `);
            
            // Cập nhật tổng giá trị đơn hàng
            $('#subtotal').text('0đ');
            $('#shipping').text('0đ');
            $('#tax').text('0đ');
            $('#total').text('0đ');
            
            // Vô hiệu hóa các nút
            $('#update-cart, #checkout-btn').prop('disabled', true);
            
            // Hiển thị thông báo thành công
            showAlert('success', 'Đặt hàng thành công! Cảm ơn bạn đã mua sắm.');
            
            // Chuyển hướng đến trang xác nhận
            setTimeout(function() {
              window.location.href = response.redirect || "/confirmation";
            }, 1000);
          } else {
            // Hiển thị thông báo lỗi
            showAlert('danger', response.message || 'Đã xảy ra lỗi khi đặt hàng.');
            $('#checkout-submit').prop('disabled', false).text('Xác nhận đặt hàng');
          }
        },
        error: function(xhr, status, error) {
          // Hiển thị thông báo lỗi chi tiết
          console.error('AJAX error:', xhr.responseText);
          console.error('Status:', status);
          console.error('Error:', error);
          console.error('XHR Object:', xhr);
          
          let errorMessage = 'Đã xảy ra lỗi khi đặt hàng';
          
          if (xhr.responseJSON && xhr.responseJSON.message) {
            errorMessage += ': ' + xhr.responseJSON.message;
          } else if (xhr.responseText) {
            try {
              const errorResponse = JSON.parse(xhr.responseText);
              if (errorResponse.message) {
                errorMessage += ': ' + errorResponse.message;
              }
            } catch (e) {
              // Nếu không phải JSON, hiển thị phần đầu của responseText
              const responsePreview = xhr.responseText.substring(0, 100);
              errorMessage += ' (Server: ' + responsePreview + '...)';
            }
          }
          
          showAlert('danger', errorMessage);
          $('#checkout-submit').prop('disabled', false).text('Xác nhận đặt hàng');
        }
      });
    } catch (error) {
      console.error('Error:', error);
      showAlert('danger', 'Đã xảy ra lỗi: ' + error.message);
      $('#checkout-submit').prop('disabled', false).text('Xác nhận đặt hàng');
    }
  });
  
  // === CHỨC NĂNG MÃ GIẢM GIÁ ===
  
  // Xử lý nút áp dụng mã giảm giá
  $('.input-group-append button').on('click', function() {
    console.log('Apply coupon button clicked');
    const couponCode = $(this).closest('.input-group').find('input').val().trim();
    console.log('Coupon code:', couponCode);
    
    if (couponCode) {
      $(this).prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
      
      // Giả lập kiểm tra mã giảm giá
      setTimeout(function() {
        $('.input-group-append button').prop('disabled', false).text('Áp dụng');
        showAlert('info', 'Chức năng mã giảm giá đang được phát triển');
      }, 800);
    } else {
      showAlert('warning', 'Vui lòng nhập mã giảm giá');
    }
  });
  
  // === HÀM TIỆN ÍCH ===
  
  // Hàm định dạng số
  function formatNumber(number) {
      return new Intl.NumberFormat('vi-VN').format(number);
    }
    
  // Hàm hiển thị thông báo
    function showAlert(type, message) {
    console.log('Showing alert:', type, message);
    
      const alertHtml = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
          ${message}
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      `;
      
    $('#alert-container').html(alertHtml);
    $('html, body').animate({ scrollTop: 0 }, 'fast');
    
    // Tự động ẩn thông báo sau 5 giây
    setTimeout(function() {
      $('.alert').alert('close');
    }, 5000);
  }

  // Thêm fancybox cho hình sản phẩm
  $('[data-fancybox]').fancybox({
    buttons: [
      "zoom",
      "close"
    ],
    animationEffect: "zoom-in-out"
  });
  });
</script>
@endsection
