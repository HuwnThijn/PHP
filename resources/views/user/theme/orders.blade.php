@extends('user.theme.auth-layout')

@section('title', 'Order History')

@section('content')
<section class="page-title bg-1">
  <div class="overlay"></div>
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="block text-center">
          <span class="text-white">Order History</span>
          <h1 class="text-capitalize mb-5 text-lg">My Orders</h1>
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
            <h4 class="mb-4">My Orders</h4>
            
            <!-- Filters and search -->
            <div class="row mb-4">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="status-filter">Filter by Status</label>
                  <select class="form-control" id="status-filter">
                    <option value="all">All Orders</option>
                    <option value="pending">Pending</option>
                    <option value="processing">Processing</option>
                    <option value="shipped">Shipped</option>
                    <option value="delivered">Delivered</option>
                    <option value="cancelled">Cancelled</option>
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="order-search">Search Orders</label>
                  <input type="text" class="form-control" id="order-search" placeholder="Search by Order ID or Product">
                </div>
              </div>
            </div>
            
            <!-- Orders table -->
            <div class="table-responsive">
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th>Order ID</th>
                    <th>Date</th>
                    <th>Items</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- No orders state -->
                  <tr id="no-orders" class="@if(isset($orders) && count($orders) > 0) d-none @endif">
                    <td colspan="6" class="text-center py-4">
                      <div class="empty-orders-container">
                        <i class="icofont-file-document" style="font-size: 3rem; color: #e9ecef;"></i>
                        <p class="mt-3 mb-0">You haven't placed any orders yet</p>
                        <a href="{{ route('store') }}" class="btn btn-main-2 btn-sm mt-3">Shop Now</a>
                      </div>
                    </td>
                  </tr>
                  
                  <!-- Sample orders (these would be populated from controller) -->
                  @if(isset($orders) && count($orders) > 0)
                    @foreach($orders as $order)
                    <tr>
                      <td>
                        <span class="font-weight-bold">{{ $order->order_number }}</span>
                      </td>
                      <td>{{ $order->created_at->format('M d, Y') }}</td>
                      <td>{{ $order->item_count }} items</td>
                      <td>${{ number_format($order->total, 2) }}</td>
                      <td>
                        <span class="badge 
                          @if($order->status == 'delivered') badge-success
                          @elseif($order->status == 'pending') badge-warning
                          @elseif($order->status == 'cancelled') badge-danger
                          @elseif($order->status == 'processing') badge-info
                          @elseif($order->status == 'shipped') badge-primary
                          @endif">
                          {{ ucfirst($order->status) }}
                        </span>
                      </td>
                      <td>
                        <button class="btn btn-sm btn-primary view-order-btn" data-order-id="{{ $order->id }}">
                          <i class="icofont-eye-alt mr-1"></i> View Details
                        </button>
                      </td>
                    </tr>
                    @endforeach
                  @endif
                </tbody>
              </table>
            </div>
            
            <!-- Pagination -->
            @if(isset($orders) && $orders->hasPages())
            <div class="d-flex justify-content-center mt-4">
              {{ $orders->links() }}
            </div>
            @endif
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
        <h5 class="modal-title" id="orderDetailsModalLabel">Order Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- Order details content will be loaded here dynamically -->
        <div class="order-details-container">
          <div class="row">
            <div class="col-md-6">
              <h6>Order Information</h6>
              <p><strong>Order ID:</strong> <span id="modal-order-id"></span></p>
              <p><strong>Date:</strong> <span id="modal-order-date"></span></p>
              <p><strong>Status:</strong> <span id="modal-order-status"></span></p>
              <p><strong>Payment Method:</strong> <span id="modal-payment-method"></span></p>
            </div>
            <div class="col-md-6">
              <h6>Shipping Address</h6>
              <p id="modal-shipping-address"></p>
            </div>
          </div>
          
          <hr>
          
          <h6>Order Items</h6>
          <div class="table-responsive">
            <table class="table table-sm">
              <thead>
                <tr>
                  <th>Product</th>
                  <th>Price</th>
                  <th>Quantity</th>
                  <th>Total</th>
                </tr>
              </thead>
              <tbody id="modal-order-items">
                <!-- Order items will be loaded here dynamically -->
              </tbody>
              <tfoot>
                <tr>
                  <td colspan="3" class="text-right font-weight-bold">Subtotal:</td>
                  <td id="modal-subtotal"></td>
                </tr>
                <tr>
                  <td colspan="3" class="text-right font-weight-bold">Shipping:</td>
                  <td id="modal-shipping"></td>
                </tr>
                <tr>
                  <td colspan="3" class="text-right font-weight-bold">Tax:</td>
                  <td id="modal-tax"></td>
                </tr>
                <tr>
                  <td colspan="3" class="text-right font-weight-bold">Total:</td>
                  <td id="modal-total" class="font-weight-bold"></td>
                </tr>
              </tfoot>
            </table>
          </div>
          
          <div id="tracking-info" class="mt-4 d-none">
            <h6>Tracking Information</h6>
            <p><strong>Tracking Number:</strong> <span id="modal-tracking-number"></span></p>
            <p><strong>Carrier:</strong> <span id="modal-carrier"></span></p>
            <p><strong>Estimated Delivery:</strong> <span id="modal-delivery-date"></span></p>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="track-order-btn">Track Order</button>
        <button type="button" class="btn btn-danger" id="cancel-order-btn">Cancel Order</button>
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
      
      $('tbody tr:not(#no-orders)').each(function() {
        const orderIdText = $(this).find('td:first-child').text().toLowerCase();
        const orderContent = $(this).text().toLowerCase();
        
        if (orderIdText.includes(searchTerm) || orderContent.includes(searchTerm)) {
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
        $('tbody tr:not(#no-orders)').show();
      } else {
        $('tbody tr:not(#no-orders)').each(function() {
          const statusText = $(this).find('td:nth-child(5) span').text().toLowerCase();
          
          if (statusText === selectedStatus) {
            $(this).show();
          } else {
            $(this).hide();
          }
        });
      }
      
      checkVisibleOrders();
    });
    
    // Check if there are any visible orders
    function checkVisibleOrders() {
      const visibleOrdersCount = $('tbody tr:not(#no-orders):visible').length;
      
      if (visibleOrdersCount === 0) {
        $('#no-orders').removeClass('d-none').find('p').text('No orders match your search criteria');
      } else {
        $('#no-orders').addClass('d-none');
      }
    }
    
    // View order details
    $('.view-order-btn').on('click', function() {
      const orderId = $(this).data('order-id');
      
      // In a real application, you would fetch the order details from the server
      // For this demo, we'll populate with sample data
      
      $('#modal-order-id').text('ORD-' + orderId);
      $('#modal-order-date').text('March 15, 2023');
      $('#modal-order-status').text('Shipped');
      $('#modal-payment-method').text('Credit Card');
      $('#modal-shipping-address').text('John Doe\n123 Main Street\nApt 4B\nNew York, NY 10001\nUnited States');
      
      // Sample order items
      const orderItems = `
        <tr>
          <td>Skin Cleanser</td>
          <td>$25.00</td>
          <td>1</td>
          <td>$25.00</td>
        </tr>
        <tr>
          <td>Moisturizer</td>
          <td>$35.00</td>
          <td>1</td>
          <td>$35.00</td>
        </tr>
      `;
      
      $('#modal-order-items').html(orderItems);
      $('#modal-subtotal').text('$60.00');
      $('#modal-shipping').text('$5.00');
      $('#modal-tax').text('$6.00');
      $('#modal-total').text('$71.00');
      
      // Show tracking info for shipped orders
      if ($('#modal-order-status').text() === 'Shipped') {
        $('#tracking-info').removeClass('d-none');
        $('#modal-tracking-number').text('TRK123456789');
        $('#modal-carrier').text('UPS');
        $('#modal-delivery-date').text('March 21, 2023');
        $('#track-order-btn').show();
      } else {
        $('#tracking-info').addClass('d-none');
        $('#track-order-btn').hide();
      }
      
      // Hide cancel button for delivered or cancelled orders
      if (['Delivered', 'Cancelled'].includes($('#modal-order-status').text())) {
        $('#cancel-order-btn').hide();
      } else {
        $('#cancel-order-btn').show();
      }
      
      $('#order-details-modal').modal('show');
    });
    
    // Track order button
    $('#track-order-btn').on('click', function() {
      const trackingNumber = $('#modal-tracking-number').text();
      const carrier = $('#modal-carrier').text();
      
      // In a real application, this would redirect to the carrier's tracking page
      alert(`Tracking order ${trackingNumber} with ${carrier}`);
    });
    
    // Cancel order button
    $('#cancel-order-btn').on('click', function() {
      const orderId = $('#modal-order-id').text();
      
      if (confirm(`Are you sure you want to cancel order ${orderId}?`)) {
        // In a real application, this would send a request to cancel the order
        alert(`Order ${orderId} has been cancelled`);
        $('#order-details-modal').modal('hide');
      }
    });
  });
</script>
@endsection
