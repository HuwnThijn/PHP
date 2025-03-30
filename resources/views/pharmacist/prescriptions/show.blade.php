@extends('layouts.pharmacist')

@section('title', 'Chi tiết đơn thuốc')

@section('page-title', 'Chi tiết đơn thuốc #' . $prescription->id)

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Thông tin đơn thuốc</h6>
                <div>
                    @if($prescription->status == 'pending')
                        <span class="badge badge-warning">Chờ xử lý</span>
                    @elseif($prescription->status == 'completed')
                        <span class="badge badge-success">Đã xử lý</span>
                    @else
                        <span class="badge badge-secondary">{{ $prescription->status }}</span>
                    @endif
                </div>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5 class="font-weight-bold">Thông tin bệnh nhân</h5>
                        <p><strong>Tên:</strong> {{ $prescription->patient->name }}</p>
                        <p><strong>Email:</strong> {{ $prescription->patient->email }}</p>
                        <p><strong>Điện thoại:</strong> {{ $prescription->patient->phone }}</p>
                        <p><strong>Địa chỉ:</strong> {{ $prescription->patient->address }}</p>
                    </div>
                    <div class="col-md-6">
                        <h5 class="font-weight-bold">Thông tin bác sĩ</h5>
                        <p><strong>Tên:</strong> {{ $prescription->doctor->name }}</p>
                        <p><strong>Email:</strong> {{ $prescription->doctor->email }}</p>
                        <p><strong>Điện thoại:</strong> {{ $prescription->doctor->phone }}</p>
                        <p><strong>Chuyên môn:</strong> {{ $prescription->doctor->specialization }}</p>
                    </div>
                </div>

                <h5 class="font-weight-bold mb-3">Danh sách thuốc</h5>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Tên thuốc</th>
                                <th>Liều lượng</th>
                                <th>Số lượng</th>
                                <th>Đơn giá</th>
                                <th>Thành tiền</th>
                                <th>Tồn kho</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($prescription->items as $item)
                            <tr>
                                <td>{{ $item->medicine->name }}</td>
                                <td>{{ $item->dosage }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ number_format($item->price) }} VNĐ</td>
                                <td>{{ number_format($item->price * $item->quantity) }} VNĐ</td>
                                <td>
                                    @if($item->medicine->stock_quantity >= $item->quantity)
                                        <span class="badge badge-success">{{ $item->medicine->stock_quantity }}</span>
                                    @else
                                        <span class="badge badge-danger">{{ $item->medicine->stock_quantity }} (Thiếu)</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="text-right font-weight-bold">Tổng cộng:</td>
                                <td colspan="2" class="font-weight-bold">{{ number_format($prescription->total_amount) }} VNĐ</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="mt-4">
                    <h5 class="font-weight-bold">Ghi chú của bác sĩ</h5>
                    <p>{{ $prescription->notes ?? 'Không có ghi chú' }}</p>
                </div>

                @if($prescription->status == 'completed')
                <div class="mt-4">
                    <h5 class="font-weight-bold">Thông tin xử lý</h5>
                    <p><strong>Người xử lý:</strong> {{ $prescription->processedBy->name ?? 'N/A' }}</p>
                    <p><strong>Thời gian xử lý:</strong> {{ $prescription->processed_at ? $prescription->processed_at->format('d/m/Y H:i') : 'N/A' }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        @if($prescription->status == 'pending')
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Xử lý đơn thuốc</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('pharmacist.prescriptions.process', $prescription->id) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="payment_method">Phương thức thanh toán</label>
                        <select class="form-control" id="payment_method" name="payment_method" required>
                            <option value="cash">Tiền mặt</option>
                            <option value="card">Thẻ</option>
                            <option value="transfer">Chuyển khoản</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-success btn-block">
                        <i class="fas fa-check"></i> Xác nhận và xuất thuốc
                    </button>
                </form>
            </div>
        </div>
        @endif

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Thông tin đơn thuốc</h6>
            </div>
            <div class="card-body">
                <p><strong>Mã đơn thuốc:</strong> #{{ $prescription->id }}</p>
                <p><strong>Ngày tạo:</strong> {{ $prescription->created_at->format('d/m/Y H:i') }}</p>
                <p><strong>Trạng thái:</strong> 
                    @if($prescription->status == 'pending')
                        <span class="badge badge-warning">Chờ xử lý</span>
                    @elseif($prescription->status == 'completed')
                        <span class="badge badge-success">Đã xử lý</span>
                    @else
                        <span class="badge badge-secondary">{{ $prescription->status }}</span>
                    @endif
                </p>
                <p><strong>Tổng tiền:</strong> {{ number_format($prescription->total_amount) }} VNĐ</p>
                
                <div class="mt-3">
                    <a href="{{ route('pharmacist.prescriptions.pending') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </a>
                    
                    @if($prescription->status == 'completed')
                    <a href="#" class="btn btn-primary btn-sm" onclick="window.print()">
                        <i class="fas fa-print"></i> In đơn thuốc
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 