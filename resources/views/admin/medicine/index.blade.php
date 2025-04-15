@extends('admin.layouts.app')

@section('title', 'Quản lý Thuốc')

@section('page-title', 'Quản lý thuốc')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <div class="d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Danh sách thuốc</h6>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMedicineModal">
                <i class="fas fa-plus"></i> Thêm thuốc
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên thuốc</th>
                        <th>Mô tả</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Nhà sản xuất</th>
                        <th>Hạn sử dụng</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($medicines as $medicine)
                    <tr>
                        <td>{{ $medicine->id }}</td>
                        <td>{{ $medicine->name }}</td>
                        <td>{{ Str::limit($medicine->description, 50) }}</td>
                        <td>{{ number_format($medicine->price) }} VNĐ</td>
                        <td>{{ $medicine->stock_quantity }}</td>
                        <td>{{ $medicine->manufacturer }}</td>
                        <td>{{ $medicine->expiry_date }}</td>
                        <td>
                            @if($medicine->stock_quantity > 0)
                                <span class="badge bg-success">Còn hàng</span>
                            @else
                                <span class="badge bg-danger">Hết hàng</span>
                            @endif
                        </td>
                        <td>
                            <button class="btn btn-sm btn-info mb-1" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#editMedicineModal" 
                                    data-medicine="{{ json_encode($medicine) }}">
                                <i class="fas fa-edit"></i> Sửa
                            </button>
                            <button class="btn btn-sm btn-danger" 
                                    onclick="deleteMedicine({{ $medicine->id }})">
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
                        <input type="number" class="form-control" name="price" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Số lượng</label>
                        <input type="number" class="form-control" name="stock_quantity" required>
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
                        <label class="form-label">Dạng thuốc</label>
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
                        <input type="number" class="form-control" name="price" id="edit_price" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Số lượng</label>
                        <input type="number" class="form-control" name="stock_quantity" id="edit_stock_quantity" required>
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
                        <label class="form-label">Dạng thuốc</label>
                        <input type="text" class="form-control" name="dosage_form" id="edit_dosage_form" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Hướng dẫn sử dụng</label>
                        <textarea class="form-control" name="usage_instructions" id="edit_usage_instructions" rows="3" required></textarea>
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
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Xử lý modal chỉnh sửa
        const editModal = document.getElementById('editMedicineModal');
        editModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const medicine = JSON.parse(button.getAttribute('data-medicine'));
            
            // Cập nhật action của form và lưu id vào data-id
            const form = document.getElementById('editMedicineForm');
            form.action = `/admin/medicine/${medicine.id}`;
            form.setAttribute('data-id', medicine.id);
            
            // Điền thông tin vào form
            document.getElementById('edit_name').value = medicine.name;
            document.getElementById('edit_description').value = medicine.description;
            document.getElementById('edit_price').value = medicine.price;
            document.getElementById('edit_stock_quantity').value = medicine.stock_quantity;
            document.getElementById('edit_manufacturer').value = medicine.manufacturer;
            document.getElementById('edit_expiry_date').value = medicine.expiry_date;
            document.getElementById('edit_dosage_form').value = medicine.dosage_form;
            document.getElementById('edit_usage_instructions').value = medicine.usage_instructions;
        });

        // Xử lý submit form chỉnh sửa
        document.getElementById('editMedicineForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const action = this.action;
            
            fetch(action, {
                method: 'POST',
                body: formData
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
                alert('Có lỗi xảy ra khi cập nhật thông tin');
            });
        });
    });

    // Xử lý xóa thuốc
    function deleteMedicine(id) {
        if (confirm('Bạn có chắc muốn xóa thuốc này?')) {
            fetch(`/admin/medicine/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
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
                alert('Có lỗi xảy ra khi xóa thuốc');
            });
        }
    }
</script>
@endsection 