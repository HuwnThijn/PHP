@extends('doctor.layouts.app')

@section('title', 'Thêm lịch làm việc')

@section('page-title', 'Thêm lịch làm việc mới')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <a href="{{ route('doctor.schedules.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Thông tin lịch làm việc</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('doctor.schedules.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="date">Ngày <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('date') is-invalid @enderror" 
                                id="date" name="date" 
                                value="{{ request('date', old('date', now()->format('Y-m-d'))) }}" 
                                min="{{ now()->format('Y-m-d') }}" required>
                            @error('date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mb-3">
                            <label for="start_time">Thời gian bắt đầu <span class="text-danger">*</span></label>
                            <input type="time" class="form-control @error('start_time') is-invalid @enderror" 
                                id="start_time" name="start_time" 
                                value="{{ old('start_time', '08:00') }}" required>
                            @error('start_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mb-3">
                            <label for="end_time">Thời gian kết thúc <span class="text-danger">*</span></label>
                            <input type="time" class="form-control @error('end_time') is-invalid @enderror" 
                                id="end_time" name="end_time" 
                                value="{{ old('end_time', '17:00') }}" required>
                            @error('end_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="repeat_weekly" name="repeat_weekly" {{ old('repeat_weekly') ? 'checked' : '' }}>
                                <label class="form-check-label" for="repeat_weekly">Lặp lại hàng tuần</label>
                            </div>
                            <small class="form-text text-muted">
                                Nếu chọn, lịch này sẽ được lặp lại vào cùng thứ hàng tuần
                            </small>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="form-group mb-3">
                            <label for="notes">Ghi chú</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Lưu lịch làm việc
                    </button>
                    <a href="{{ route('doctor.schedules.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Hủy
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Kiểm tra nếu thời gian kết thúc nhỏ hơn thời gian bắt đầu
        $('#end_time, #start_time').change(function() {
            var startTime = $('#start_time').val();
            var endTime = $('#end_time').val();
            
            if (startTime && endTime && startTime >= endTime) {
                alert('Thời gian kết thúc phải sau thời gian bắt đầu');
                $('#end_time').val('');
            }
        });
        
        // Gợi ý các múi giờ phổ biến cho lịch làm việc
        $('#start_time').on('focus', function() {
            if (!$(this).val()) {
                $(this).val('08:00');
            }
        });
        
        $('#end_time').on('focus', function() {
            if (!$(this).val()) {
                $(this).val('17:00');
            }
        });
    });
</script>
@endsection 