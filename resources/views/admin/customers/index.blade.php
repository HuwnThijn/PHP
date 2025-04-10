@extends('admin.layouts.app')

@section('title', 'Quản lý Khách hàng')

@section('page-title', 'Quản lý khách hàng')

@section('content')
<!-- Thống kê -->
<div class="row">
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Tổng số khách hàng</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $counts['member'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
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
                            Khách hàng đang hoạt động</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $counts['active'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-check fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                            Khách hàng bị khóa</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $counts['inactive'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-lock fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Danh sách khách hàng -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Danh sách khách hàng</h6>
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
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
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
                    @foreach($customers as $customer)
                    <tr>
                        <td>{{ $customer->id_user }}</td>
                        <td>{{ $customer->name }}</td>
                        <td>{{ $customer->email }}</td>
                        <td>{{ $customer->phone }}</td>
                        <td>{{ $customer->address }}</td>
                        <td>
                            @if($customer->status == 1)
                                <span class="badge bg-success">Đang hoạt động</span>
                            @else
                                <span class="badge bg-danger">Đã vô hiệu hóa</span>
                            @endif
                        </td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    Thay đổi trạng thái
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item {{ $customer->status === 1 ? 'disabled' : '' }}" 
                                          href="#" onclick="updateCustomerStatus({{ $customer->id_user }}, 1)">
                                        <i class="fas fa-check text-success"></i> Kích hoạt
                                    </a></li>
                                    <li><a class="dropdown-item {{ $customer->status === 0 ? 'disabled' : '' }}" 
                                          href="#" onclick="updateCustomerStatus({{ $customer->id_user }}, 0)">
                                        <i class="fas fa-ban text-danger"></i> Vô hiệu hóa
                                    </a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $customers->links() }}
    </div>
</div>
@endsection

@section('scripts')
<script>
    function updateCustomerStatus(userId, status) {
        if (confirm('Bạn có chắc muốn thay đổi trạng thái của khách hàng này?')) {
            fetch(`/admin/customers/${userId}/status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
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
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi cập nhật trạng thái');
            });
        }
    }
</script>
@endsection 