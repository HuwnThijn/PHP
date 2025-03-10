@extends('layouts.admin')

@section('title', 'Quản lý người dùng')

@section('page-title', 'Quản lý người dùng')

@section('content')
<!-- Thống kê -->
<div class="row">
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Tổng số bác sĩ</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $counts['doctor'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-md fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Tổng số dược sĩ</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $counts['pharmacist'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-pills fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Tổng số thành viên</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $counts['member'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Danh sách người dùng -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <div class="d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Danh sách người dùng</h6>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStaffModal">
                <i class="fas fa-plus"></i> Thêm nhân viên
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên</th>
                        <th>Email</th>
                        <th>Số điện thoại</th>
                        <th>Vai trò</th>
                        <th>Chuyên môn</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>{{ $user->id_user }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->phone }}</td>
                        <td>
                            @switch($user->id_role)
                                @case(2)
                                    <span class="badge bg-primary">Bác sĩ</span>
                                    @break
                                @case(3)
                                    <span class="badge bg-success">Dược sĩ</span>
                                    @break
                                @case(4)
                                    <span class="badge bg-info">Thành viên</span>
                                    @break
                            @endswitch
                        </td>
                        <td>{{ $user->specialization ?? 'N/A' }}</td>
                        <td>
                            @switch($user->status)
                                @case('active')
                                    <span class="badge bg-success">Hoạt động</span>
                                    @break
                                @case('temporary_locked')
                                    <span class="badge bg-warning">Tạm khóa</span>
                                    @break
                                @case('permanent_locked')
                                    <span class="badge bg-danger">Khóa vĩnh viễn</span>
                                    @break
                            @endswitch
                        </td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    Trạng thái
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item {{ $user->status === 'active' ? 'disabled' : '' }}" 
                                          href="#" onclick="updateStatus({{ $user->id_user }}, 'active')">
                                        <i class="fas fa-check text-success"></i> Kích hoạt
                                    </a></li>
                                    <li><a class="dropdown-item {{ $user->status === 'temporary_locked' ? 'disabled' : '' }}" 
                                          href="#" onclick="updateStatus({{ $user->id_user }}, 'temporary_locked')">
                                        <i class="fas fa-clock text-warning"></i> Tạm khóa
                                    </a></li>
                                    <li><a class="dropdown-item {{ $user->status === 'permanent_locked' ? 'disabled' : '' }}" 
                                          href="#" onclick="updateStatus({{ $user->id_user }}, 'permanent_locked')">
                                        <i class="fas fa-ban text-danger"></i> Khóa vĩnh viễn
                                    </a></li>
                                </ul>
                            </div>
                            <button class="btn btn-sm btn-info mt-1" data-bs-toggle="modal" 
                                    data-bs-target="#editStaffModal" 
                                    data-staff="{{ json_encode($user) }}">
                                <i class="fas fa-edit"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $users->links() }}
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
                        <select class="form-select" name="role" required>
                            <option value="2">Bác sĩ</option>
                            <option value="3">Dược sĩ</option>
                        </select>
                    </div>
                    <div class="mb-3" id="specializationField">
                        <label class="form-label">Chuyên môn</label>
                        <input type="text" class="form-control" name="specialization">
                        <small class="text-muted">Chỉ bắt buộc đối với bác sĩ</small>
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
@endsection

@section('scripts')
<script>
    // Hiển thị/ẩn trường chuyên môn dựa vào vai trò
    document.querySelector('select[name="role"]').addEventListener('change', function() {
        const specializationField = document.getElementById('specializationField');
        specializationField.style.display = this.value === '2' ? 'block' : 'none';
        
        // Reset giá trị nếu chọn dược sĩ
        if (this.value === '3') {
            document.querySelector('input[name="specialization"]').value = '';
        }
    });

    // Kích hoạt sự kiện change khi trang tải
    document.querySelector('select[name="role"]').dispatchEvent(new Event('change'));

    // Cập nhật trạng thái tài khoản
    function updateStatus(userId, status) {
        if (confirm('Bạn có chắc muốn thay đổi trạng thái của người dùng này?')) {
            fetch(`/admin/users/${userId}/status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ status: status })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert('Có lỗi xảy ra: ' + data.message);
                }
            })
            .catch(error => {
                alert('Có lỗi xảy ra khi cập nhật trạng thái');
                console.error('Error:', error);
            });
        }
    }
</script>
@endsection 