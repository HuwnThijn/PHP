@extends('admin.layouts.app')

@section('title', 'Lịch làm việc của bác sĩ ' . $doctor->name)

@section('page-title', 'Lịch làm việc của bác sĩ ' . $doctor->name)

@section('styles')
<style>
    .schedule-table th, .schedule-table td {
        text-align: center;
        vertical-align: middle;
    }
    .schedule-card {
        margin-bottom: 10px;
        border-left: 4px solid #4e73df;
    }
    .schedule-time {
        font-weight: bold;
    }
    .schedule-actions {
        display: flex;
        justify-content: flex-end;
    }
    .week-navigator {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
</style>
@endsection

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <div class="d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Thông tin bác sĩ</h6>
            <a href="{{ route('admin.schedules.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại danh sách
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-2">
                @if($doctor->avatar)
                    <img src="{{ asset('storage/' . $doctor->avatar) }}" alt="{{ $doctor->name }}" class="img-fluid rounded-circle mb-3">
                @else
                    <img src="{{ asset('img/default-avatar.png') }}" alt="{{ $doctor->name }}" class="img-fluid rounded-circle mb-3" style="width: 150px; height: 150px;">
                @endif
            </div>
            <div class="col-md-10">
                <h4>{{ $doctor->name }}</h4>
                <p><strong>Chuyên khoa:</strong> {{ $doctor->specialization ?? 'N/A' }}</p>
                <p><strong>Email:</strong> {{ $doctor->email }}</p>
                <p><strong>Số điện thoại:</strong> {{ $doctor->phone }}</p>
                <a href="{{ route('admin.schedules.create', $doctor->id_user) }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Thêm lịch làm việc
                </a>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="alert alert-danger">
    {{ session('error') }}
</div>
@endif

<!-- Week Navigator -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Tuần từ {{ $weekStart->format('d/m/Y') }}</h6>
    </div>
    <div class="card-body">
        <div class="week-navigator">
            <a href="{{ route('admin.schedules.doctor', ['doctorId' => $doctor->id_user, 'week_start' => $prevWeek]) }}" class="btn btn-outline-primary">
                <i class="fas fa-chevron-left"></i> Tuần trước
            </a>
            <a href="{{ route('admin.schedules.doctor', ['doctorId' => $doctor->id_user, 'week_start' => Carbon\Carbon::now()->startOfWeek()->format('Y-m-d')]) }}" class="btn btn-outline-secondary">
                Tuần hiện tại
            </a>
            <a href="{{ route('admin.schedules.doctor', ['doctorId' => $doctor->id_user, 'week_start' => $nextWeek]) }}" class="btn btn-outline-primary">
                Tuần sau <i class="fas fa-chevron-right"></i>
            </a>
        </div>
    </div>
</div>

<!-- Weekly Schedule -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Lịch làm việc trong tuần</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered schedule-table">
                <thead>
                    <tr>
                        <th width="14%">Thứ Hai</th>
                        <th width="14%">Thứ Ba</th>
                        <th width="14%">Thứ Tư</th>
                        <th width="14%">Thứ Năm</th>
                        <th width="14%">Thứ Sáu</th>
                        <th width="14%">Thứ Bảy</th>
                        <th width="14%">Chủ Nhật</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        @foreach($weekDays as $dayIndex => $day)
                        <td>
                            <div class="day-header mb-2">
                                <strong>{{ $day['formatted_date'] }}</strong>
                            </div>
                            
                            @php
                                $daySchedules = $schedules->filter(function($schedule) use ($day) {
                                    return $schedule->date->format('Y-m-d') == $day['date'] || 
                                        ($schedule->repeat_weekly && $schedule->date->format('l') == $day['day_name']);
                                });
                            @endphp
                            
                            @if($daySchedules->count() > 0)
                                @foreach($daySchedules as $schedule)
                                <div class="card schedule-card">
                                    <div class="card-body p-2">
                                        <div class="schedule-time">
                                            {{ Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - 
                                            {{ Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                                        </div>
                                        
                                        @if($schedule->repeat_weekly)
                                        <div class="badge badge-info">Hàng tuần</div>
                                        @endif
                                        
                                        @if(!$schedule->is_available)
                                        <div class="badge badge-danger">Không làm việc</div>
                                        @endif
                                        
                                        @if($schedule->notes)
                                        <div class="small mt-1">{{ $schedule->notes }}</div>
                                        @endif
                                        
                                        <div class="schedule-actions mt-2">
                                            <a href="{{ route('admin.schedules.edit', ['doctorId' => $doctor->id_user, 'id' => $schedule->id]) }}" class="btn btn-sm btn-info mr-1">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.schedules.destroy', ['doctorId' => $doctor->id_user, 'id' => $schedule->id]) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc muốn xóa lịch làm việc này?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            @else
                                <div class="text-muted small">Không có lịch</div>
                            @endif
                        </td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection 