@extends('admin.layouts.app')

@section('title', 'Quản lý nhân viên')

@section('content')
<!-- Thống kê tổng quan -->
<div class="row">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Tổng số nhân viên</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ count($doctors) + count($pharmacists) }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
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
                            Doanh thu hôm nay</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ number_format($dailyRevenue, 0, ',', '.') }} VNĐ
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
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
                            Doanh thu tuần này</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ number_format($weeklyRevenue, 0, ',', '.') }} VNĐ
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar-week fa-2x text-gray-300"></i>
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
                            Doanh thu tháng này</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ number_format($monthlyRevenue, 0, ',', '.') }} VNĐ
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStaffModal">
            <i class="fas fa-plus"></i> Thêm nhân viên mới
        </button>
    </div>
</div>

<!-- Thông báo -->
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

<!-- Danh sách bác sĩ -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Danh sách bác sĩ</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table" id="doctorsTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên</th>
                        <th>Email</th>
                        <th>Số điện thoại</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($doctors as $doctor)
                    <tr>
                        <td>{{ $doctor->id_user }}</td>
                        <td>{{ $doctor->name }}</td>
                        <td>{{ $doctor->email }}</td>
                        <td>{{ $doctor->phone }}</td>
                        <td>
                            @if($doctor->status == 'active')
                            <span class="badge bg-success text-white">Hoạt động</span>
                            @elseif($doctor->status == 'temporary_locked')
                            <span class="badge bg-warning text-dark">Tạm khóa</span>
                            @elseif($doctor->status == 'permanent_locked')
                            <span class="badge bg-danger text-white">Vĩnh viễn</span>
                            @endif
                        </td>
                        <td>
                            <button class="btn btn-sm btn-primary edit-btn" data-bs-toggle="modal" data-bs-target="#editStaffModal{{ $doctor->id_user }}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-danger status-btn" data-bs-toggle="modal" data-bs-target="#statusModal{{ $doctor->id_user }}">
                                <i class="fas fa-lock"></i>
                            </button>
                        </td>
                    </tr>

                    <!-- Modal chỉnh sửa cho từng bác sĩ -->
                    <div class="modal fade" id="editStaffModal{{ $doctor->id_user }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Chỉnh sửa thông tin nhân viên</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('admin.staff.update', $doctor->id_user) }}" method="POST" class="edit-staff-form">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Tên</label>
                                            <input type="text" class="form-control" name="name" value="{{ $doctor->name }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Email</label>
                                            <input type="email" class="form-control" name="email" value="{{ $doctor->email }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Số điện thoại</label>
                                            <input type="text" class="form-control" name="phone" value="{{ $doctor->phone }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Địa chỉ</label>
                                            <input type="text" class="form-control" name="address" value="{{ $doctor->address }}" required>
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

                    <!-- Modal quản lý trạng thái cho từng bác sĩ -->
                    <div class="modal fade" id="statusModal{{ $doctor->id_user }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Quản lý trạng thái tài khoản</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('admin.users.status', $doctor->id_user) }}" method="POST" class="status-form">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Hành động</label>
                                            <select class="form-control" name="action" required>
                                                <option value="unlock">Mở khóa</option>
                                                <option value="delete">Xóa</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Trạng thái mới</label>
                                            <select class="form-control" name="status" required>
                                                <option value="active">Hoạt động</option>
                                                <option value="temporary_locked">Tạm khóa</option>
                                                <option value="permanent_locked">Vĩnh viễn</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                        <button type="submit" class="btn btn-danger">Xác nhận</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Danh sách dược sĩ -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Danh sách dược sĩ</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table" id="pharmacistsTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên</th>
                        <th>Email</th>
                        <th>Số điện thoại</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pharmacists as $pharmacist)
                    <tr>
                        <td>{{ $pharmacist->id_user }}</td>
                        <td>{{ $pharmacist->name }}</td>
                        <td>{{ $pharmacist->email }}</td>
                        <td>{{ $pharmacist->phone }}</td>
                        <td>
                            @if($pharmacist->status == 'active')
                            <span class="badge bg-success text-white">Hoạt động</span>
                            @elseif($pharmacist->status == 'temporary_locked')
                            <span class="badge bg-warning text-dark">Tạm khóa</span>
                            @elseif($pharmacist->status == 'permanent_locked')
                            <span class="badge bg-danger text-white">Vĩnh viễn</span>
                            @endif
                        </td>
                        <td>
                            <button class="btn btn-sm btn-primary edit-btn" data-bs-toggle="modal" data-bs-target="#editStaffModal{{ $pharmacist->id_user }}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-danger status-btn" data-bs-toggle="modal" data-bs-target="#statusModal{{ $pharmacist->id_user }}">
                                <i class="fas fa-lock"></i>
                            </button>
                        </td>
                    </tr>

                    <!-- Modal chỉnh sửa cho từng dược sĩ -->
                    <div class="modal fade" id="editStaffModal{{ $pharmacist->id_user }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Chỉnh sửa thông tin nhân viên</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('admin.staff.update', $pharmacist->id_user) }}" method="POST" class="edit-staff-form">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Tên</label>
                                            <input type="text" class="form-control" name="name" value="{{ $pharmacist->name }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Email</label>
                                            <input type="email" class="form-control" name="email" value="{{ $pharmacist->email }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Số điện thoại</label>
                                            <input type="text" class="form-control" name="phone" value="{{ $pharmacist->phone }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Địa chỉ</label>
                                            <input type="text" class="form-control" name="address" value="{{ $pharmacist->address }}" required>
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

                    <!-- Modal quản lý trạng thái cho từng dược sĩ -->
                    <div class="modal fade" id="statusModal{{ $pharmacist->id_user }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Quản lý trạng thái tài khoản</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('admin.users.status', $pharmacist->id_user) }}" method="POST" class="status-form">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Hành động</label>
                                            <select class="form-control" name="action" required>
                                                <option value="unlock">Mở khóa</option>
                                                <option value="delete">Xóa</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Trạng thái mới</label>
                                            <select class="form-control" name="status" required>
                                                <option value="active">Hoạt động</option>
                                                <option value="temporary_locked">Tạm khóa</option>
                                                <option value="permanent_locked">Vĩnh viễn</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                        <button type="submit" class="btn btn-danger">Xác nhận</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal thêm nhân viên -->
