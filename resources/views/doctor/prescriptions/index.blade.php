@extends('doctor.layouts.app')

@section('title', 'Quản lý đơn thuốc')

@section('page-title', 'Quản lý đơn thuốc')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Danh sách đơn thuốc</h6>
        <div class="input-group" style="width: 300px;">
            <input type="text" class="form-control" id="searchInput" placeholder="Tìm kiếm...">
            <button class="btn btn-outline-secondary" type="button">
                <i class="fas fa-search"></i>
            </button>
        </div>
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
                        <th>Bệnh nhân</th>
                        <th>Chẩn đoán</th>
                        <th>Ngày kê</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($prescriptions as $prescription)
                    <tr>
                        <td>#{{ $prescription->id_prescription }}</td>
                        <td>{{ $prescription->patient->name }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($prescription->diagnosis, 50) }}</td>
                        <td>{{ $prescription->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            @if($prescription->status == 'pending')
                                <span class="badge bg-warning">Chờ xử lý</span>
                            @elseif($prescription->status == 'completed')
                                <span class="badge bg-success">Đã hoàn thành</span>
                            @else
                                <span class="badge bg-secondary">{{ $prescription->status }}</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('doctor.prescriptions.show', $prescription->id_prescription) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i> Xem
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">Không có đơn thuốc nào</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{ $prescriptions->links() }}
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Tìm kiếm trên bảng
        $("#searchInput").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#dataTable tbody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });
</script>
@endsection 