@extends('doctor.layouts.app')

@section('title', 'Chi tiết đơn thuốc')

@section('page-title', 'Chi tiết đơn thuốc #' . $prescription->id_prescription)

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <a href="{{ route('doctor.prescriptions.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
        
        <a href="#" onclick="window.print()" class="btn btn-info ml-2">
            <i class="fas fa-print"></i> In đơn thuốc
        </a>
    </div>
</div>

<div class="row">
    <!-- Thông tin bệnh nhân và đơn thuốc -->
    <div class="col-lg-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Thông tin bệnh nhân</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Bệnh nhân:</strong> {{ $prescription->patient->name }}
                </div>
                <div class="mb-3">
                    <strong>Email:</strong> {{ $prescription->patient->email }}
                </div>
                <div class="mb-3">
                    <strong>Số điện thoại:</strong> {{ $prescription->patient->phone }}
                </div>
                <div class="mb-3">
                    <strong>Địa chỉ:</strong> {{ $prescription->patient->address ?? 'Không có' }}
                </div>
            </div>
        </div>
        
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Thông tin đơn thuốc</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Mã đơn thuốc:</strong> #{{ $prescription->id_prescription }}
                </div>
                <div class="mb-3">
                    <strong>Ngày kê:</strong> {{ $prescription->created_at->format('d/m/Y H:i') }}
                </div>
                <div class="mb-3">
                    <strong>Trạng thái:</strong>
                    @if($prescription->status == 'pending')
                        <span class="badge bg-warning">Chờ xử lý</span>
                    @elseif($prescription->status == 'completed')
                        <span class="badge bg-success">Đã hoàn thành</span>
                    @else
                        <span class="badge bg-secondary">{{ $prescription->status }}</span>
                    @endif
                </div>
                @if($prescription->processed_by)
                <div class="mb-3">
                    <strong>Dược sĩ xử lý:</strong> {{ $prescription->processedBy->name }}
                </div>
                <div class="mb-3">
                    <strong>Ngày xử lý:</strong> {{ $prescription->processed_at->format('d/m/Y H:i') }}
                </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Chi tiết đơn thuốc -->
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Chẩn đoán</h6>
            </div>
            <div class="card-body">
                <p>{{ $prescription->diagnosis }}</p>
                
                @if($prescription->notes)
                <hr>
                <h6 class="font-weight-bold">Ghi chú:</h6>
                <p>{{ $prescription->notes }}</p>
                @endif
            </div>
        </div>
        
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Thuốc đã kê</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Thuốc</th>
                                <th>Số lượng</th>
                                <th>Liều dùng</th>
                                <th>Hướng dẫn</th>
                                <th>Đơn giá</th>
                                <th>Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($prescription->items as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item->medicine->name }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ $item->dosage }}</td>
                                    <td>{{ $item->instructions }}</td>
                                    <td>{{ number_format($item->price) }} VNĐ</td>
                                    <td>{{ number_format($item->price * $item->quantity) }} VNĐ</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">Không có thuốc nào trong đơn</td>
                                </tr>
                            @endforelse
                            
                            @if($prescription->items->count() > 0)
                                <tr>
                                    <td colspan="6" class="text-right"><strong>Tổng cộng:</strong></td>
                                    <td><strong>{{ number_format($prescription->total_amount ?? $prescription->items->sum(function($item) { return $item->price * $item->quantity; })) }} VNĐ</strong></td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
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