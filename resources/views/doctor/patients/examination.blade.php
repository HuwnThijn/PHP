@extends('doctor.layouts.app')

@section('title', 'Khám bệnh')

@section('page-title', 'Khám bệnh - ' . $medicalRecord->patient->name)

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
        border-color: #36b9cc;
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
    
    #selected-medicines {
        max-height: 400px;
        overflow-y: auto;
    }
</style>
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <a href="{{ route('doctor.patients.pending') }}" class="btn btn-secondary">
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
                    <strong>Địa chỉ:</strong> {{ $medicalRecord->patient->address ?? 'Không có' }}
                </div>
                <div class="mb-3">
                    <strong>Ngày tiếp nhận:</strong> {{ $medicalRecord->created_at->format('d/m/Y H:i') }}
                </div>
                <div class="mb-3">
                    <strong>Ghi chú ban đầu:</strong>
                    <p>{{ $medicalRecord->notes ?? 'Không có ghi chú' }}</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-8">
        <form action="{{ route('doctor.patients.save_examination', $medicalRecord->id_medical_record) }}" method="POST">
            @csrf
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Kết quả khám bệnh</h6>
                </div>
                <div class="card-body">
                    <div class="form-group mb-3">
                        <label for="diagnosis" class="form-label">Chẩn đoán <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="diagnosis" name="diagnosis" rows="3" required>{{ old('diagnosis') }}</textarea>
                        @error('diagnosis')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="notes" class="form-label">Ghi chú</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>
            
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Kê đơn thuốc</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col">
                            <div class="input-group">
                                <input type="text" class="form-control" id="searchMedicine" placeholder="Tìm kiếm thuốc...">
                                <button class="btn btn-outline-secondary" type="button">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="mb-3">Danh sách thuốc</h6>
                            <div id="medicine-list" style="max-height: 400px; overflow-y: auto;">
                                @foreach($medicines as $medicine)
                                <div class="medicine-item" data-id="{{ $medicine->id }}" data-name="{{ $medicine->name }}" data-price="{{ $medicine->price }}">
                                    <h6>{{ $medicine->name }}</h6>
                                    <div>
                                        <small>Giá: {{ number_format($medicine->price) }} VNĐ</small>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                        <div class="stock {{ $medicine->stock_quantity < 10 ? 'low' : 'ok' }}">
                                            Tồn kho: {{ $medicine->stock_quantity }}
                                        </div>
                                        <button type="button" class="btn btn-sm btn-info add-medicine">
                                            <i class="fas fa-plus"></i> Thêm
                                        </button>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <h6 class="mb-3">Thuốc đã chọn</h6>
                            <div id="selected-medicines">
                                <div class="text-center text-muted py-3" id="no-medicine-selected">
                                    <i class="fas fa-prescription-bottle-alt fa-3x mb-3"></i>
                                    <p>Chưa có thuốc nào được chọn</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Các liệu trình điều trị</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($treatments->chunk(ceil($treatments->count() / 2)) as $chunk)
                        <div class="col-md-6">
                            @foreach($chunk as $treatment)
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="treatment{{ $treatment->id }}" name="treatments[]" value="{{ $treatment->id }}">
                                <label class="form-check-label" for="treatment{{ $treatment->id }}">
                                    {{ $treatment->name }} ({{ number_format($treatment->price) }} VNĐ)
                                </label>
                            </div>
                            @endforeach
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <div class="text-center mb-4">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-save"></i> Lưu kết quả khám và kê đơn
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Tìm kiếm thuốc
        $("#searchMedicine").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#medicine-list .medicine-item").filter(function() {
                $(this).toggle($(this).data('name').toLowerCase().indexOf(value) > -1);
            });
        });
        
        // Thêm thuốc vào danh sách đã chọn
        $(".add-medicine").on("click", function() {
            var medicineItem = $(this).closest('.medicine-item');
            var medicineId = medicineItem.data('id');
            var medicineName = medicineItem.data('name');
            var medicinePrice = medicineItem.data('price');
            
            // Kiểm tra xem thuốc đã được chọn chưa
            if ($("#selected-medicine-" + medicineId).length) {
                // Nếu đã chọn, tăng số lượng
                var quantityInput = $("#medicine-quantity-" + medicineId);
                var currentQuantity = parseInt(quantityInput.val());
                quantityInput.val(currentQuantity + 1);
            } else {
                // Nếu chưa chọn, thêm vào danh sách
                var template = `
                    <div class="medicine-item selected" id="selected-medicine-${medicineId}">
                        <h6>${medicineName}</h6>
                        <input type="hidden" name="items[${medicineId}][medicine_id]" value="${medicineId}">
                        
                        <div class="row mb-2">
                            <div class="col">
                                <label class="form-label">Số lượng</label>
                                <input type="number" class="form-control" name="items[${medicineId}][quantity]" id="medicine-quantity-${medicineId}" value="1" min="1" required>
                            </div>
                        </div>
                        
                        <div class="row mb-2">
                            <div class="col">
                                <label class="form-label">Liều dùng</label>
                                <input type="text" class="form-control" name="items[${medicineId}][dosage]" placeholder="VD: 1 viên/lần" required>
                            </div>
                        </div>
                        
                        <div class="row mb-2">
                            <div class="col">
                                <label class="form-label">Hướng dẫn</label>
                                <input type="text" class="form-control" name="items[${medicineId}][instructions]" placeholder="VD: Ngày 2 lần, sau khi ăn" required>
                            </div>
                        </div>
                        
                        <div class="text-end mt-2">
                            <button type="button" class="btn btn-sm btn-danger remove-medicine" data-id="${medicineId}">
                                <i class="fas fa-trash"></i> Xóa
                            </button>
                        </div>
                    </div>
                `;
                
                $("#selected-medicines").append(template);
                $("#no-medicine-selected").hide();
            }
        });
        
        // Xóa thuốc khỏi danh sách đã chọn
        $(document).on("click", ".remove-medicine", function() {
            var medicineId = $(this).data('id');
            $("#selected-medicine-" + medicineId).remove();
            
            // Nếu không còn thuốc nào, hiển thị thông báo
            if ($("#selected-medicines .medicine-item").length === 0) {
                $("#no-medicine-selected").show();
            }
        });
    });
</script>
@endsection 