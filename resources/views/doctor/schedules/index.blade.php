@extends('layouts.doctor')

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

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Ngày</th>
                            <th>Thời gian bắt đầu</th>
                            <th>Thời gian kết thúc</th>
                            <th>Trạng thái</th>
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
                                        <span class="badge badge-success">Có thể khám</span>
                                    @else
                                        <span class="badge badge-danger">Đã đặt</span>
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
        </div>
    </div>
</div>
@endsection 