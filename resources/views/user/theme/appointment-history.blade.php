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
						<span class="text-white">My Account</span>
						<h1 class="text-capitalize mb-5 text-lg">Examination History</h1>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-lg-10">
					<div class="card shadow">
						<div class="card-body">
							<h4 class="mb-4">Lịch sử đặt lịch khám</h4>
							
							@if($appointments->isEmpty())
								<div class="alert alert-info">
									Bạn chưa có lịch hẹn khám nào. <a href="{{ route('user.appointment') }}">Đặt lịch ngay</a>
								</div>
							@else
								<div class="table-responsive">
									<table class="table table-hover">
										<thead class="thead-light">
											<tr>
												<th>Mã lịch hẹn</th>
												<th>Bác sĩ</th>
												<th>Dịch vụ</th>
												<th>Thời gian</th>
												<th>Trạng thái</th>
											</tr>
										</thead>
										<tbody>
											@foreach($appointments as $appointment)
												<tr>
													<td>#{{ $appointment->id_appointment }}</td>
													<td>{{ $appointment->doctor->name }}</td>
													<td>{{ $appointment->service->name }}</td>
													<td>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i - d/m/Y') }}</td>
													<td>
														@php
															$status = '';
															$badge = '';
															
															switch($appointment->status) {
																case 'scheduled':
																	$status = 'Đã đặt lịch';
																	$badge = 'badge-info';
																	break;
																case 'completed':
																	$status = 'Đã hoàn thành';
																	$badge = 'badge-success';
																	break;
																case 'cancelled':
																	$status = 'Đã hủy';
																	$badge = 'badge-danger';
																	break;
																case 'no-show':
																	$status = 'Không đến';
																	$badge = 'badge-warning';
																	break;
																default:
																	$status = $appointment->status;
																	$badge = 'badge-secondary';
															}
														@endphp
														<span class="badge {{ $badge }}">{{ $status }}</span>
													</td>
												</tr>
											@endforeach
										</tbody>
									</table>
									
									<div class="d-flex justify-content-center mt-4">
										{{ $appointments->links() }}
									</div>
								</div>
							@endif
							
							<div class="text-center mt-4">
								<a href="{{ route('user.appointment') }}" class="btn btn-main btn-round-full">ĐẶT LỊCH MỚI</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	@include('user.partials.footer')

</body>

</html> 