@include('header')
@include('blocks/navigation')
@include('blocks/page-title', {
"title": "About Us",
"description": "About Us"
})

<section class="section about-page">
	<div class="container">
		<div class="row">
			<div class="col-lg-4">
				<h2 class="title-color">Personal care for your healthy living</h2>
			</div>
			<div class="col-lg-8">
				<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Incidunt, quod laborum alias. Vitae dolorum, officia sit! Saepe ullam facere at, consequatur incidunt, quae esse, quis ut reprehenderit dignissimos, libero delectus.</p>
				<img src="images/about/sign.png" alt="" class="img-fluid">
			</div>
		</div>
	</div>
</section>

<section class="fetaure-page ">
	<div class="container">
		<div class="row">
			<div class="col-lg-3 col-md-6">
				<div class="about-block-item mb-5 mb-lg-0">
					<img src="images/about/about-1.jpg" alt="" class="img-fluid w-100">
					<h4 class="mt-3">Healthcare for Kids</h4>
					<p>Voluptate aperiam esse possimus maxime repellendus, nihil quod accusantium .</p>
				</div>
			</div>
			<div class="col-lg-3 col-md-6">
				<div class="about-block-item mb-5 mb-lg-0">
					<img src="images/about/about-2.jpg" alt="" class="img-fluid w-100">
					<h4 class="mt-3">Medical Counseling</h4>
					<p>Voluptate aperiam esse possimus maxime repellendus, nihil quod accusantium .</p>
				</div>
			</div>
			<div class="col-lg-3 col-md-6">
				<div class="about-block-item mb-5 mb-lg-0">
					<img src="images/about/about-3.jpg" alt="" class="img-fluid w-100">
					<h4 class="mt-3">Modern Equipments</h4>
					<p>Voluptate aperiam esse possimus maxime repellendus, nihil quod accusantium .</p>
				</div>
			</div>
			<div class="col-lg-3 col-md-6">
				<div class="about-block-item">
					<img src="images/about/about-4.jpg" alt="" class="img-fluid w-100">
					<h4 class="mt-3">Qualified Doctors</h4>
					<p>Voluptate aperiam esse possimus maxime repellendus, nihil quod accusantium .</p>
				</div>
			</div>
		</div>
	</div>
</section>
@include('blocks/award')

@include('blocks/team')

@include('blocks/testimonial')
@include('blocks/footer')
@include('footer')