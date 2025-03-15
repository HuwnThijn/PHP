@extends('layouts.pharmacist')

@section('title', 'Tạo đơn hàng mới')

@section('page-title', 'Tạo đơn hàng mới')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Thông tin đơn hàng</h6>
            </div>
            <div class="card-body">
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <form action="{{ route('pharmacist.orders.store') }}" method="POST" id="orderForm">
                    @csrf
                    <div class="form-group">
                        <label for="patient_id">Chọn khách hàng</label>
                        <select class="form-control @error('patient_id') is-invalid @enderror" id="patient_id" name="patient_id" required>
                            <option value="">-- Chọn khách hàng --</option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient->id_user }}">{{ $patient->name }} ({{ $patient->phone }})</option>
                            @endforeach
                        </select>
                        @error('patient_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="payment_method">Phương thức thanh toán</label>
                        <select class="form-control @error('payment_method') is-invalid @enderror" id="payment_method" name="payment_method" required>
                            <option value="cash">Tiền mặt</option>
                            <option value="card">Thẻ</option>
                            <option value="transfer">Chuyển khoản</option>
                        </select>
                        @error('payment_method')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="card mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Danh sách thuốc</h6>
                            <button type="button" class="btn btn-sm btn-primary" id="addItemBtn">
                                <i class="fas fa-plus"></i> Thêm thuốc
                            </button>
                        </div>
                        <div class="card-body">
                            <div id="itemsContainer">
                                <div class="item-row mb-3">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <select class="form-control medicine-select" name="items[0][medicine_id]" required>
                                                <option value="">-- Chọn thuốc --</option>
                                                @foreach($medicines as $medicine)
                                                    <option value="{{ $medicine->id }}" data-price="{{ $medicine->price }}" data-stock="{{ $medicine->stock_quantity }}">
                                                        {{ $medicine->name }} ({{ number_format($medicine->price) }} VNĐ)
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="number" class="form-control quantity-input" name="items[0][quantity]" placeholder="Số lượng" min="1" required>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control item-price" placeholder="Thành tiền" readonly>
                                        </div>
                                        <div class="col-md-1">
                                            <button type="button" class="btn btn-danger btn-sm remove-item" disabled>
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="stock-info mt-1 text-info"></div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-8 text-right">
                                    <strong>Tổng cộng:</strong>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" class="form-control" id="totalAmount" readonly>
                                </div>
                                <div class="col-md-1"></div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Lưu đơn hàng
                    </button>
                    <a href="{{ route('pharmacist.orders.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </a>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Hướng dẫn</h6>
            </div>
            <div class="card-body">
                <p>Để tạo đơn hàng mới, vui lòng thực hiện các bước sau:</p>
                <ol>
                    <li>Chọn khách hàng từ danh sách</li>
                    <li>Chọn phương thức thanh toán</li>
                    <li>Thêm thuốc vào đơn hàng:
                        <ul>
                            <li>Chọn thuốc từ danh sách</li>
                            <li>Nhập số lượng</li>
                            <li>Thêm thuốc khác nếu cần</li>
                        </ul>
                    </li>
                    <li>Kiểm tra tổng tiền</li>
                    <li>Nhấn "Lưu đơn hàng" để hoàn tất</li>
                </ol>
                <p class="text-danger">Lưu ý: Hệ thống sẽ tự động kiểm tra tồn kho. Nếu số lượng yêu cầu vượt quá tồn kho, bạn sẽ không thể lưu đơn hàng.</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        let itemCount = 1;
        
        // Thêm thuốc mới
        $('#addItemBtn').click(function() {
            const newItem = `
                <div class="item-row mb-3">
                    <div class="row">
                        <div class="col-md-5">
                            <select class="form-control medicine-select" name="items[${itemCount}][medicine_id]" required>
                                <option value="">-- Chọn thuốc --</option>
                                @foreach($medicines as $medicine)
                                    <option value="{{ $medicine->id }}" data-price="{{ $medicine->price }}" data-stock="{{ $medicine->stock_quantity }}">
                                        {{ $medicine->name }} ({{ number_format($medicine->price) }} VNĐ)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="number" class="form-control quantity-input" name="items[${itemCount}][quantity]" placeholder="Số lượng" min="1" required>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control item-price" placeholder="Thành tiền" readonly>
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-danger btn-sm remove-item">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="stock-info mt-1 text-info"></div>
                </div>
            `;
            
            $('#itemsContainer').append(newItem);
            itemCount++;
            
            // Kích hoạt nút xóa cho tất cả các hàng
            $('.remove-item').prop('disabled', $('.item-row').length <= 1);
        });
        
        // Xóa thuốc
        $(document).on('click', '.remove-item', function() {
            $(this).closest('.item-row').remove();
            calculateTotal();
            
            // Vô hiệu hóa nút xóa nếu chỉ còn 1 hàng
            $('.remove-item').prop('disabled', $('.item-row').length <= 1);
        });
        
        // Tính toán thành tiền khi chọn thuốc hoặc thay đổi số lượng
        $(document).on('change', '.medicine-select, .quantity-input', function() {
            const row = $(this).closest('.item-row');
            const select = row.find('.medicine-select');
            const quantityInput = row.find('.quantity-input');
            const priceInput = row.find('.item-price');
            const stockInfo = row.find('.stock-info');
            
            if (select.val() && quantityInput.val()) {
                const selectedOption = select.find('option:selected');
                const price = parseFloat(selectedOption.data('price'));
                const stock = parseInt(selectedOption.data('stock'));
                const quantity = parseInt(quantityInput.val());
                
                // Hiển thị thông tin tồn kho
                stockInfo.text(`Tồn kho: ${stock}`);
                
                // Kiểm tra tồn kho
                if (quantity > stock) {
                    stockInfo.removeClass('text-info').addClass('text-danger');
                    stockInfo.text(`Tồn kho không đủ! Hiện chỉ còn ${stock} sản phẩm.`);
                } else {
                    stockInfo.removeClass('text-danger').addClass('text-info');
                }
                
                // Tính thành tiền
                const total = price * quantity;
                priceInput.val(formatCurrency(total));
                
                // Cập nhật tổng tiền
                calculateTotal();
            } else {
                priceInput.val('');
                stockInfo.text('');
            }
        });
        
        // Tính tổng tiền
        function calculateTotal() {
            let total = 0;
            $('.item-row').each(function() {
                const select = $(this).find('.medicine-select');
                const quantity = $(this).find('.quantity-input').val();
                
                if (select.val() && quantity) {
                    const price = parseFloat(select.find('option:selected').data('price'));
                    total += price * parseInt(quantity);
                }
            });
            
            $('#totalAmount').val(formatCurrency(total));
        }
        
        // Format tiền tệ
        function formatCurrency(amount) {
            return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount);
        }
        
        // Kiểm tra form trước khi submit
        $('#orderForm').submit(function(e) {
            let hasError = false;
            
            // Kiểm tra xem có thuốc nào vượt quá tồn kho không
            $('.item-row').each(function() {
                const select = $(this).find('.medicine-select');
                const quantity = $(this).find('.quantity-input').val();
                
                if (select.val() && quantity) {
                    const stock = parseInt(select.find('option:selected').data('stock'));
                    if (parseInt(quantity) > stock) {
                        hasError = true;
                        return false; // break the loop
                    }
                }
            });
            
            if (hasError) {
                e.preventDefault();
                alert('Không thể tạo đơn hàng vì có sản phẩm vượt quá số lượng tồn kho!');
            }
        });
    });
</script>
@endsection 