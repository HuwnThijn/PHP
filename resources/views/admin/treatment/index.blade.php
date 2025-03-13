@extends('layouts.admin')

@section('title', 'Quản lý trị liệu')

@section('page-title', 'Quản lý trị liệu')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <div class="d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Danh sách trị liệu</h6>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTreatmentModal">
                <i class="fas fa-plus"></i> Thêm trị liệu
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên trị liệu</th>
                        <th>Mô tả</th>
                        <th>Giá</th>
                        <th>Thời gian (phút)</th>
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
                        <td>{{ number_format($treatment->price) }} VNĐ</td>
                        <td>{{ $treatment->duration }}</td>
                        <td>{{ $treatment->equipment_needed }}</td>
                        <td>
                            <button class="btn btn-sm btn-info mb-1" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#editTreatmentModal" 
                                    data-treatment="{{ json_encode($treatment) }}">
                                <i class="fas fa-edit"></i> Sửa
                            </button>
                            <button class="btn btn-sm btn-danger" 
                                    onclick="deleteTreatment({{ $treatment->id }})">
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

<!-- Modal thêm trị liệu -->
<div class="modal fade" id="addTreatmentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thêm trị liệu mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.treatment.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tên trị liệu</label>
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
                        <label class="form-label">Thời gian (phút)</label>
                        <input type="number" class="form-control" name="duration" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Thiết bị cần thiết</label>
                        <input type="text" class="form-control" name="equipment_needed" required>
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

<!-- Modal chỉnh sửa trị liệu -->
<div class="modal fade" id="editTreatmentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Chỉnh sửa thông tin trị liệu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editTreatmentForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tên trị liệu</label>
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
                        <label class="form-label">Thời gian (phút)</label>
                        <input type="number" class="form-control" name="duration" id="edit_duration" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Thiết bị cần thiết</label>
                        <input type="text" class="form-control" name="equipment_needed" id="edit_equipment_needed" required>
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
        const editModal = document.getElementById('editTreatmentModal');
        editModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const treatment = JSON.parse(button.getAttribute('data-treatment'));
            
            // Cập nhật action của form
            const form = document.getElementById('editTreatmentForm');
            form.action = `/admin/treatment/${treatment.id}`;
            
            // Điền thông tin vào form
            document.getElementById('edit_name').value = treatment.name;
            document.getElementById('edit_description').value = treatment.description;
            document.getElementById('edit_price').value = treatment.price;
            document.getElementById('edit_duration').value = treatment.duration;
            document.getElementById('edit_equipment_needed').value = treatment.equipment_needed;
            document.getElementById('edit_contraindications').value = treatment.contraindications;
            document.getElementById('edit_side_effects').value = treatment.side_effects;
        });

        // Xử lý submit form chỉnh sửa
        document.getElementById('editTreatmentForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
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

    // Xử lý xóa trị liệu
    function deleteTreatment(id) {
        if (confirm('Bạn có chắc muốn xóa trị liệu này?')) {
            fetch(`/admin/treatment/${id}`, {
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
                alert('Có lỗi xảy ra khi xóa trị liệu');
            });
        }
    }
</script>
@endsection 