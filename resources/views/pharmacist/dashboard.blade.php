@extends('layouts.pharmacist')

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
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Đơn thuốc</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pendingPrescriptions }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-6 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Thuốc sắp hết</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $lowStockItems }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Đơn thuốc gần đây -->
    <div class="col-lg-12 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Đơn thuốc gần đây</h6>
                <a href="{{ route('pharmacist.prescriptions.pending') }}" class="btn btn-sm btn-primary">
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
                                <th>Bác sĩ</th>
                                <th>Trạng thái</th>
                                <th>Ngày tạo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentPrescriptions as $prescription)
                            <tr>
                                <td>#{{ $prescription->id }}</td>
                                <td>{{ $prescription->patient->name }}</td>
                                <td>{{ $prescription->doctor->name }}</td>
                                <td>
                                    @if($prescription->status == 'pending')
                                        <span class="badge badge-warning text-black">Chờ xử lý</span>
                                    @elseif($prescription->status == 'completed')
                                        <span class="badge badge-success text-black">Hoàn thành</span>
                                    @elseif($prescription->status == 'cancelled')
                                        <span class="badge badge-danger text-black">Đã hủy</span>
                                    @else
                                        <span class="badge badge-secondary text-black">{{ $prescription->status }}</span>
                                    @endif
                                </td>
                                <td>{{ $prescription->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">Không có đơn thuốc nào gần đây</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 