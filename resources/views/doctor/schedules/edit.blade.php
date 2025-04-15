@extends('doctor.layouts.app')

@section('title', 'Chỉnh sửa lịch làm việc')

@section('page-title', 'Chỉnh sửa lịch làm việc')

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
            <form action="{{ route('doctor.schedules.update', $schedule->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="date">Ngày <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('date') is-invalid @enderror" 
                                id="date" name="date" 
                                value="{{ old('date', $schedule->date->format('Y-m-d')) }}" required>
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
                                value="{{ old('start_time', $schedule->start_time->format('H:i')) }}" required>
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
                                value="{{ old('end_time', $schedule->end_time->format('H:i')) }}" required>
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
                                <input type="checkbox" class="form-check-input" id="is_available" name="is_available" 
                                    {{ old('is_available', $schedule->is_available) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_available">Còn trống</label>
                            </div>
                            <small class="form-text text-muted">
                                Bỏ chọn nếu lịch này đã được đặt hoặc không thể sử dụng
                            </small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="repeat_weekly" name="repeat_weekly" 
                                    {{ old('repeat_weekly', $schedule->repeat_weekly) ? 'checked' : '' }}>
                                <label class="form-check-label" for="repeat_weekly">Lặp lại hàng tuần</label>
                            </div>
                            <small class="form-text text-muted">
                                Chọn nếu lịch này sẽ lặp lại vào cùng thứ hàng tuần
                            </small>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="form-group mb-3">
                            <label for="notes">Ghi chú</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                id="notes" name="notes" rows="3">{{ old('notes', $schedule->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Cập nhật
                    </button>
                    <a href="{{ route('doctor.schedules.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Hủy
                    </a>
                    <form action="{{ route('doctor.schedules.destroy', $schedule->id) }}" method="POST" class="ms-auto">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa lịch này?')">
                            <i class="fas fa-trash"></i> Xóa lịch
                        </button>
                    </form>
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
    });
</script>
@endsection 