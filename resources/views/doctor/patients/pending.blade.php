@extends('doctor.layouts.app')

@section('title', 'Bệnh nhân chờ khám')

@section('page-title', 'Danh sách bệnh nhân chờ khám')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Bệnh nhân chờ khám</h6>
        <div class="input-group" style="width: 300px;">
            <input type="text" class="form-control" id="searchInput" placeholder="Tìm kiếm bệnh nhân...">
            <button class="btn btn-outline-secondary" type="button">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Bệnh nhân</th>
                        <th>Liên hệ</th>
                        <th>Ghi chú</th>
                        <th>Ngày tiếp nhận</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($patients as $patient)
                    <tr>
                        <td>#{{ $patient->id_medical_record }}</td>
                        <td>{{ $patient->patient->name }}</td>
                        <td>
                            <div>Email: {{ $patient->patient->email }}</div>
                            <div>SĐT: {{ $patient->patient->phone }}</div>
                        </td>
                        <td>{{ $patient->notes ?? 'Không có ghi chú' }}</td>
                        <td>{{ $patient->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <a href="{{ route('doctor.patients.examination', $patient->id_medical_record) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-stethoscope"></i> Khám
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">Không có bệnh nhân nào đang chờ khám</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{ $patients->links() }}
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Tìm kiếm bệnh nhân trên bảng
        $("#searchInput").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#dataTable tbody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });
</script>
@endsection 