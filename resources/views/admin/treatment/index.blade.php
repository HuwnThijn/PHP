@extends('layouts.admin')

@section('title', 'Quản lý trị liệu')

@section('page-title', 'Quản lý phương pháp trị liệu')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Danh sách phương pháp trị liệu</h5>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTreatmentModal">
                <i class="fas fa-plus"></i> Thêm phương pháp mới
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên phương pháp</th>
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
                        <td>{{ number_format($treatment->price) }} VNĐ</td>
                        <td>{{ $treatment->duration }}</td>
                        <td>{{ $treatment->equipment_needed }}</td>
                        <td>
                            <button class="btn btn-sm btn-info" data-bs-toggle="modal" 
                                    data-bs-target="#editTreatmentModal" 
                                    data-treatment="{{ json_encode($treatment) }}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="deleteTreatment({{ $treatment->id }})">
                                <i class="fas fa-trash"></i>
                            </button>
                            <button class="btn btn-sm btn-secondary" data-bs-toggle="modal"
                                    data-bs-target="#viewTreatmentModal"
                                    data-treatment="{{ json_encode($treatment) }}">
                                <i class="fas fa-eye"></i>
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
                <h5 class="modal-title">Thêm phương pháp trị liệu mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.treatment.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tên phương pháp</label>
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
                        <label class="form-label">Thời gian (phút)</label>
                        <input type="number" class="form-control" name="duration" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Thiết bị cần thiết</label>
                        <textarea class="form-control" name="equipment_needed" rows="2" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Chống chỉ định</label>
                        <textarea class="form-control" name="contraindications" rows="2" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tác dụng phụ</label>
                        <textarea class="form-control" name="side_effects" rows="2" required></textarea>
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

<!-- Modal xem chi tiết -->
<div class="modal fade" id="viewTreatmentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Chi tiết phương pháp trị liệu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <dl class="row">
                    <dt class="col-sm-4">Tên phương pháp</dt>
                    <dd class="col-sm-8" id="view-name"></dd>

                    <dt class="col-sm-4">Mô tả</dt>
                    <dd class="col-sm-8" id="view-description"></dd>

                    <dt class="col-sm-4">Giá</dt>
                    <dd class="col-sm-8" id="view-price"></dd>

                    <dt class="col-sm-4">Thời gian</dt>
                    <dd class="col-sm-8" id="view-duration"></dd>

                    <dt class="col-sm-4">Thiết bị</dt>
                    <dd class="col-sm-8" id="view-equipment"></dd>

                    <dt class="col-sm-4">Chống chỉ định</dt>
                    <dd class="col-sm-8" id="view-contraindications"></dd>

                    <dt class="col-sm-4">Tác dụng phụ</dt>
                    <dd class="col-sm-8" id="view-side-effects"></dd>
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function deleteTreatment(treatmentId) {
        if (confirm('Bạn có chắc muốn xóa phương pháp trị liệu này?')) {
            fetch(`/admin/treatment/${treatmentId}`, {
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

    // Xử lý hiển thị chi tiết
    document.getElementById('viewTreatmentModal').addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const treatment = JSON.parse(button.getAttribute('data-treatment'));
        
        document.getElementById('view-name').textContent = treatment.name;
        document.getElementById('view-description').textContent = treatment.description;
        document.getElementById('view-price').textContent = new Intl.NumberFormat('vi-VN', {
            style: 'currency',
            currency: 'VND'
        }).format(treatment.price);
        document.getElementById('view-duration').textContent = `${treatment.duration} phút`;
        document.getElementById('view-equipment').textContent = treatment.equipment_needed;
        document.getElementById('view-contraindications').textContent = treatment.contraindications;
        document.getElementById('view-side-effects').textContent = treatment.side_effects;
    });
</script>
@endsection 