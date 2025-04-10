@extends('admin.layouts.app')

@section('title', 'Quản lý thành viên')

@section('content')
<!-- Thống kê -->
<div class="row">
    <div class="col-xl-3 col-md-6 mb-4">
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
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Thành viên hoạt động</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $members->where('status', 'active')->count() }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-check fa-2x text-gray-300"></i>
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
                            Thành viên bị vô hiệu hóa</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $members->where('status', 'inactive')->count() }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-lock fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Thêm mới</div>
                        <button type="button" class="btn btn-sm btn-primary w-100" data-bs-toggle="modal" data-bs-target="#addMemberModal">
                            <i class="fas fa-plus"></i> Thêm thành viên
                        </button>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-plus fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Danh sách thành viên -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Danh sách thành viên</h6>
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
            <table class="table table-bordered" id="membersTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên</th>
                        <th>Email</th>
                        <th>Số điện thoại</th>
                        <th>Địa chỉ</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($members as $member)
                    <tr>
                        <td>{{ $member->id_user }}</td>
                        <td>{{ $member->name }}</td>
                        <td>{{ $member->email }}</td>
                        <td>{{ $member->phone }}</td>
                        <td>{{ $member->address }}</td>
                        <td>
                            @if($member->status == 'active')
                                <span class="badge bg-success text-white">Hoạt động</span>
                            @else
                                <span class="badge bg-danger text-white">Vô hiệu hóa</span>
                            @endif
                        </td>
                        <td>
                            <button class="btn btn-sm btn-info edit-member" data-member-id="{{ $member->id_user }}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-{{ $member->status == 'active' ? 'danger' : 'success' }} toggle-status" 
                                   data-member-id="{{ $member->id_user }}" 
                                   data-status="{{ $member->status == 'active' ? 'inactive' : 'active' }}">
                                <i class="fas fa-{{ $member->status == 'active' ? 'ban' : 'check' }}"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $members->links() }}
    </div>
</div>

<!-- Modal thêm thành viên -->
<div class="modal fade" id="addMemberModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thêm thành viên mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addMemberForm" action="{{ route('admin.member.store') }}" method="POST">
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
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Thêm</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal chỉnh sửa thành viên -->
<div class="modal fade" id="editMemberModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Chỉnh sửa thông tin thành viên</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editMemberForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tên</label>
                        <input type="text" class="form-control" name="name" id="edit_name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" id="edit_email" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Số điện thoại</label>
                        <input type="text" class="form-control" name="phone" id="edit_phone" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Địa chỉ</label>
                        <input type="text" class="form-control" name="address" id="edit_address" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal xác nhận thay đổi trạng thái -->
<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận thay đổi trạng thái</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="statusMessage"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-primary" id="confirmStatus">Xác nhận</button>
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
    .bg-danger {
        background-color: #dc3545 !important;
    }
    .border-left-info {
        border-left: 0.25rem solid #36b9cc !important;
    }
    .border-left-success {
        border-left: 0.25rem solid #1cc88a !important;
    }
    .border-left-warning {
        border-left: 0.25rem solid #f6c23e !important;
    }
    .border-left-primary {
        border-left: 0.25rem solid #4e73df !important;
    }
    .text-xs {
        font-size: .7rem;
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Khởi tạo DataTables
    $('#membersTable').DataTable({
        language: {
            url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Vietnamese.json"
        },
        pageLength: 10,
        ordering: true,
        "columnDefs": [
            { "orderable": false, "targets": 6 } // Vô hiệu hóa sắp xếp cho cột thao tác
        ]
    });

    // Xử lý thêm thành viên
    $('#addMemberForm').on('submit', function(e) {
        e.preventDefault();
        
        const form = $(this);
        const formData = new FormData(form[0]);
        
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
                    alert('Thêm thành viên mới thành công!');
                    location.reload();
                } else {
                    alert('Có lỗi xảy ra: ' + response.message);
                }
            },
            error: function(xhr) {
                alert('Có lỗi xảy ra khi thêm thành viên');
                console.error(xhr.responseText);
            }
        });
    });

    // Xử lý nút chỉnh sửa thành viên
    $('.edit-member').on('click', function() {
        const memberId = $(this).data('member-id');
        
        // Lấy thông tin thành viên qua AJAX
        $.ajax({
            url: `/admin/member/${memberId}/edit`,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                // Cập nhật action của form
                $('#editMemberForm').attr('action', `/admin/member/${memberId}`);
                
                // Điền thông tin vào form
                $('#edit_name').val(data.name);
                $('#edit_email').val(data.email);
                $('#edit_phone').val(data.phone);
                $('#edit_address').val(data.address);
                
                // Hiển thị modal
                $('#editMemberModal').modal('show');
            },
            error: function(xhr) {
                alert('Không thể lấy thông tin thành viên');
                console.error(xhr.responseText);
            }
        });
    });

    // Xử lý form chỉnh sửa thành viên
    $('#editMemberForm').on('submit', function(e) {
        e.preventDefault();
        
        const form = $(this);
        const formData = new FormData(form[0]);
        
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
                    alert('Cập nhật thông tin thành viên thành công!');
                    location.reload();
                } else {
                    alert('Có lỗi xảy ra: ' + response.message);
                }
            },
            error: function(xhr) {
                alert('Có lỗi xảy ra khi cập nhật thông tin');
                console.error(xhr.responseText);
            }
        });
    });

    // Xử lý nút thay đổi trạng thái
    let memberIdToUpdate = null;
    let statusToSet = null;
    
    $('.toggle-status').on('click', function() {
        memberIdToUpdate = $(this).data('member-id');
        statusToSet = $(this).data('status');
        
        const action = statusToSet == 'active' ? 'kích hoạt' : 'vô hiệu hóa';
        $('#statusMessage').text(`Bạn có chắc chắn muốn ${action} tài khoản thành viên này?`);
        
        $('#statusModal').modal('show');
    });
    
    // Xử lý xác nhận thay đổi trạng thái
    $('#confirmStatus').on('click', function() {
        if (!memberIdToUpdate) return;
        
        $.ajax({
            url: `/admin/member/${memberIdToUpdate}/status`,
            type: 'POST',
            data: {
                status: statusToSet,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                    location.reload();
                } else {
                    alert('Có lỗi xảy ra: ' + response.message);
                }
            },
            error: function(xhr) {
                alert('Có lỗi xảy ra khi cập nhật trạng thái');
                console.error(xhr.responseText);
            }
        });
        
        $('#statusModal').modal('hide');
    });
});
</script>
@endpush 