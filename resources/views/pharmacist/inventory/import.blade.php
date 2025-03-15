@extends('layouts.pharmacist')

@section('title', 'Nhập kho')

@section('page-title', 'Nhập kho')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Nhập thuốc vào kho</h6>
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

                <form action="{{ route('pharmacist.inventory.process-import') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="medicine_id">Chọn thuốc</label>
                        <select class="form-control @error('medicine_id') is-invalid @enderror" id="medicine_id" name="medicine_id" required>
                            <option value="">-- Chọn thuốc --</option>
                            @foreach($medicines as $medicine)
                                <option value="{{ $medicine->id }}">{{ $medicine->name }} (Hiện có: {{ $medicine->stock_quantity }})</option>
                            @endforeach
                        </select>
                        @error('medicine_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="quantity">Số lượng</label>
                        <input type="number" class="form-control @error('quantity') is-invalid @enderror" id="quantity" name="quantity" min="1" required>
                        @error('quantity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="batch_number">Số lô</label>
                        <input type="text" class="form-control @error('batch_number') is-invalid @enderror" id="batch_number" name="batch_number" required>
                        @error('batch_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="expiry_date">Hạn sử dụng</label>
                        <input type="date" class="form-control @error('expiry_date') is-invalid @enderror" id="expiry_date" name="expiry_date" required>
                        @error('expiry_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="supplier">Nhà cung cấp</label>
                        <input type="text" class="form-control @error('supplier') is-invalid @enderror" id="supplier" name="supplier" required>
                        @error('supplier')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="unit_price">Giá nhập</label>
                        <input type="number" class="form-control @error('unit_price') is-invalid @enderror" id="unit_price" name="unit_price" min="0" required>
                        @error('unit_price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="note">Ghi chú</label>
                        <textarea class="form-control" id="note" name="note" rows="3"></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Nhập kho</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection