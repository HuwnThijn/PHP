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
                        <span class="text-white">Contact Us</span>
                        <h1 class="text-capitalize mb-5 text-lg">Get in Touch</h1>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section contact-info pb-0">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="contact-block mb-4 mb-lg-0">
                        <i class="icofont-live-support"></i>
                        <h5>Call Us</h5>
                        +823-4565-13456
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="contact-block mb-4 mb-lg-0">
                        <i class="icofont-support-faq"></i>
                        <h5>Email Us</h5>
                        o2skin@gmail.com
                    </div>
                </div>
                <div class="col-lg-4 col-md-12">
                    <div class="contact-block mb-4 mb-lg-0">
                        <i class="icofont-location-pin"></i>
                        <h5>Location</h5>
                        Số 16, Tp. Thủ Đức - Tp. Hồ Chí Minh
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="contact-form-wrap section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="section-title text-center">
                        <h2 class="text-md mb-2">Contact us</h2>
                        <div class="divider mx-auto my-4"></div>
                        <p class="mb-5">Welcome to our website 📞 You can contact us anytime 😎</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <form id="contact-form" class="contact__form" method="post" action="mail.php">
                        <!-- form message -->
                        <div class="row">
                            <div class="col-12">
                                <div class="alert alert-success contact__msg" style="display: none" role="alert">
                                    Your message was sent successfully.
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <input name="name" id="name" type="text" class="form-control"
                                        placeholder="Your Full Name">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <input name="email" id="email" type="email" class="form-control"
                                        placeholder="Your Email Address" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <input name="subject" id="subject" type="text" class="form-control"
                                        placeholder="Your Query Topic" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <input name="phone" id="phone" type="text" class="form-control"
                                        placeholder="Your Phone Number" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group-2 mb-4">
                            <textarea name="message" id="message" class="form-control" rows="8" placeholder="Your Message" required></textarea>
                        </div>

                        <div class="text-center">
                            <input class="btn btn-main btn-round-full" name="submit" type="submit"
                                value="Send Message">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Map Section -->
    <section class="map-section">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-12 px-0">
                    <div id="map-container">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3022.472979439986!2d-73.97769068459431!3d40.7516207793276!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c2590247c56379%3A0x15e13bea38b43e18!2sChrysler%20Building!5e0!3m2!1sen!2sus!4v1586810387289!5m2!1sen!2sus" 
                            width="100%" 
                            height="450" 
                            style="border:0;" 
                            allowfullscreen="" 
                            loading="lazy">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- footer Start -->
    @include('user.partials.footer')

    <!-- Essential Scripts -->
    <script src="{{ asset('user/theme/plugins/jquery/jquery.js') }}"></script>
    <script src="{{ asset('user/theme/plugins/bootstrap/bootstrap.min.js') }}"></script>
    <script src="{{ asset('user/theme/plugins/slick-carousel/slick/slick.min.js') }}"></script>
    <script src="{{ asset('user/theme/plugins/shuffle/shuffle.min.js') }}"></script>
    <script src="{{ asset('user/theme/js/script.js') }}"></script>

</body>
</html> 