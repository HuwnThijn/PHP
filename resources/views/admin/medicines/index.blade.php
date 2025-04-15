@extends('admin.layouts.app')

@section('title', 'Quản lý thuốc')

@section('content')
<!-- Thống kê tổng quan -->
<div class="row">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Tổng số thuốc</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $medicines->total() }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-pills fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Thuốc còn hàng</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $medicines->where('stock_quantity', '>', 0)->count() }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Thuốc sắp hết hàng</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $medicines->where('stock_quantity', '<', 10)->where('stock_quantity', '>', 0)->count() }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                            Thuốc hết hàng</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $medicines->where('stock_quantity', '=', 0)->count() }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMedicineModal">
            <i class="fas fa-plus"></i> Thêm thuốc mới
        </button>
    </div>
</div>

<!-- Danh sách thuốc -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Danh sách thuốc</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="medicinesTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên thuốc</th>
                        <th>Mô tả</th>
                        <th>Giá</th>
                        <th>Số lượng tồn</th>
                        <th>Nhà sản xuất</th>
                        <th>Hạn sử dụng</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($medicines as $medicine)
                    <tr>
                        <td>{{ $medicine->id }}</td>
                        <td>{{ $medicine->name }}</td>
                        <td>{{ Str::limit($medicine->description, 50) }}</td>
                        <td>{{ $medicine->price_formatted }}</td>
                        <td>
                            @if($medicine->stock_quantity > 10)
                                <span class="badge bg-success text-white">{{ $medicine->stock_quantity }}</span>
                            @elseif($medicine->stock_quantity > 0)
                                <span class="badge bg-warning text-dark">{{ $medicine->stock_quantity }}</span>
                            @else
                                <span class="badge bg-danger text-white">Hết hàng</span>
                            @endif
                        </td>
                        <td>{{ $medicine->manufacturer }}</td>
                        <td>{{ $medicine->expiry_date_formatted }}</td>
                        <td>
                            <button class="btn btn-sm btn-info edit-medicine" data-bs-toggle="modal" data-bs-target="#editMedicineModal" data-medicine-id="{{ $medicine->id }}">
                                <i class="fas fa-edit"></i> Sửa
                            </button>
                            <button class="btn btn-sm btn-danger delete-medicine" data-medicine-id="{{ $medicine->id }}">
                                <i class="fas fa-trash"></i> Xóa
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $medicines->links() }}
    </div>
</div>

<!-- Modal thêm thuốc -->
<div class="modal fade" id="addMedicineModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thêm thuốc mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addMedicineForm" action="{{ route('admin.medicine.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tên thuốc</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mô tả</label>
                        <textarea class="form-control" name="description" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Giá</label>
                        <input type="number" class="form-control" name="price" min="0" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Số lượng tồn</label>
                        <input type="number" class="form-control" name="stock_quantity" min="0" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nhà sản xuất</label>
                        <input type="text" class="form-control" name="manufacturer" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Hạn sử dụng</label>
                        <input type="date" class="form-control" name="expiry_date" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Dạng bào chế</label>
                        <input type="text" class="form-control" name="dosage_form" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Hướng dẫn sử dụng</label>
                        <textarea class="form-control" name="usage_instructions" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Thêm</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal chỉnh sửa thuốc -->
<div class="modal fade" id="editMedicineModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Chỉnh sửa thông tin thuốc</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editMedicineForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tên thuốc</label>
                        <input type="text" class="form-control" name="name" id="edit_name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mô tả</label>
                        <textarea class="form-control" name="description" id="edit_description" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Giá</label>
                        <input type="number" class="form-control" name="price" id="edit_price" min="0" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Số lượng tồn</label>
                        <input type="number" class="form-control" name="stock_quantity" id="edit_stock_quantity" min="0" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nhà sản xuất</label>
                        <input type="text" class="form-control" name="manufacturer" id="edit_manufacturer" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Hạn sử dụng</label>
                        <input type="date" class="form-control" name="expiry_date" id="edit_expiry_date" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Dạng bào chế</label>
                        <input type="text" class="form-control" name="dosage_form" id="edit_dosage_form" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Hướng dẫn sử dụng</label>
                        <textarea class="form-control" name="usage_instructions" id="edit_usage_instructions" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal xác nhận xóa -->
<div class="modal fade" id="deleteMedicineModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận xóa thuốc</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn xóa thuốc này không? Hành động này không thể hoàn tác.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Xóa</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .badge {
        padding: 0.5em 0.8em;
        border-radius: 0.25rem;
    }
    .bg-success {
        background-color: #28a745 !important;
    }
    .bg-warning {
        background-color: #ffc107 !important;
    }
    .bg-danger {
        background-color: #dc3545 !important;
    }
