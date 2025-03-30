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
      </div>
    </div>

    <div class="row">
      <div class="col-lg-8">
        <div class="card shadow">
          <div class="card-body">
            <h4 class="mb-4">Giỏ hàng của tôi</h4>
            
            <!-- Cart items table -->
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
                <tbody>
                  <!-- Cart is empty state -->
                  <tr id="empty-cart" class="@if(isset($cartItems) && count($cartItems) > 0) d-none @endif">
                    <td colspan="6" class="text-center py-4">
                      <div class="empty-cart-container">
                        <i class="icofont-shopping-cart" style="font-size: 3rem; color: #e9ecef;"></i>
                        <p class="mt-3 mb-0">Giỏ hàng của bạn trống</p>
                        <a href="{{ route('store') }}" class="btn btn-main-2 btn-sm mt-3">Tiếp tục mua sắm</a>
                      </div>
                    </td>
                  </tr>
                  
                  <!-- Cart items -->
                  @if(isset($cartItems) && count($cartItems) > 0)
                    @foreach($cartItems as $id => $item)
                    <tr class="cart-item" data-id="{{ $id }}">
                      <td width="15%">
                        <img src="{{ asset($item['image']) }}" alt="{{ $item['name'] }}" class="img-fluid" style="max-width: 80px;">
                      </td>
                      <td width="30%">
                        <h6 class="mb-0">{{ $item['name'] }}</h6>
                      </td>
                      <td width="15%">{{ number_format($item['price'], 0, ',', '.') }}đ</td>
                      <td width="20%">
                        <div class="quantity-control d-flex align-items-center">
                          <button class="btn btn-sm btn-outline-secondary quantity-btn" data-action="decrease">-</button>
                          <input type="number" class="form-control form-control-sm mx-2 text-center quantity-input" value="{{ $item['quantity'] }}" min="1" max="10">
                          <button class="btn btn-sm btn-outline-secondary quantity-btn" data-action="increase">+</button>
                        </div>
                      </td>
                      <td width="15%" class="item-total">{{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}đ</td>
                      <td width="5%">
                        <button class="btn btn-sm btn-danger remove-item-btn" data-id="{{ $id }}">
                          <i class="icofont-trash"></i>
                        </button>
                      </td>
                    </tr>
                    @endforeach
                  @endif
                </tbody>
              </table>
            </div>
            
            <!-- Cart actions -->
            <div class="d-flex justify-content-between mt-4">
              <a href="{{ route('store') }}" class="btn btn-secondary">
                <i class="icofont-arrow-left mr-1"></i> Tiếp tục mua sắm
              </a>
              <button id="update-cart" class="btn btn-primary" @if(!isset($cartItems) || count($cartItems) == 0) disabled @endif>
                <i class="icofont-refresh mr-1"></i> Cập nhật giỏ hàng
              </button>
            </div>
          </div>
        </div>
      </div>
      
      <div class="col-lg-4 mt-4 mt-lg-0">
        <div class="card shadow">
          <div class="card-body">
            <h4 class="mb-4">Thông tin đơn hàng</h4>
            
            <div class="summary-item d-flex justify-content-between mb-2">
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
                  0đ
                @endif
              </span>
            </div>
            
            <div class="summary-item d-flex justify-content-between mb-2">
              <span>Phí vận chuyển</span>
              <span class="font-weight-bold" id="shipping">
                @if(isset($cartItems) && count($cartItems) > 0)
                  30.000đ
                @else
                  0đ
                @endif
              </span>
            </div>
            
            <div class="summary-item d-flex justify-content-between mb-2">
              <span>Thuế</span>
              <span class="font-weight-bold" id="tax">
                @if(isset($cartItems) && count($cartItems) > 0)
                  2.000đ
                @else
                  0đ
                @endif
              </span>
            </div>
            
            <hr>
            
            <div class="summary-item d-flex justify-content-between mb-3">
              <span class="h5">Tổng cộng</span>
              <span class="h5 text-primary" id="total">
                @if(isset($cartItems) && count($cartItems) > 0)
                  @php
                    $total = $subtotal + 30000 + 2000;
                  @endphp
                  {{ number_format($total, 0, ',', '.') }}đ
                @else
                  0đ
                @endif
              </span>
            </div>
            
            <button id="checkout-btn" class="btn btn-main btn-block" @if(!isset($cartItems) || count($cartItems) == 0) disabled @endif>
              Tiến hành thanh toán
            </button>
          </div>
        </div>
        
        <!-- Coupon code section -->
        <div class="card shadow mt-4">
          <div class="card-body">
            <h5>Mã giảm giá</h5>
            <div class="input-group mt-3">
              <input type="text" class="form-control" placeholder="Nhập mã giảm giá">
              <div class="input-group-append">
                <button class="btn btn-secondary" type="button">Áp dụng</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="delete-modal" tabindex="-1" role="dialog" aria-labelledby="delete-modal-title" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="delete-modal-title">Xác nhận xóa</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Bạn có muốn xóa sản phẩm khỏi giỏ hàng?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Không</button>
        <button type="button" class="btn btn-danger" id="confirm-delete">Có</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
  $(document).ready(function() {
    // Setup CSRF token for AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    // Update quantities
    $('.quantity-btn').on('click', function() {
      const action = $(this).data('action');
      const inputElement = $(this).closest('.quantity-control').find('.quantity-input');
      let value = parseInt(inputElement.val());
      
      if (action === 'increase' && value < 10) {
        inputElement.val(value + 1);
      } else if (action === 'decrease' && value > 1) {
        inputElement.val(value - 1);
      }
    });
    
    // Quantity manual input
    $('.quantity-input').on('change', function() {
      let value = parseInt($(this).val());
      if (isNaN(value) || value < 1) {
        $(this).val(1);
      } else if (value > 10) {
        $(this).val(10);
      }
    });
    
    // Update cart button
    $('#update-cart').on('click', function() {
      const updates = [];
      
      // Collect all items and their quantities
      $('.cart-item').each(function() {
        const id = $(this).data('id');
        const quantity = parseInt($(this).find('.quantity-input').val());
        
        if (!isNaN(quantity) && quantity > 0) {
          updates.push({ id, quantity });
        }
      });
      
      // Update each item one by one
      let updateCount = 0;
      
      updates.forEach(function(item) {
        $.ajax({
          url: "{{ route('user.cart.update') }}",
          method: "POST",
          data: {
            id: item.id,
            quantity: item.quantity
          },
          success: function(response) {
            if (response.success) {
              // Update item total
              const row = $(`.cart-item[data-id="${item.id}"]`);
              row.find('.item-total').text(numberFormat(response.item_total) + 'đ');
              
              updateCount++;
              
              // If all items updated, update totals and show message
              if (updateCount === updates.length) {
                $('#subtotal').text(numberFormat(response.subtotal) + 'đ');
                $('#total').text(numberFormat(response.total) + 'đ');
                
                // Show success message
                showAlert('success', 'Giỏ hàng đã được cập nhật thành công');
              }
            }
          },
          error: function(xhr) {
            console.error('Error updating cart:', xhr);
            showAlert('danger', 'Có lỗi xảy ra khi cập nhật giỏ hàng');
          }
        });
      });
    });
    
    // Remove item button
    $('.remove-item-btn').on('click', function() {
      const id = $(this).data('id');
      $('#delete-modal').modal('show');
      
      // Set the item ID to be deleted
      $('#confirm-delete').data('id', id);
    });
    
    // Confirm delete
    $('#confirm-delete').on('click', function() {
      const id = $(this).data('id');
      
      $.ajax({
        url: "{{ route('user.cart.remove') }}",
        method: "POST",
        data: {
          id: id
        },
        success: function(response) {
          if (response.success) {
            // Remove the item from the table
            $(`.cart-item[data-id="${id}"]`).remove();
            
            // Update totals
            $('#subtotal').text(numberFormat(response.subtotal) + 'đ');
            $('#shipping').text(numberFormat(response.shipping) + 'đ');
            $('#tax').text(numberFormat(response.tax) + 'đ');
            $('#total').text(numberFormat(response.total) + 'đ');
            
            // If cart is empty, show empty cart message
            if (response.cart_empty) {
              $('#empty-cart').removeClass('d-none');
              $('#update-cart').prop('disabled', true);
              $('#checkout-btn').prop('disabled', true);
            }
            
            // Close the modal
            $('#delete-modal').modal('hide');
            
            // Show success message
            showAlert('success', response.message);
          }
        },
        error: function(xhr) {
          console.error('Error removing item from cart:', xhr);
          $('#delete-modal').modal('hide');
          showAlert('danger', 'Có lỗi xảy ra khi xóa sản phẩm');
        }
      });
    });
    
    // Helper function to format numbers
    function numberFormat(number) {
      return new Intl.NumberFormat('vi-VN').format(number);
    }
    
    // Helper function to show alerts
    function showAlert(type, message) {
      const alertHtml = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
          ${message}
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      `;
      
      $('.col-lg-12').html(alertHtml);
      
      // Scroll to the top to see the alert
      $('html, body').animate({ scrollTop: 0 }, 'slow');
    }
  });
</script>

<style>
  .quantity-control {
    max-width: 120px;
  }
  
  .quantity-input {
    width: 40px;
  }
  
  .empty-cart-container {
    padding: 30px 0;
  }
</style>
@endsection
