@extends('doctor.layouts.app')

@section('title', 'Quản lý lịch làm việc')

@section('page-title', 'Quản lý lịch làm việc')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Quản lý lịch làm việc</h1>
        <a href="{{ route('doctor.schedules.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Thêm lịch làm việc
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    @php
        // Khởi tạo biến schedules nếu chưa có
        if (!isset($schedules)) {
            $schedules = collect([]);
        }
        
        // Tạo mảng weekDays nếu chưa có
        if (!isset($weekDays)) {
            $currentWeekStart = \Carbon\Carbon::now()->startOfWeek();
            $weekDays = [];
            for ($i = 0; $i < 7; $i++) {
                $day = (clone $currentWeekStart)->addDays($i);
                $weekDays[$i] = [
                    'date' => $day->format('Y-m-d'),
                    'day_name' => $day->format('l'),
                    'formatted_date' => $day->format('d/m/Y')
                ];
            }
        }
    @endphp
    
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Lịch làm việc theo tuần</h6>
            <div class="btn-group">
                @if(isset($prevWeek))
                <a href="{{ route('doctor.schedules.week', ['week_start' => $prevWeek]) }}" class="btn btn-outline-secondary">
                    <i class="fas fa-chevron-left"></i> Tuần trước
                </a>
                @endif
                
                <button type="button" class="btn btn-outline-primary" id="currentWeek">
                    {{ isset($weekStart) ? $weekStart->format('d/m/Y') : \Carbon\Carbon::now()->startOfWeek()->format('d/m/Y') }} - 
                    {{ isset($weekStart) ? (clone $weekStart)->addDays(6)->format('d/m/Y') : \Carbon\Carbon::now()->endOfWeek()->format('d/m/Y') }}
                </button>
                
                @if(isset($nextWeek))
                <a href="{{ route('doctor.schedules.week', ['week_start' => $nextWeek]) }}" class="btn btn-outline-secondary">
                    Tuần sau <i class="fas fa-chevron-right"></i>
                </a>
                @endif
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
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
                        <tr style="height: 150px;">
                            @foreach($weekDays as $day)
                            <td class="align-top">
                                <div class="d-flex justify-content-between mb-2">
                                    <strong>{{ $day['formatted_date'] }}</strong>
                                    <a href="{{ route('doctor.schedules.create', ['date' => $day['date']]) }}" class="text-primary">
                                        <i class="fas fa-plus-circle"></i>
                                    </a>
                                </div>
                                
                                @foreach($schedules as $schedule)
                                    @if($schedule->date->format('Y-m-d') == $day['date'])
                                    <div class="p-1 mb-1 rounded {{ $schedule->is_available ? 'bg-success' : 'bg-danger' }} text-white">
                                        <small>
                                            {{ $schedule->start_time->format('H:i') }} - {{ $schedule->end_time->format('H:i') }}
                                            <div class="mt-1 d-flex justify-content-between">
                                                <span>{{ $schedule->is_available ? 'Còn trống' : 'Đã đặt' }}</span>
                                                <div>
                                                    <a href="{{ route('doctor.schedules.edit', $schedule) }}" class="text-white">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('doctor.schedules.destroy', $schedule) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-link btn-sm p-0 text-white" onclick="return confirm('Bạn có chắc chắn muốn xóa lịch này?')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </small>
                                    </div>
                                    @endif
                                @endforeach
                            </td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Danh sách lịch làm việc</h6>
        </div>
        <div class="card-body">
            @if($schedules->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Ngày</th>
                            <th>Thời gian bắt đầu</th>
                            <th>Thời gian kết thúc</th>
                            <th>Trạng thái</th>
                            <th>Lặp lại hàng tuần</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($schedules as $schedule)
                            <tr>
                                <td>{{ $schedule->date->format('d/m/Y') }}</td>
                                <td>{{ $schedule->start_time->format('H:i') }}</td>
                                <td>{{ $schedule->end_time->format('H:i') }}</td>
                                <td>
                                    @if($schedule->is_available)
                                        <span class="badge bg-success">Còn trống</span>
                                    @else
                                        <span class="badge bg-danger">Đã đặt</span>
                                    @endif
                                </td>
                                <td>
                                    @if($schedule->repeat_weekly)
                                        <span class="badge bg-info">Lặp lại</span>
                                    @else
                                        <span class="badge bg-secondary">Không lặp lại</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('doctor.schedules.edit', $schedule) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('doctor.schedules.destroy', $schedule) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa lịch này?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="alert alert-info">
                Bạn chưa có lịch làm việc nào. <a href="{{ route('doctor.schedules.create') }}" class="alert-link">Thêm lịch mới</a> ngay!
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .badge {
        font-size: 85%;
    }
    
    .btn-group {
        display: flex;
        gap: 5px;
    }
</style>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Kích hoạt tooltip cho các nút
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
@endsection 