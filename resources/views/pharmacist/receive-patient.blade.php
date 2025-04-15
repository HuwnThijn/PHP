@extends('layouts.pharmacist')

@section('title', 'Tiếp nhận bệnh nhân')

@section('page-title', 'Tiếp nhận bệnh nhân')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Lịch hẹn hôm nay</h6>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Thời gian</th>
                                <th>Bệnh nhân</th>
                                <th>Bác sĩ</th>
                                <th>Dịch vụ</th>
                                <th>Ghi chú</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($todayAppointments as $appointment)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') }}</td>
                                <td>
                                    @if($appointment->isGuest())
                                        {{ $appointment->guest_name }} <span class="badge bg-secondary">Khách</span><br>
                                        <small>{{ $appointment->guest_phone }}</small>
                                    @else
                                        {{ $appointment->patient->name }}<br>
                                        <small>{{ $appointment->patient->phone }}</small>
                                    @endif
                                </td>
                                <td>{{ $appointment->doctor->name }}</td>
                                <td>{{ $appointment->service->name ?? 'Không có' }}</td>
                                <td>{{ $appointment->notes }}</td>
                                <td>
                                    <button class="btn btn-sm btn-primary receive-patient" 
                                            data-appointment-id="{{ $appointment->id_appointment }}"
                                            data-patient-id="{{ $appointment->id_patient }}"
                                            data-patient-name="{{ $appointment->isGuest() ? $appointment->guest_name : $appointment->patient->name }}"
                                            data-doctor-id="{{ $appointment->id_doctor }}"
                                            data-doctor-name="{{ $appointment->doctor->name }}"
                                            data-notes="{{ $appointment->notes }}">
                                        <i class="fas fa-user-check"></i> Tiếp nhận
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">Không có lịch hẹn nào hôm nay</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Tiếp nhận bệnh nhân thủ công</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('pharmacist.patients.process') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Bệnh nhân <span class="text-danger">*</span></label>
                        <select class="form-select" name="patient_id" required>
                            <option value="">-- Chọn bệnh nhân --</option>
                            @foreach(App\Models\User::where('id_role', 4)->orderBy('name')->get() as $patient)
                                <option value="{{ $patient->id_user }}">{{ $patient->name }} - {{ $patient->phone }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Bác sĩ <span class="text-danger">*</span></label>
                        <select class="form-select" name="doctor_id" required>
                            <option value="">-- Chọn bác sĩ --</option>
                            @if($availableDoctors->count() > 0)
                                @foreach($availableDoctors as $doctor)
                                    <option value="{{ $doctor->id_user }}">{{ $doctor->name }}</option>
                                @endforeach
                            @else
                                <option value="" disabled>Không có bác sĩ nào có lịch làm việc hôm nay</option>
                            @endif
                        </select>
                        <small class="form-text text-muted">Chỉ hiển thị bác sĩ có lịch làm việc hôm nay</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ghi chú</label>
                        <textarea class="form-control" name="notes" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary" {{ $availableDoctors->count() == 0 ? 'disabled' : '' }}>
                        <i class="fas fa-user-plus"></i> Tiếp nhận bệnh nhân
                    </button>
                    @if($availableDoctors->count() == 0)
                        <div class="alert alert-warning mt-3">
                            <i class="fas fa-exclamation-triangle"></i> Không thể tiếp nhận bệnh nhân vì không có bác sĩ nào có lịch làm việc hôm nay.
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal tiếp nhận bệnh nhân từ lịch hẹn -->
<div class="modal fade" id="receivePatientModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tiếp nhận bệnh nhân</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('pharmacist.patients.process') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="appointment_id" id="appointment_id">
                    
                    <div class="mb-3">
                        <label class="form-label">Bệnh nhân</label>
                        <select class="form-select" name="patient_id" id="patient_id" required>
                            <option value="">-- Chọn bệnh nhân --</option>
                            @foreach(App\Models\User::where('id_role', 4)->orderBy('name')->get() as $patient)
                                <option value="{{ $patient->id_user }}">{{ $patient->name }} - {{ $patient->phone }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted">Nếu là khách vãng lai, vui lòng tạo tài khoản trước.</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Bác sĩ</label>
                        <select class="form-select" name="doctor_id" id="doctor_id" required>
                            <option value="">-- Chọn bác sĩ --</option>
                            @if($availableDoctors->count() > 0)
                                @foreach($availableDoctors as $doctor)
                                    <option value="{{ $doctor->id_user }}">{{ $doctor->name }}</option>
                                @endforeach
                            @else
                                <option value="" disabled>Không có bác sĩ nào có lịch làm việc hôm nay</option>
                            @endif
                        </select>
                        <small class="form-text text-muted">Chỉ hiển thị bác sĩ có lịch làm việc hôm nay</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Ghi chú</label>
                        <textarea class="form-control" name="notes" id="notes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary" {{ $availableDoctors->count() == 0 ? 'disabled' : '' }}>
                        <i class="fas fa-user-check"></i> Tiếp nhận
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Xử lý sự kiện khi nhấn nút tiếp nhận bệnh nhân
        $('.receive-patient').on('click', function() {
            const appointmentId = $(this).data('appointment-id');
            const patientId = $(this).data('patient-id');
            const doctorId = $(this).data('doctor-id');
            const notes = $(this).data('notes');
            
            $('#appointment_id').val(appointmentId);
            $('#patient_id').val(patientId);
            
            // Kiểm tra xem bác sĩ có lịch làm việc hôm nay không
            const doctorSelect = $('#doctor_id');
            let doctorAvailable = false;
            
            doctorSelect.find('option').each(function() {
                if ($(this).val() == doctorId) {
                    doctorAvailable = true;
                    return false; // break
                }
            });
            
            if (doctorAvailable) {
                doctorSelect.val(doctorId);
            } else {
                // Hiển thị thông báo nếu bác sĩ không có lịch làm việc hôm nay
                alert('Bác sĩ này không có lịch làm việc hôm nay. Vui lòng chọn bác sĩ khác.');
            }
            
            $('#notes').val(notes);
            $('#receivePatientModal').modal('show');
        });
    });
</script>
@endsection 