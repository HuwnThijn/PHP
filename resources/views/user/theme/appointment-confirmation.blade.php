<!DOCTYPE html>

<html lang="en">

@include('user.partials.head')

<body id="top">

	@include('user.partials.header')

	<section class="section confirmation">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-lg-8">
					<div class="confirmation-content text-center">
						<i class="icofont-check-circled text-lg text-color-2"></i>
						<h2 class="mt-3 mb-4">Cảm ơn bạn đã đặt lịch khám</h2>
						<p>Chúng tôi đã nhận được thông tin đặt lịch của bạn và sẽ liên hệ với bạn sớm nhất có thể.</p>
					</div>
					
					<div class="appointment-details mt-5">
						<h3 class="mb-4 text-center">Thông tin lịch hẹn</h3>
						<div class="card">
							<div class="card-body">
								<div class="row">
									<div class="col-md-6">
										<p><strong>Họ tên:</strong> {{ $appointment->isGuest() ? $appointment->guest_name : $appointment->patient->name }}</p>
										<p><strong>Email:</strong> {{ $appointment->isGuest() ? $appointment->guest_email : $appointment->patient->email }}</p>
										<p><strong>Số điện thoại:</strong> {{ $appointment->isGuest() ? $appointment->guest_phone : $appointment->patient->phone }}</p>
									</div>
									<div class="col-md-6">
										<p><strong>Bác sĩ:</strong> {{ $appointment->doctor->name }}</p>
										<p><strong>Dịch vụ:</strong> {{ $appointment->service->name }}</p>
										<p><strong>Thời gian:</strong> {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i - d/m/Y') }}</p>
									</div>
								</div>
								<div class="row mt-3">
									<div class="col-12">
										<p><strong>Ghi chú:</strong> {{ $appointment->notes ?? 'Không có' }}</p>
									</div>
								</div>
							</div>
						</div>
					</div>
					
					<div class="text-center mt-5">
						@if(Auth::check())
							<a href="{{ route('user.appointment.history') }}" class="btn btn-main-2 btn-round-full">Xem lịch sử đặt lịch</a>
						@else
							<p class="mb-3">Bạn có thể đăng nhập để theo dõi lịch sử đặt lịch của mình.</p>
							<a href="{{ route('user.login') }}" class="btn btn-main-2 btn-round-full">Đăng nhập</a>
						@endif
						<a href="{{ route('index') }}" class="btn btn-main btn-round-full ml-2">Về trang chủ</a>
					</div>
				</div>
			</div>
		</div>
	</section>

	@include('user.partials.footer')

</body>

</html> 