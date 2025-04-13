@extends('admin.layouts.app')

@section('title', 'Quản lý lịch làm việc bác sĩ')

@section('page-title', 'Quản lý lịch làm việc bác sĩ')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Danh sách bác sĩ</h6>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Hình ảnh</th>
                        <th>Tên bác sĩ</th>
                        <th>Chuyên khoa</th>
                        <th>Email</th>
                        <th>Số điện thoại</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($doctors as $doctor)
                    <tr>
                        <td>{{ $doctor->id_user }}</td>
                        <td>
                            @if($doctor->avatar)
                                <img src="{{ asset('storage/' . $doctor->avatar) }}" alt="{{ $doctor->name }}" width="50">
                            @else
                                <img src="{{ asset('img/default-avatar.png') }}" alt="No avatar" width="50">
                            @endif
                        </td>
                        <td>{{ $doctor->name }}</td>
                        <td>{{ $doctor->specialization ?? 'N/A' }}</td>
                        <td>{{ $doctor->email }}</td>
                        <td>{{ $doctor->phone }}</td>
                        <td>
                            <a href="{{ route('admin.schedules.doctor', $doctor->id_user) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-calendar-alt"></i> Xem lịch làm việc
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable();
    });
</script>
@endsection 