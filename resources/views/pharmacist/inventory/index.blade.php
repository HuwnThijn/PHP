@extends('layouts.pharmacist')

@section('title', 'Quản lý kho')

@section('page-title', 'Quản lý kho')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <a href="{{ route('pharmacist.inventory.import') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nhập kho
        </a>
        <a href="{{ route('pharmacist.inventory.export') }}" class="btn btn-info">
            <i class="fas fa-file-export"></i> Lịch sử xuất/nhập kho
        </a>
    </div>
    <div class="col-md-6">
        <form action="{{ route('pharmacist.inventory.index') }}" method="GET" class="form-inline float-right">
            <div class="input-group">
                <input type="text" class="form-control" name="search" placeholder="Tìm kiếm thuốc..." value="{{ request('search') }}">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Danh sách thuốc trong kho</h6>
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
                        <th>Tên thuốc</th>
                        <th>Mô tả</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Nhà sản xuất</th>
                        <th>Hạn sử dụng</th>
                        <th>Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($medicines as $medicine)
                    <tr>
                        <td>{{ $medicine->id }}</td>
                        <td>{{ $medicine->name }}</td>
                        <td>{{ Str::limit($medicine->description, 50) }}</td>
                        <td>{{ number_format($medicine->price) }} VNĐ</td>
                        <td class="{{ $medicine->stock_quantity < 10 ? 'text-danger font-weight-bold' : '' }}">
                            {{ $medicine->stock_quantity }}
                        </td>
                        <td>{{ $medicine->manufacturer }}</td>
                        <td>{{ $medicine->expiry_date ? $medicine->expiry_date->format('d/m/Y') : 'N/A' }}</td>
                        <td>
                            @if($medicine->stock_quantity <= 0)
                                <span class="badge badge-danger">Hết hàng</span>
                            @elseif($medicine->stock_quantity < 10)
                                <span class="badge badge-warning">Sắp hết</span>
                            @else
                                <span class="badge badge-success">Còn hàng</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">Không có thuốc nào trong kho</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{ $medicines->links() }}
    </div>
</div>
@endsection 