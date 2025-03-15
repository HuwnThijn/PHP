@extends('layouts.pharmacist')

@section('title', 'Đơn thuốc chờ xử lý')

@section('page-title', 'Đơn thuốc chờ xử lý')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Danh sách đơn thuốc chờ xử lý</h6>
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

        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Bệnh nhân</th>
                        <th>Bác sĩ</th>
                        <th>Ngày tạo</th>
                        <th>Tổng tiền</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($prescriptions as $prescription)
                    <tr>
                        <td>#{{ $prescription->id }}</td>
                        <td>{{ $prescription->patient->name }}</td>
                        <td>{{ $prescription->doctor->name }}</td>
                        <td>{{ $prescription->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ number_format($prescription->total_amount) }} VNĐ</td>
                        <td>
                            <a href="{{ route('pharmacist.prescriptions.show', $prescription->id) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i> Xem
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">Không có đơn thuốc nào chờ xử lý</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{ $prescriptions->links() }}
    </div>
</div>
@endsection 