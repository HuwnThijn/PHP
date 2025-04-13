@extends('admin.layouts.app')

@section('title', 'Quản lý điều trị')

@section('content')
<!-- Thống kê tổng quan -->
<div class="row">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Tổng số phương pháp</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $treatments->total() }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-stethoscope fa-2x text-gray-300"></i>
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
                            Doanh thu điều trị</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ number_format($treatments->sum('price'), 0, ',', '.') }} VNĐ
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Thời gian trung bình</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $treatments->avg('duration') > 0 ? round($treatments->avg('duration')) : 0 }} phút
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clock fa-2x text-gray-300"></i>
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
                            Giá trung bình</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ number_format($treatments->avg('price'), 0, ',', '.') }} VNĐ
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-tag fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTreatmentModal">
            <i class="fas fa-plus"></i> Thêm phương pháp điều trị mới
        </button>
    </div>
</div>

<!-- Danh sách phương pháp điều trị -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Danh sách phương pháp điều trị</h6>
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
        
        <div class="table-responsive">
            <table class="table table-bordered" id="treatmentsTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên điều trị</th>
                        <th>Mô tả</th>
                        <th>Giá</th>
                        <th>Thời gian</th>
                        <th>Thiết bị cần thiết</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($treatments as $treatment)
                    <tr>
                        <td>{{ $treatment->id }}</td>
                        <td>{{ $treatment->name }}</td>
                        <td>{{ Str::limit($treatment->description, 50) }}</td>
                        <td>{{ number_format($treatment->price, 0, ',', '.') }} VNĐ</td>
                        <td>{{ $treatment->duration }} phút</td>
                        <td>{{ Str::limit($treatment->equipment_needed, 50) }}</td>
                        <td>
                            <button class="btn btn-sm btn-info edit-treatment" data-bs-toggle="modal" data-bs-target="#editTreatmentModal" data-treatment-id="{{ $treatment->id }}">
                                <i class="fas fa-edit"></i> Sửa
                            </button>
                            <button class="btn btn-sm btn-danger delete-treatment" data-treatment-id="{{ $treatment->id }}">
                                <i class="fas fa-trash"></i> Xóa
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $treatments->links() }}
    </div>
</div>

<!-- Modal thêm phương pháp điều trị -->
<div class="modal fade" id="addTreatmentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thêm phương pháp điều trị mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addTreatmentForm" action="{{ route('admin.treatment.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tên điều trị</label>
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
                        <label class="form-label">Thời gian điều trị (phút)</label>
                        <input type="number" class="form-control" name="duration" min="0" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Thiết bị cần thiết</label>
                        <textarea class="form-control" name="equipment_needed" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Chống chỉ định</label>
                        <textarea class="form-control" name="contraindications" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tác dụng phụ</label>
                        <textarea class="form-control" name="side_effects" rows="3" required></textarea>
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

<!-- Modal chỉnh sửa phương pháp điều trị -->
<div class="modal fade" id="editTreatmentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Chỉnh sửa thông tin điều trị</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editTreatmentForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tên điều trị</label>
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
                        <label class="form-label">Thời gian điều trị (phút)</label>
                        <input type="number" class="form-control" name="duration" id="edit_duration" min="0" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Thiết bị cần thiết</label>
                        <textarea class="form-control" name="equipment_needed" id="edit_equipment_needed" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Chống chỉ định</label>
                        <textarea class="form-control" name="contraindications" id="edit_contraindications" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tác dụng phụ</label>
                        <textarea class="form-control" name="side_effects" id="edit_side_effects" rows="3" required></textarea>
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
<div class="modal fade" id="deleteTreatmentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận xóa phương pháp điều trị</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn xóa phương pháp điều trị này không? Hành động này không thể hoàn tác.</p>
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
    .border-left-primary {
        border-left: 0.25rem solid #4e73df !important;
    }
    .border-left-success {
        border-left: 0.25rem solid #1cc88a !important;
    }
    .border-left-info {
        border-left: 0.25rem solid #36b9cc !important;
    }
    .border-left-warning {
        border-left: 0.25rem solid #f6c23e !important;
    }
</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<script>
$(document).ready(function() {
    // Hiển thị thông báo session nếu có
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
    $('#treatmentsTable').DataTable({
        language: {
            url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Vietnamese.json"
        },
        pageLength: 10,
        ordering: true,
        "columnDefs": [
            { "orderable": false, "targets": 6 } // Vô hiệu hóa sắp xếp cho cột thao tác
        ]
    });

    // Xử lý Form thêm phương pháp điều trị
    $('#addTreatmentForm').on('submit', function(e) {
        e.preventDefault();
        
        const form = $(this);
        const formData = new FormData(form[0]);
        
        $.ajax({
            url: "{{ route('admin.treatment.store') }}", // Sử dụng route mới
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
                        text: 'Thêm phương pháp điều trị mới thành công!',
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
                    text: 'Có lỗi xảy ra khi thêm phương pháp điều trị',
                    confirmButtonText: 'Đóng'
                });
                console.error(xhr.responseText);
            }
        });
    });

    // Xử lý nút chỉnh sửa phương pháp điều trị
    $('.edit-treatment').on('click', function() {
        const treatmentId = $(this).data('treatment-id');
        
        // Lấy thông tin phương pháp điều trị từ API
        $.ajax({
            url: `{{ route('admin.treatment.show', '') }}/${treatmentId}`, // Sử dụng route show
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                // Cập nhật action của form
                $('#editTreatmentForm').attr('action', `{{ route('admin.treatment.update', '') }}/${treatmentId}`);
                
                // Điền thông tin vào form
                $('#edit_name').val(data.name);
                $('#edit_description').val(data.description);
                $('#edit_price').val(data.price);
                $('#edit_duration').val(data.duration);
                $('#edit_equipment_needed').val(data.equipment_needed);
                $('#edit_contraindications').val(data.contraindications);
                $('#edit_side_effects').val(data.side_effects);
                
                // Hiển thị modal (đã mở bằng data-bs-toggle)
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi!',
                    text: 'Không thể lấy thông tin phương pháp điều trị',
                    confirmButtonText: 'Đóng'
                });
                console.error(xhr.responseText);
            }
        });
    });

    // Xử lý form chỉnh sửa phương pháp điều trị
    $('#editTreatmentForm').on('submit', function(e) {
        e.preventDefault();
        
        const form = $(this);
        const formData = new FormData(form[0]);
        formData.append('_method', 'PUT'); // Thêm method PUT
        
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
                        text: 'Cập nhật thông tin phương pháp điều trị thành công!',
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

    // Xử lý nút xóa phương pháp điều trị
    let treatmentIdToDelete = null;
    
    $('.delete-treatment').on('click', function() {
        treatmentIdToDelete = $(this).data('treatment-id');
        
        Swal.fire({
            title: 'Xác nhận xóa',
            text: "Bạn có chắc chắn muốn xóa phương pháp điều trị này? Hành động này không thể hoàn tác.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Xác nhận Xóa',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                deleteTreatment(treatmentIdToDelete);
            }
        });
    });
    
    // Hàm thực hiện xóa sau khi xác nhận
    function deleteTreatment(id) {
         $.ajax({
            url: `{{ route('admin.treatment.destroy', '') }}/${id}`,
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
                    text: 'Có lỗi xảy ra khi xóa phương pháp điều trị',
                    confirmButtonText: 'Đóng'
                });
                console.error(xhr.responseText);
            }
        });
    }
});
</script>
@endpush 