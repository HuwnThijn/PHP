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
						<span class="text-white">Thông tin bác sĩ</span>
						<h1 class="text-capitalize mb-5 text-lg">{{ $doctor->name }}</h1>
					</div>
				</div>
			</div>
		</div>
	</section>


	<section class="section doctor-single">
		<div class="container">
			<div class="row">
				<div class="col-lg-4 col-md-6">
					<div class="doctor-img-block">
						@if($doctor->avatar)
							<img src="{{ asset('storage/' . $doctor->avatar) }}" alt="{{ $doctor->name }}" class="img-fluid w-100">
						@else
							<img src="{{ asset('user/theme/images/team/' . ($doctor->gender == 'female' ? '2.jpg' : '1.jpg')) }}" alt="{{ $doctor->name }}" class="img-fluid w-100">
						@endif

						<div class="info-block mt-4">
							<h4 class="mb-0">{{ $doctor->name }}</h4>
							<p>{{ $doctor->specialization ?? 'Chuyên khoa tổng quát' }}</p>

							<div class="d-flex mt-3">
								<span class="badge badge-primary mr-2">{{ ucfirst($doctor->gender) }}</span>
								<span class="badge badge-secondary">{{ $doctor->age }} tuổi</span>
							</div>

							<ul class="list-inline mt-4 doctor-social-links">
								<li class="list-inline-item"><a href="#!"><i class="icofont-facebook"></i></a></li>
								<li class="list-inline-item"><a href="#!"><i class="icofont-twitter"></i></a></li>
								<li class="list-inline-item"><a href="#!"><i class="icofont-skype"></i></a></li>
								<li class="list-inline-item"><a href="#!"><i class="icofont-linkedin"></i></a></li>
							</ul>
						</div>
					</div>
				</div>

				<div class="col-lg-8 col-md-6">
					<div class="doctor-details mt-4 mt-lg-0">
						<h2 class="text-md">Giới thiệu</h2>
						<div class="divider my-4"></div>
						<p>
							{{ $doctor->description ?? 'Bác sĩ '.$doctor->name.' là một chuyên gia y tế có kinh nghiệm và tận tâm trong việc chăm sóc sức khỏe của bệnh nhân. Với nhiều năm kinh nghiệm trong ngành y tế, bác sĩ luôn nỗ lực cung cấp dịch vụ chăm sóc y tế chất lượng cao và đáng tin cậy.' }}
						</p>
						
						@if($doctor->experience)
						<p>Kinh nghiệm: {{ $doctor->experience }} năm trong lĩnh vực y tế.</p>
						@endif

						<a href="{{ route('user.appoinment') }}" class="btn btn-main-2 btn-round-full mt-3">Đặt lịch hẹn<i class="icofont-simple-right ml-2"></i></a>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="section doctor-qualification gray-bg">
		<div class="container">
			<div class="row">
				<div class="col-lg-6">
					<div class="section-title">
						<h3>Trình độ học vấn & Chứng chỉ</h3>
						<div class="divider my-4"></div>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-6 mb-4 mb-lg-0">
					<div class="edu-block mb-5">
						<span class="h6 text-muted">{{ $doctor->education_year1 ?? '2010-2015' }}</span>
						<h4 class="mb-3 title-color">{{ $doctor->education1 ?? 'Bác sĩ Y khoa - Đại học Y Hà Nội' }}</h4>
						<p>{{ $doctor->education_desc1 ?? 'Tốt nghiệp loại giỏi chuyên ngành Y đa khoa, chuyên sâu về chẩn đoán và điều trị các bệnh lý.' }}</p>
					</div>

					<div class="edu-block">
						<span class="h6 text-muted">{{ $doctor->education_year2 ?? '2015-2017' }}</span>
						<h4 class="mb-3 title-color">{{ $doctor->education2 ?? 'Thạc sĩ Y học - Đại học Y Dược TP.HCM' }}</h4>
						<p>{{ $doctor->education_desc2 ?? 'Hoàn thành chương trình Thạc sĩ Y học với chuyên ngành chuyên sâu, tham gia nghiên cứu y khoa.' }}</p>
					</div>
				</div>

				<div class="col-lg-6">
					<div class="edu-block mb-5">
						<span class="h6 text-muted">{{ $doctor->certification_year1 ?? '2017-2018' }}</span>
						<h4 class="mb-3 title-color">{{ $doctor->certification1 ?? 'Chứng chỉ Chuyên khoa' }}</h4>
						<p>{{ $doctor->certification_desc1 ?? 'Được cấp chứng chỉ hành nghề chuyên khoa sau khi hoàn thành các khóa đào tạo chuyên sâu.' }}</p>
					</div>

					<div class="edu-block">
						<span class="h6 text-muted">{{ $doctor->certification_year2 ?? '2018-2020' }}</span>
						<h4 class="mb-3 title-color">{{ $doctor->certification2 ?? 'Đào tạo nâng cao' }}</h4>
						<p>{{ $doctor->certification_desc2 ?? 'Tham gia các khóa đào tạo nâng cao tại các bệnh viện lớn trong nước và quốc tế.' }}</p>
					</div>
				</div>
			</div>
		</div>
	</section>


	<section class="section doctor-skills">
		<div class="container">
			<div class="row">
				<div class="col-lg-4">
					<h3>Kỹ năng chuyên môn</h3>
					<div class="divider my-4"></div>
					<p>{{ $doctor->skills_desc ?? 'Bác sĩ '.$doctor->name.' có nhiều kỹ năng chuyên môn xuất sắc trong lĩnh vực y tế, với khả năng chẩn đoán chính xác và đưa ra phương pháp điều trị hiệu quả. Luôn cập nhật các phương pháp và công nghệ mới nhất trong y học.' }}</p>
				</div>
				<div class="col-lg-4">
					<div class="skill-list">
						<h5 class="mb-4">Lĩnh vực chuyên môn</h5>
						<ul class="list-unstyled department-service">
							<li><i class="icofont-check mr-2"></i>{{ $doctor->expertise1 ?? 'Chẩn đoán và điều trị bệnh' }}</li>
							<li><i class="icofont-check mr-2"></i>{{ $doctor->expertise2 ?? 'Tư vấn sức khỏe toàn diện' }}</li>
							<li><i class="icofont-check mr-2"></i>{{ $doctor->expertise3 ?? 'Theo dõi và quản lý bệnh mãn tính' }}</li>
							<li><i class="icofont-check mr-2"></i>{{ $doctor->expertise4 ?? 'Chăm sóc sức khỏe dự phòng' }}</li>
							<li><i class="icofont-check mr-2"></i>{{ $doctor->expertise5 ?? 'Thăm khám định kỳ' }}</li>
							<li><i class="icofont-check mr-2"></i>{{ $doctor->expertise6 ?? 'Tư vấn dinh dưỡng và lối sống lành mạnh' }}</li>
						</ul>
					</div>
				</div>
				<div class="col-lg-4">
					<div class="sidebar-widget gray-bg p-4">
						<h5 class="mb-4">Đặt lịch hẹn</h5>

						<ul class="list-unstyled lh-35">
							<li class="d-flex justify-content-between align-items-center">
								<span>Thứ Hai - Thứ Sáu</span>
								<span>8:00 - 17:00</span>
							</li>
							<li class="d-flex justify-content-between align-items-center">
								<span>Thứ Bảy</span>
								<span>9:00 - 16:00</span>
							</li>
							<li class="d-flex justify-content-between align-items-center">
								<span>Chủ Nhật</span>
								<span>Nghỉ</span>
							</li>
						</ul>

						<div class="sidebar-contatct-info mt-4">
							<p class="mb-0">Cần trợ giúp khẩn cấp?</p>
							<h3 class="text-color-2">{{ $doctor->phone ?? '0123 456 789' }}</h3>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- footer Start -->
	@include('user.partials.footer')

</body>

</html>