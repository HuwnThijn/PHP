@extends('layouts.pharmacist')

@section('title', 'Xử lý đơn thuốc')

@section('page-title', 'Xử lý đơn thuốc')

@section('styles')
<style>
    .medicine-item {
        border: 1px solid #e3e6f0;
        border-radius: 0.35rem;
        padding: 15px;
        margin-bottom: 10px;
        transition: all 0.3s;
    }
    
    .medicine-item:hover {
        box-shadow: 0 .15rem 1.75rem 0 rgba(58,59,69,.1);
    }
    
    .medicine-item.selected {
        border-color: #4e73df;
        background-color: #f8f9fc;
    }
    
    .medicine-item .stock {
        font-weight: bold;
    }
    
    .medicine-item .stock.low {
        color: #e74a3b;
    }
    
    .medicine-item .stock.ok {
        color: #1cc88a;
    }
</style>
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <a href="{{ route('pharmacist.dashboard') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Thông tin bệnh nhân</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Bệnh nhân:</strong> {{ $medicalRecord->patient->name }}
                </div>
                <div class="mb-3">
                    <strong>Email:</strong> {{ $medicalRecord->patient->email }}
                </div>
                <div class="mb-3">
                    <strong>Số điện thoại:</strong> {{ $medicalRecord->patient->phone }}
                </div>
                <div class="mb-3">
                    <strong>Bác sĩ phụ trách:</strong> {{ $medicalRecord->doctor->name }}
                </div>
                <div class="mb-3">
                    <strong>Ngày khám:</strong> {{ $medicalRecord->created_at->format('d/m/Y H:i') }}
                </div>
            </div>
        </div>
        
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Thông tin bệnh án</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Chẩn đoán:</strong> 
                    <p>{{ $medicalRecord->diagnosis ?? 'Chưa có chẩn đoán' }}</p>
                </div>
                <div class="mb-3">
                    <strong>Ghi chú:</strong>
                    <p>{{ $medicalRecord->notes ?? 'Không có ghi chú' }}</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Xử lý đơn thuốc</h6>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                <form action="{{ route('pharmacist.patients.complete', $medicalRecord->id_medical_record) }}" method="POST" id="prescriptionForm">
                    @csrf
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" class="form-control" id="searchMedicine" placeholder="Tìm kiếm thuốc...">
                                <button class="btn btn-outline-secondary" type="button" id="clearSearch">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="medicine-list">
                                @foreach($medicines as $medicine)
                                <div class="medicine-item" data-medicine-id="{{ $medicine->id }}" data-medicine-name="{{ $medicine->name }}" data-medicine-price="{{ $medicine->price }}">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <h5>{{ $medicine->name }}</h5>
                                            <p class="text-muted mb-1">{{ $medicine->dosage_form }} - {{ $medicine->manufacturer }}</p>
                                            <p class="mb-1">
                                                <span class="stock {{ $medicine->stock_quantity < 10 ? 'low' : 'ok' }}">
                                                    <i class="fas {{ $medicine->stock_quantity < 10 ? 'fa-exclamation-triangle' : 'fa-check-circle' }}"></i>
                                                    Còn lại: {{ $medicine->stock_quantity }}
                                                </span>
                                            </p>
                                            <p class="mb-0"><strong>{{ number_format($medicine->price, 0, ',', '.') }} VNĐ</strong></p>
                                        </div>
                                        <div class="col-md-4 d-flex align-items-center justify-content-end">
                                            <button type="button" class="btn btn-primary add-medicine">
                                                <i class="fas fa-plus"></i> Thêm
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <h4 class="mt-4 mb-3">Đơn thuốc</h4>
                    
                    <div class="selected-medicines mb-4">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Thuốc</th>
                                    <th width="100">Số lượng</th>
                                    <th>Liều dùng</th>
                                    <th>Đơn giá</th>
                                    <th>Thành tiền</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody id="selectedMedicinesList">
                                <tr id="emptyRow">
                                    <td colspan="6" class="text-center">Chưa có thuốc nào được chọn</td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="4" class="text-end">Tổng cộng:</th>
                                    <th id="totalAmount">0 VNĐ</th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Ghi chú</label>
                            <textarea class="form-control" name="notes" rows="3"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phương thức thanh toán</label>
                            <select class="form-select" name="payment_method" required>
                                <option value="cash">Tiền mặt</option>
                                <option value="credit_card">Thẻ tín dụng</option>
                                <option value="bank_transfer">Chuyển khoản</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="text-end">
                        <button type="submit" class="btn btn-success btn-lg" id="completeBtn" disabled>
                            <i class="fas fa-check-circle"></i> Hoàn tất và thu tiền
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        let totalAmount = 0;
        let selectedMedicines = [];
        
        // Tìm kiếm thuốc
        $('#searchMedicine').on('keyup', function() {
            const searchText = $(this).val().toLowerCase();
            
            $('.medicine-item').each(function() {
                const medicineName = $(this).data('medicine-name').toLowerCase();
                if (medicineName.includes(searchText)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });
        
        // Xóa tìm kiếm
        $('#clearSearch').on('click', function() {
            $('#searchMedicine').val('');
            $('.medicine-item').show();
        });
        
        // Thêm thuốc vào đơn
        $('.add-medicine').on('click', function() {
            const medicineItem = $(this).closest('.medicine-item');
            const medicineId = medicineItem.data('medicine-id');
            const medicineName = medicineItem.data('medicine-name');
            const medicinePrice = medicineItem.data('medicine-price');
            
            // Kiểm tra xem thuốc đã được thêm vào đơn thuốc chưa
            if (selectedMedicines.includes(medicineId)) {
                // Tăng số lượng thuốc đã có
                const quantityInput = $(`#quantity_${medicineId}`);
                const currentQty = parseInt(quantityInput.val());
                quantityInput.val(currentQty + 1);
                updateMedicineAmount(medicineId);
            } else {
                // Thêm thuốc mới vào đơn
                selectedMedicines.push(medicineId);
                
                if ($('#emptyRow').length) {
                    $('#emptyRow').remove();
                }
                
                const newRow = `
                    <tr id="medicine_${medicineId}">
                        <td>${medicineName}</td>
                        <td>
                            <input type="number" class="form-control form-control-sm quantity-input" 
                                id="quantity_${medicineId}" name="items[${medicineId}][quantity]" 
                                value="1" min="1" data-price="${medicinePrice}" data-id="${medicineId}">
                            <input type="hidden" name="items[${medicineId}][medicine_id]" value="${medicineId}">
                        </td>
                        <td>
                            <input type="text" class="form-control form-control-sm" 
                                name="dosage[${medicineId}]" placeholder="Liều dùng">
                        </td>
                        <td>${new Intl.NumberFormat('vi-VN').format(medicinePrice)} VNĐ</td>
                        <td class="medicine-amount">${new Intl.NumberFormat('vi-VN').format(medicinePrice)} VNĐ</td>
                        <td>
                            <button type="button" class="btn btn-sm btn-danger remove-medicine" data-id="${medicineId}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
                
                $('#selectedMedicinesList').append(newRow);
                updateTotalAmount();
            }
            
            // Bật nút hoàn tất
            $('#completeBtn').prop('disabled', false);
        });
        
        // Xóa thuốc khỏi đơn
        $(document).on('click', '.remove-medicine', function() {
            const medicineId = $(this).data('id');
            
            // Xóa khỏi mảng
            selectedMedicines = selectedMedicines.filter(id => id !== medicineId);
            
            // Xóa dòng
            $(`#medicine_${medicineId}`).remove();
            
            // Cập nhật tổng tiền
            updateTotalAmount();
            
            // Kiểm tra nếu không còn thuốc nào
            if (selectedMedicines.length === 0) {
                $('#selectedMedicinesList').html('<tr id="emptyRow"><td colspan="6" class="text-center">Chưa có thuốc nào được chọn</td></tr>');
                $('#completeBtn').prop('disabled', true);
            }
        });
        
        // Cập nhật số lượng thuốc
        $(document).on('change', '.quantity-input', function() {
            const medicineId = $(this).data('id');
            updateMedicineAmount(medicineId);
        });
        
        // Hàm cập nhật thành tiền của từng thuốc
        function updateMedicineAmount(medicineId) {
            const quantityInput = $(`#quantity_${medicineId}`);
            const quantity = parseInt(quantityInput.val());
            const price = parseFloat(quantityInput.data('price'));
            const amount = quantity * price;
            
            $(`#medicine_${medicineId} .medicine-amount`).text(new Intl.NumberFormat('vi-VN').format(amount) + ' VNĐ');
            
            updateTotalAmount();
        }
        
        // Hàm cập nhật tổng tiền
        function updateTotalAmount() {
            totalAmount = 0;
            
            $('.quantity-input').each(function() {
                const quantity = parseInt($(this).val());
                const price = parseFloat($(this).data('price'));
                totalAmount += quantity * price;
            });
            
            $('#totalAmount').text(new Intl.NumberFormat('vi-VN').format(totalAmount) + ' VNĐ');
        }
        
        // Kiểm tra trước khi submit
        $('#prescriptionForm').on('submit', function(e) {
            if (selectedMedicines.length === 0) {
                e.preventDefault();
                alert('Vui lòng chọn ít nhất một thuốc!');
                return false;
            }
            
            return true;
        });
    });
</script>
@endsection 