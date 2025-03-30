<!DOCTYPE html>

<html lang="en">

@include('user.partials.head')

<body id="top">

	@include('user.partials.header')

	<section class="page-title bg-1">
		<div class="overlay"></div>
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="block text-center">
						<span class="text-white">Đặt lịch khám</span>
						<h1 class="text-capitalize mb-5 text-lg">Đặt lịch hẹn</h1>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="section appoinment">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 ">
                    <div class="appoinment-content">
                        <img src="{{ asset('user/theme/images/about/img-3.jpg') }}" alt=""
                            class="img-fluid">
                        <div class="emergency">
                            <h2 class="text-lg"><i class="icofont-phone-circle text-lg"></i>Liên hệ khẩn cấp</h2>
                            <h3 class="text-md" style="color: orange;">+84 123 456 789</h3>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-10 ">
                    <div class="appoinment-wrap mt-5 mt-lg-0">
                        <h2 class="mb-2 title-color">Đặt lịch khám</h2>
                        <p class="mb-4">Vui lòng điền đầy đủ thông tin để chúng tôi có thể sắp xếp lịch hẹn phù hợp cho bạn.</p>
                        <form id="appointmentForm" class="appoinment-form" method="post" action="{{ route('user.appointment.store') }}">
                            @csrf
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <select class="form-control select-service" id="service" name="id_service" required>
                                            <option value="">-- Chọn dịch vụ --</option>
                                            @foreach($services as $service)
                                                <option value="{{ $service->id_service }}">{{ $service->name }} ({{ number_format($service->price) }} VNĐ)</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <select class="form-control select-doctor" id="doctor" name="id_doctor" required>
                                            <option value="">-- Chọn bác sĩ --</option>
                                            @foreach($doctors as $doctor)
                                                <option value="{{ $doctor->id_user }}">{{ $doctor->name }} ({{ $doctor->specialization ?? 'Đa khoa' }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <input name="appointment_date" id="date" type="date" class="form-control"
                                            placeholder="Ngày khám" required min="{{ date('Y-m-d') }}">
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <input name="appointment_time" id="time" type="time" class="form-control"
                                            placeholder="Giờ khám" required>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        @if(Auth::check())
                                            <input name="name" id="name" type="text" class="form-control"
                                                placeholder="Họ và tên" required value="{{ Auth::user()->name }}" readonly>
                                        @else
                                            <input name="name" id="name" type="text" class="form-control"
                                                placeholder="Họ và tên" required>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        @if(Auth::check())
                                            @if(!empty(Auth::user()->phone))
                                                <input name="phone" id="phone" type="text" class="form-control"
                                                    placeholder="Số điện thoại" required value="{{ Auth::user()->phone }}" readonly>
                                            @else
                                                <input name="phone" id="phone" type="text" class="form-control"
                                                    placeholder="Vui lòng nhập số điện thoại" required>
                                            @endif
                                        @else
                                            <input name="phone" id="phone" type="text" class="form-control"
                                                placeholder="Số điện thoại" required>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        @if(Auth::check())
                                            <input name="email" id="email" type="email" class="form-control"
                                                placeholder="Email" required value="{{ Auth::user()->email }}" readonly>
                                        @else
                                            <input name="email" id="email" type="email" class="form-control"
                                                placeholder="Email" required>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="form-group-2 mb-4">
                                <textarea name="notes" id="notes" class="form-control" rows="6" placeholder="Mô tả triệu chứng hoặc yêu cầu của bạn"></textarea>
                            </div>

							<button type="submit" class="btn btn-main btn-round-full">Đặt lịch khám <i class="icofont-simple-right ml-2"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

	<!-- footer Start -->
    @include('user.partials.footer')

    <!-- JavaScript -->
    <script src="{{ asset('user/theme/plugins/jquery/jquery.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Submit form handler
            $('#appointmentForm').on('submit', function(e) {
                e.preventDefault();
                
                // Validate form
                if (!this.checkValidity()) {
                    e.stopPropagation();
                    $(this).addClass('was-validated');
                    return;
                }
                
                // Submit form
                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            window.location.href = "{{ route('user.appointment.confirmation') }}";
                        } else {
                            alert(response.message || 'Có lỗi xảy ra. Vui lòng thử lại sau.');
                        }
                    },
                    error: function(xhr) {
                        let errors = xhr.responseJSON?.errors || {};
                        let errorMessage = 'Vui lòng kiểm tra lại thông tin:';
                        
                        for (let field in errors) {
                            errorMessage += '\n- ' + errors[field][0];
                        }
                        
                        alert(errorMessage);
                    }
                });
            });
        });
    </script>
</body>

</html>