</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<script>
$(document).ready(function() {
    // Hiển thị thông báo nếu có
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Thành công!',
            text: '{{ session('success') }}',
            timer: 2000,
            showConfirmButton: false
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Lỗi!',
            text: '{{ session('error') }}',
            confirmButtonText: 'Đóng'
        });
    @endif

    // Khởi tạo DataTables
    $('#medicinesTable').DataTable({
        language: {
            url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Vietnamese.json"
        },
        pageLength: 10,
        ordering: true,
        "columnDefs": [
            { "orderable": false, "targets": 7 } // Vô hiệu hóa sắp xếp cho cột thao tác
        ]
    });

    // Xử lý Form thêm thuốc
    $('#addMedicineForm').on('submit', function(e) {
        e.preventDefault();
        
        const form = $(this);
        const formData = new FormData(form[0]);
        
        $.ajax({
            url: "{{ route('admin.medicine.store') }}",
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Thành công!',
                        text: 'Thêm thuốc mới thành công!',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi!',
                        text: 'Có lỗi xảy ra: ' + response.message,
                        confirmButtonText: 'Đóng'
                    });
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi!',
                    text: 'Có lỗi xảy ra khi thêm thuốc',
                    confirmButtonText: 'Đóng'
                });
                console.error(xhr.responseText);
            }
        });
    });

    // Xử lý nút chỉnh sửa thuốc
    $('.edit-medicine').on('click', function() {
        const medicineId = $(this).data('medicine-id');
        
        // Lấy thông tin thuốc từ API
        $.ajax({
            url: "{{ route('admin.medicine.show', '') }}/" + medicineId,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                // Cập nhật action của form
                $('#editMedicineForm').attr('action', "{{ route('admin.medicine.update', '') }}/" + medicineId);
                
                // Điền thông tin vào form
                $('#edit_name').val(data.name);
                $('#edit_description').val(data.description);
                $('#edit_price').val(data.price);
                $('#edit_stock_quantity').val(data.stock_quantity);
                $('#edit_manufacturer').val(data.manufacturer);
                $('#edit_expiry_date').val(data.expiry_date);
                $('#edit_dosage_form').val(data.dosage_form);
                $('#edit_usage_instructions').val(data.usage_instructions);
                
                // Hiển thị modal (modal đã được mở bằng data-bs-toggle)
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi!',
                    text: 'Không thể lấy thông tin thuốc',
                    confirmButtonText: 'Đóng'
                });
                console.error(xhr.responseText);
            }
        });
    });

    // Xử lý form chỉnh sửa thuốc
    $('#editMedicineForm').on('submit', function(e) {
        e.preventDefault();
        
        const form = $(this);
        const formData = new FormData(form[0]);
        formData.append('_method', 'PUT');
        
        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Thành công!',
                        text: 'Cập nhật thông tin thuốc thành công!',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi!',
                        text: 'Có lỗi xảy ra: ' + response.message,
                        confirmButtonText: 'Đóng'
                    });
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi!',
                    text: 'Có lỗi xảy ra khi cập nhật thông tin',
                    confirmButtonText: 'Đóng'
                });
                console.error(xhr.responseText);
            }
        });
    });

    // Xử lý nút xóa thuốc
    let medicineIdToDelete = null;
    
    $('.delete-medicine').on('click', function() {
        medicineIdToDelete = $(this).data('medicine-id');
        
        Swal.fire({
            title: 'Xác nhận xóa',
            text: "Bạn có chắc chắn muốn xóa thuốc này? Hành động này không thể hoàn tác.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Xác nhận Xóa',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                deleteMedicine(medicineIdToDelete);
            }
        });
    });
    
    // Hàm thực hiện xóa sau khi xác nhận
    function deleteMedicine(id) {
        $.ajax({
            url: "{{ route('admin.medicine.destroy', '') }}/" + id,
            type: 'POST',
            data: {
                _method: 'DELETE',
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Đã xóa!',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi!',
                        text: 'Có lỗi xảy ra: ' + response.message,
                        confirmButtonText: 'Đóng'
                    });
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi!',
                    text: 'Có lỗi xảy ra khi xóa thuốc',
                    confirmButtonText: 'Đóng'
                });
                console.error(xhr.responseText);
            }
        });
    }
});
</script>
@endpush 