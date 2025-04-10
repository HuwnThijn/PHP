<!DOCTYPE html>

<html lang="en">

{{-- head start --}}
@include('user.partials.head')

<body id="top">

    {{-- header start --}}
    @include('user.partials.header')

    @yield('content')

    {{-- footer start --}}
    @include('user.partials.footer')

    <!-- Essential Scripts -->
    <script src="{{ asset('user/theme/plugins/jquery/jquery.js') }}"></script>
    <script src="{{ asset('user/theme/plugins/bootstrap/bootstrap.min.js') }}"></script>
    <script src="{{ asset('user/theme/plugins/slick-carousel/slick/slick.min.js') }}"></script>
    <script src="{{ asset('user/theme/plugins/shuffle/shuffle.min.js') }}"></script>

    <!-- Google Map -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAkeLMlsiwzp6b3Gnaxd86lvakimwGA6UA"></script>
    <script src="{{ asset('user/theme/plugins/google-map/gmap.js') }}"></script>

    <script src="{{ asset('user/theme/js/script.js') }}"></script>

</body>
</html> 