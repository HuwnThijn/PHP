@extends('doctor.layouts.app')

@section('title', 'Chi tiết bệnh án')

@section('page-title', 'Chi tiết bệnh án - ' . $medicalRecord->patient->name)

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <a href="{{ route('doctor.patients.history') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
        
        <a href="#" onclick="window.print()" class="btn btn-info ml-2">
            <i class="fas fa-print"></i> In hồ sơ
        </a>
    </div>
</div>

<div class="row">
    <!-- Thông tin bệnh nhân -->
    <div class="col-lg-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Thông tin bệnh nhân</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Bệnh nhân:</strong> {{ $medicalRecord->patient->name }}
                </div>
                <div class="mb-3">
                    <strong>Email:</strong> {{ $medicalRecord->patient->email }}
                </div>
                <div class="mb-3">
                    <strong>Số điện thoại:</strong> {{ $medicalRecord->patient->phone }}
                </div>
                <div class="mb-3">
                    <strong>Địa chỉ:</strong> {{ $medicalRecord->patient->address ?? 'Không có' }}
                </div>
                <div class="mb-3">
                    <strong>Ngày tiếp nhận:</strong> {{ $medicalRecord->created_at->format('d/m/Y H:i') }}
                </div>
                <div class="mb-3">
                    <strong>Ngày khám:</strong> {{ $medicalRecord->updated_at->format('d/m/Y H:i') }}
                </div>
            </div>
        </div>
    </div>
    
    <!-- Thông tin khám bệnh -->
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Kết quả khám bệnh</h6>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <h5 class="font-weight-bold">Chẩn đoán</h5>
                    <p>{{ $medicalRecord->diagnosis }}</p>
                </div>
                
                <div class="mb-4">
                    <h5 class="font-weight-bold">Ghi chú</h5>
                    <p>{{ $medicalRecord->notes ?? 'Không có ghi chú' }}</p>
                </div>
            </div>
        </div>
        
        <!-- Đơn thuốc -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Đơn thuốc</h6>
            </div>
            <div class="card-body">
                @if($medicalRecord->prescriptions->count() > 0)
                    @foreach($medicalRecord->prescriptions as $prescription)
                        <div class="mb-3">
                            <h5 class="font-weight-bold">Đơn thuốc #{{ $prescription->id_prescription }}</h5>
                            <p><strong>Trạng thái:</strong> 
                                @if($prescription->status == 'pending')
                                    <span class="badge bg-warning">Chờ xử lý</span>
                                @elseif($prescription->status == 'completed')
                                    <span class="badge bg-success">Đã hoàn thành</span>
                                @else
                                    <span class="badge bg-secondary">{{ $prescription->status }}</span>
                                @endif
                            </p>
                            
                            <div class="table-responsive mt-3">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Thuốc</th>
                                            <th>Số lượng</th>
                                            <th>Liều dùng</th>
                                            <th>Hướng dẫn</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($prescription->items as $item)
                                            <tr>
                                                <td>{{ $item->medicine->name }}</td>
                                                <td>{{ $item->quantity }}</td>
                                                <td>{{ $item->dosage }}</td>
                                                <td>{{ $item->instructions }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center">Không có thuốc trong đơn</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-center">Không có đơn thuốc nào được kê</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    @media print {
        .sidebar, nav, .btn, footer {
            display: none !important;
        }
        
        .card {
            box-shadow: none !important;
            border: 1px solid #ddd !important;
        }
        
        .card-header {
            background-color: #f8f9fc !important;
            color: #000 !important;
        }
        
        body {
            padding: 0 !important;
            margin: 0 !important;
        }
        
        .container-fluid {
            width: 100% !important;
            padding: 0 !important;
        }
    }
</style>
@endsection 