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
                        <span class="text-white">All Doctors</span>
                        <h1 class="text-capitalize mb-5 text-lg">Specialized doctors</h1>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- doctors section -->
    <section class="section doctors">
        <div class="container">
            {{-- <div class="row justify-content-center mb-4">
                <div class="col-lg-6 text-center">
                    <div class="section-title">
                        <h2>Our Doctors</h2>
                        <div class="divider mx-auto my-4"></div>
                        <p>Our professional doctors are available to provide you with the best healthcare services</p>
                    </div>
                </div>
            </div> --}}

            <!-- Filter buttons -->
            <div class="col-12 text-center mb-5">
                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                    <label class="btn active">
                        <input type="radio" name="shuffle-filter" value="all" checked="checked" />All Doctors
                    </label>
                    
                    <!-- Gender filters -->
                    @foreach($genders as $gender)
                    <label class="btn">
                        <input type="radio" name="shuffle-filter" value="gender-{{ $gender }}" />{{ ucfirst($gender) }}
                    </label>
                    @endforeach
                    
                    <!-- Age range filters -->
                    @foreach($ageRanges as $label => $range)
                    <label class="btn">
                        <input type="radio" name="shuffle-filter" value="age-{{ str_replace('+', 'plus', $label) }}" />{{ $label }}
                    </label>
                    @endforeach
                </div>
            </div>

            <div class="row shuffle-wrapper portfolio-gallery">
                @if(count($doctors) > 0)
                    @foreach($doctors as $doctor)
                        <!-- Define filter groups based on gender and age -->
                        @php
                            $filterGroups = [];
                            
                            // Add gender filter group
                            if($doctor->gender) {
                                $filterGroups[] = 'gender-' . $doctor->gender;
                            }
                            
                            // Add age filter group
                            if($doctor->age) {
                                foreach($ageRanges as $label => $range) {
                                    if($doctor->age >= $range[0] && $doctor->age <= $range[1]) {
                                        $filterGroups[] = 'age-' . str_replace('+', 'plus', $label);
                                        break;
                                    }
                                }
                            }
                            
                            // Convert to JSON for data-groups attribute
                            $filterGroupsJson = json_encode($filterGroups);
                        @endphp
                        
                        <div class="col-lg-3 col-sm-6 col-md-6 mb-4 shuffle-item" data-groups="{{ $filterGroupsJson }}">
                            <div class="position-relative doctor-inner-box">
                                <div class="doctor-profile">
                                    <div class="doctor-img">
                                        @if($doctor->avatar)
                                            <img src="{{ asset('storage/' . $doctor->avatar) }}" alt="{{ $doctor->name }}" class="img-fluid w-100">
                                        @else
                                            <img src="{{ asset('user/theme/images/team/' . ($doctor->gender == 'female' ? '2.jpg' : '1.jpg')) }}" alt="{{ $doctor->name }}" class="img-fluid w-100">
                                        @endif
                                    </div>
                                </div>
                                <div class="content mt-3">
                                    <h4 class="mb-0"><a href="{{ route('user.doctorSingle', $doctor->id_user) }}">{{ $doctor->name }}</a></h4>
                                    <p>{{ $doctor->specialization ?? 'General' }}</p>
                                    <p>
                                        <span class="badge badge-primary">{{ ucfirst($doctor->gender) }}</span>
                                        <span class="badge badge-secondary">{{ $doctor->age }} years</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-12 text-center">
                        <p>No doctors available at the moment.</p>
                    </div>
                @endif
            </div>
        </div>
    </section>
    <!-- /doctors section -->

    <section class="section cta-page">
        <div class="container">
            <div class="row">
                <div class="col-lg-7">
                    <div class="cta-content">
                        <div class="divider mb-4"></div>
                        <h2 class="mb-5 text-lg">We are pleased to offer you the <span class="title-color">chance to
                                have the healthy</span></h2>
                        <a href="{{ route('user.appoinment') }}" class="btn btn-main-2 btn-round-full">Get appointment<i
                                class="icofont-simple-right ml-2"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('user.partials.footer')

    <!-- JavaScript Libraries -->
    <script src="{{ asset('user/theme/plugins/jquery/jquery.js') }}"></script>
    <script src="{{ asset('user/theme/plugins/bootstrap/bootstrap.min.js') }}"></script>
    <script src="{{ asset('user/theme/plugins/slick-carousel/slick/slick.min.js') }}"></script>
    <script src="{{ asset('user/theme/plugins/shuffle/shuffle.min.js') }}"></script>
    
    <!-- Main JavaScript -->
    <script src="{{ asset('user/theme/js/script.js') }}"></script>

</body>

</html>
