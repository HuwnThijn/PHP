{{-- head start --}}
<head>

    <!-- Basic Page Needs
  ================================================== -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Responsive Bootstrap 4 Medical Template">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>O2Skin - Web y tế số 1 VN</title>

    <!-- Mobile Specific Metas
  ================================================== -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="generator" content="Themefisher Novena HTML Template v1.0">

    <!-- theme meta -->
    <meta name="theme-name" content="novena" />

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('user/theme/images/favicon.png') }}" />


    <!--
  Essential stylesheets
  =====================================-->
    <link rel="stylesheet" href="{{ asset('user/theme/plugins/bootstrap/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('user/theme/plugins/icofont/icofont.min.css') }}">
    <link rel="stylesheet" href="{{ asset('user/theme/plugins/slick-carousel/slick/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('user/theme/plugins/slick-carousel/slick/slick-theme.css') }}">

    <!-- Main Stylesheet -->
    <link rel="stylesheet" href="{{ asset('user/theme/css/style.css') }}">

    <!-- Custom CSS for RTL support for Arabic language -->
    @if(app()->getLocale() == 'ar')
    <link rel="stylesheet" href="{{ asset('user/theme/css/rtl.css') }}">
    @endif

    @yield('styles')
</head>