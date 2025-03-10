@extends('layouts.admin')

@section('title', 'Quản lý thuốc')

@section('page-title', 'Quản lý thuốc')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Danh sách thuốc</h5>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMedicineModal">
                <i class="fas fa-plus"></i> Thêm thuốc mới
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên thuốc</th>
                        <th>Giá</th>
                        <th>Số lượng tồn</th>
                        <th>Nhà sản xuất</th>
                        <th>Hạn sử dụng</th>
                        <th>Dạng bào chế</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($medicines as $medicine)
                    <tr>
                        <td>{{ $medicine->id }}</td>
                        <td>{{ $medicine->name }}</td>
                        <td>{{ number_format($medicine->price) }} VNĐ</td>
                        <td>{{ $medicine->stock_quantity }}</td>
                        <td>{{ $medicine->manufacturer }}</td>
                        <td>{{ $medicine->expiry_date->format('d/m/Y') }}</td>
                        <td>{{ $medicine->dosage_form }}</td>
                        <td>
                            <button class="btn btn-sm btn-info" data-bs-toggle="modal" 
                                    data-bs-target="#editMedicineModal" 
                                    data-medicine="{{ json_encode($medicine) }}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="deleteMedicine({{ $medicine->id }})">
                                <i class="fas fa-trash"></i>
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
            <form action="{{ route('admin.medicine.store') }}" method="POST">
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
                        <label class="form-label">Số lượng</label>
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
                        <select class="form-select" name="dosage_form" required>
                            <option value="viên">Viên</option>
                            <option value="siro">Siro</option>
                            <option value="tiêm">Tiêm</option>
                            <option value="khác">Khác</option>
                        </select>
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
@endsection

@section('scripts')
<script>
    function deleteMedicine(medicineId) {
        if (confirm('Bạn có chắc muốn xóa thuốc này?')) {
            fetch(`/admin/medicine/${medicineId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    location.reload();
                }
            });
        }
    }
</script>
@endsection 