<div class="modal fade" id="addStaffModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thêm nhân viên mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.staff.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tên</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mật khẩu</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Số điện thoại</label>
                        <input type="text" class="form-control" name="phone" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Địa chỉ</label>
                        <input type="text" class="form-control" name="address" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Vai trò</label>
                        <select class="form-control" name="role" required>
                            <option value="2">Bác sĩ</option>
                            <option value="3">Dược sĩ</option>
                        </select>
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
    .table th {
        border-top: none;
        border-bottom: 1px solid #e3e6f0;
        font-weight: 600;
        padding: 0.75rem;
        vertical-align: middle;
    }
    .table td {
        padding: 0.75rem;
        vertical-align: middle;
    }
    .btn-primary {
        background-color: #4e73df;
        border-color: #4e73df;
    }
    .btn-danger {
        background-color: #e74a3b;
        border-color: #e74a3b;
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
    .text-xs {
        font-size: .7rem;
    }
    .text-primary {
        color: #4e73df !important;
    }
    .text-success {
        color: #1cc88a !important;
    }
    .text-info {
        color: #36b9cc !important;
    }
    .text-warning {
        color: #f6c23e !important;
    }
    .text-gray-300 {
        color: #dddfeb !important;
    }
    .text-gray-800 {
        color: #5a5c69 !important;
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
    $('#doctorsTable, #pharmacistsTable').DataTable({
        language: {
            url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Vietnamese.json"
        },
        pageLength: 10,
        ordering: true
    });

    // Xử lý form chỉnh sửa nhân viên
    $('.edit-staff-form').on('submit', function(e) {
        e.preventDefault();
        
        const form = $(this);
        const formData = new FormData(form[0]);
        formData.append('_method', 'PUT'); // Thêm method PUT vào formData
        
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
                    text: 'Có lỗi xảy ra khi cập nhật thông tin: ' + (xhr.responseJSON ? xhr.responseJSON.message : xhr.statusText),
                    confirmButtonText: 'Đóng'
                });
                console.error(xhr.responseText);
            }
        });
    });

    // Xử lý form quản lý trạng thái tài khoản
    $('.status-form').on('submit', function(e) {
            e.preventDefault();
            
        const form = $(this);
        const formData = new FormData(form[0]);
        
        // Thêm method PUT vào formData
        formData.append('_method', 'PUT');
        
        Swal.fire({
            title: 'Xác nhận',
            text: "Bạn có chắc muốn thay đổi trạng thái của tài khoản này?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Xác nhận',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
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
                        Swal.fire({
                            icon: 'success',
                            title: 'Thành công!',
                            text: response.message || 'Cập nhật trạng thái thành công',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                    location.reload();
                        });
                    },
                    error: function(xhr) {
                         Swal.fire({
                            icon: 'error',
                            title: 'Lỗi!',
                            text: 'Có lỗi xảy ra khi cập nhật trạng thái: ' + (xhr.responseJSON ? xhr.responseJSON.message : xhr.statusText),
                            confirmButtonText: 'Đóng'
                        });
                        console.error(xhr.responseText);
                    }
                });
            }
            });
        });
    });
</script>
@endpush
@endsection 