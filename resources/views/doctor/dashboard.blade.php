@extends('doctor.layouts.app')

@section('title', 'Tổng quan')

@section('page-title', 'Tổng quan hệ thống')

@section('content')
<!-- Cards thống kê -->
<div class="row">
    <div class="col-xl-6 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Bệnh nhân chờ khám</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pendingPatients }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-injured fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-6 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Bệnh nhân khám hôm nay</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $patientsToday }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clipboard-check fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Bệnh nhân chờ khám -->
    <div class="col-lg-6 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Bệnh nhân chờ khám</h6>
                <a href="{{ route('doctor.patients.pending') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-arrow-right"></i> Xem tất cả
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Bệnh nhân</th>
                                <th>Ngày tiếp nhận</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pendingMedicalRecords as $record)
                            <tr>
                                <td>#{{ $record->id_medical_record }}</td>
                                <td>{{ $record->patient->name }}</td>
                                <td>{{ $record->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('doctor.patients.examination', $record->id_medical_record) }}" 
                                       class="btn btn-info btn-sm">
                                        <i class="fas fa-stethoscope"></i> Khám
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">Không có bệnh nhân nào đang chờ khám</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bệnh nhân đã khám gần đây -->
    <div class="col-lg-6 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-info">Bệnh nhân đã khám gần đây</h6>
                <a href="{{ route('doctor.patients.history') }}" class="btn btn-sm btn-info">
                    <i class="fas fa-arrow-right"></i> Xem tất cả
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Bệnh nhân</th>
                                <th>Chẩn đoán</th>
                                <th>Ngày khám</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentMedicalRecords as $record)
                            <tr>
                                <td>#{{ $record->id_medical_record }}</td>
                                <td>{{ $record->patient->name }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($record->diagnosis, 30) }}</td>
                                <td>{{ $record->updated_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('doctor.patients.show', $record->id_medical_record) }}" 
                                       class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i> Xem
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">Không có bệnh nhân nào đã khám gần đây</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Thông báo và hướng dẫn -->
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Thông báo và hướng dẫn</h6>
            </div>
            <div class="card-body">
                <p>Chào mừng đến với hệ thống quản lý bệnh nhân dành cho bác sĩ. Dưới đây là một số hướng dẫn nhanh:</p>
                <ul>
                    <li>Bạn có thể xem danh sách bệnh nhân chờ khám và bắt đầu khám cho bệnh nhân.</li>
                    <li>Sau khi khám xong, hãy nhập chẩn đoán và kê đơn thuốc cho bệnh nhân.</li>
                    <li>Đơn thuốc sẽ được chuyển đến dược sĩ để chuẩn bị thuốc.</li>
                    <li>Bạn có thể xem lịch sử khám bệnh bất kỳ lúc nào.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection 