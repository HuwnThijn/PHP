<!-- header Start -->
<header>
    <div class="header-top-bar">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <ul class="top-bar-info list-inline-item pl-0 mb-0">
                        <li class="list-inline-item"><a href="mailto:o2skin@gmail.com"><i
                                    class="icofont-support-faq mr-2"></i>o2skin@gmail.com</a></li>
                        <li class="list-inline-item"><i class="icofont-location-pin mr-2"></i>{{ __('menu.address') }}: 10/80c, Thủ Đức, Hồ Chí Minh</li>
                    </ul>
                </div>
                <div class="col-lg-6">
                    <div class="text-lg-right top-right-bar mt-2 mt-lg-0 d-flex justify-content-end align-items-center">
                        <!-- Language Switcher -->
                        <div class="language-switch-wrapper mr-3">
                            <div class="custom-dropdown">
                                <button id="language-toggle-btn" class="btn btn-sm btn-outline-light">
                                    <i class="icofont-globe mr-1"></i> {{ __('menu.language') }}
                                </button>
                                <div id="language-dropdown" class="custom-dropdown-menu">
                                    <div class="dropdown-header">{{ __('menu.select_language') }}</div>
                                    <a class="custom-dropdown-item {{ App::getLocale() == 'en' ? 'active' : '' }}" href="{{ route('language.change', 'en') }}">
                                        <i class="icofont-flag mr-2 text-primary"></i> {{ __('menu.english') }}
                                    </a>
                                    <a class="custom-dropdown-item {{ App::getLocale() == 'vi' ? 'active' : '' }}" href="{{ route('language.change', 'vi') }}">
                                        <i class="icofont-flag mr-2 text-danger"></i> {{ __('menu.vietnamese') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Theme Toggle -->
                        <div class="theme-switch-wrapper mr-3">
                            <button id="theme-toggle" class="btn btn-sm">
                                <i class="icofont-sun-alt" id="theme-icon"></i>
                            </button>
                        </div>

                        {{-- <a href="tel:+23-345-67890">
                            <span>{{ __('menu.call_now') }}: </span>
                            <span class="h4">823-4565-13456</span>
                        </a> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <nav class="navbar navbar-expand-lg navigation" id="navbar">
        <div class="container">
            <a class="navbar-brand" href="{{ route('index') }}">
                <img src="{{ asset('user/theme/images/logo.png') }}" alt="" class="img-fluid">
            </a>

            <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarmain"
                aria-controls="navbarmain" aria-expanded="false" aria-label="Toggle navigation">
                <span class="icofont-navigation-menu"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarmain">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item active"><a class="nav-link" href="{{ route('index') }}">{{ __('menu.home') }}</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('about') }}">{{ __('menu.about') }}</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('service') }}">{{ __('menu.services') }}</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('doctor') }}">{{ __('menu.doctor') }}</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('store') }}">{{ __('menu.store') }}</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('appoinment') }}">{{ __('menu.appointment') }}</a></li>
                    
                    {{-- <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="{{ route('doctor',['doctor-1']) }}" id="dropdown03"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Doctors <i
                                class="icofont-thin-down"></i></a>
                        <ul class="dropdown-menu" aria-labelledby="dropdown03">
                            <li><a class="dropdown-item" href="{{ route('doctor', ['doctor-1']) }}">Doctors</a>
                            </li>
                            <li><a class="dropdown-item" href="{{ route('doctor-single') }}">Doctor Single</a></li>
                           
                        </ul>
                    </li> --}}

                    <li class="nav-item"><a class="nav-link" href="{{ route('contact') }}">{{ __('menu.contact') }}</a></li>

                    @guest
                    <li class="nav-item"><a class="nav-link" href="{{ route('user.login') }}">{{ __('menu.login') }}</a></li>
                    @else
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="dropdown-user"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="icofont-user-alt-3 mr-1"></i> {{ Auth::user()->name }} <i class="icofont-thin-down"></i>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dropdown-user">
                            <li><a class="dropdown-item" href="{{ route('user.profile') }}"><i class="icofont-user mr-2"></i>{{ __('menu.my_profile') }}</a></li>
                            
                            <li><a class="dropdown-item" href="{{ route('user.cart') }}"><i class="icofont-shopping-cart mr-2"></i>{{ __('menu.my_cart') }}</a></li>
                            <li><a class="dropdown-item" href="{{ route('user.orders') }}"><i class="icofont-file-document mr-2"></i>{{ __('menu.order_history') }}</a></li>
                            <li><a class="dropdown-item" href="{{ route('user.appointment.history') }}"><i class="icofont-history mr-2"></i>{{ __('menu.examination_history') }}</a></li>
                            <li>
                                <a class="dropdown-item" href="{{ route('user.logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="icofont-logout mr-2"></i>{{ __('menu.logout') }}
                                </a>
                            </li>
                        </ul>
                        <form id="logout-form" action="{{ route('user.logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>
</header>

<!-- Add Required Libraries for Bootstrap Dropdowns -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.min.js"></script>

<!-- Theme and Language Styles -->
<style>
    /* Theme Switch Styles */
    .theme-switch-wrapper {
        display: inline-block;
    }

    #theme-toggle {
        background: transparent;
        border: none;
        color: #fff;
        cursor: pointer;
        padding: 0;
    }

    #theme-toggle:focus {
        outline: none;
    }

    #theme-icon {
        font-size: 1.2rem;
    }
    
    /* Custom Language Dropdown Styles */
    .language-switch-wrapper {
        position: relative;
    }
    
    .custom-dropdown {
        position: relative;
        display: inline-block;
    }
    
    #language-toggle-btn {
        background: rgba(255, 255, 255, 0.15);
        border-color: rgba(255, 255, 255, 0.3);
        color: #fff;
        font-weight: 500;
        padding: 6px 15px;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    #language-toggle-btn:hover {
        background: rgba(255, 255, 255, 0.25);
        border-color: rgba(255, 255, 255, 0.5);
    }
    
    .custom-dropdown-menu {
        min-width: 180px;
        position: absolute;
        background-color: #fff;
        border-radius: 8px;
        margin-top: 8px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
        z-index: 9999;
        right: 0;
        display: none;
        padding: 0;
        border: none;
        top: 100%;
        left: auto;
        overflow: hidden;
    }
    
    .custom-dropdown-menu.show {
        display: block;
        animation: fadeInDown 0.2s ease;
    }
    
    .dropdown-header {
        display: block;
        padding: 10px 15px;
        margin-bottom: 0;
        font-size: 0.8rem;
        color: #6c757d;
        white-space: nowrap;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 600;
        border-bottom: 1px solid rgba(0,0,0,0.05);
        background-color: #f8f9fa;
    }
    
    .custom-dropdown-item {
        display: flex;
        align-items: center;
        text-decoration: none;
        padding: 12px 15px;
        color: #223a66;
        width: 100%;
        clear: both;
        text-align: left;
        white-space: nowrap;
        background-color: transparent;
        transition: all 0.2s ease;
        font-weight: 500;
        font-size: 0.95rem;
    }
    
    .custom-dropdown-item i {
        font-size: 1.2rem;
    }
    
    .custom-dropdown-item:hover {
        background-color: #f0f5ff;
        text-decoration: none;
        color: #0056b3;
    }
    
    .custom-dropdown-item.active {
        background-color: #e9f0ff;
        color: #0056b3;
        font-weight: 600;
        border-left: 3px solid #0056b3;
    }
    
    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* Dark Theme Styles */
    body.dark-theme {
        background-color: #222;
        color: #eee;
    }

    body.dark-theme .header-top-bar {
        background-color: #1a1a1a !important;
    }

    body.dark-theme .navbar {
        background-color: #2a2a2a !important;
    }

    body.dark-theme .navbar-nav .nav-link {
        color: #eee !important;
    }

    body.dark-theme .dropdown-menu {
        background-color: #333 !important;
    }

    body.dark-theme .dropdown-item {
        color: #eee !important;
    }

    body.dark-theme .dropdown-item:hover {
        background-color: #444 !important;
    }

    body.dark-theme .section {
        background-color: #222 !important;
    }

    body.dark-theme .card,
    body.dark-theme .sidebar-widget {
        background-color: #333 !important;
        color: #eee !important;
    }

    body.dark-theme .footer {
        background-color: #1a1a1a !important;
    }
    
    body.dark-theme .language-switch-wrapper .dropdown-menu {
        background-color: #333;
    }
    
    body.dark-theme .language-switch-wrapper .dropdown-item {
        color: #eee;
    }
    
    body.dark-theme .language-switch-wrapper .dropdown-item:hover {
        background-color: #444;
    }

    /* Dark theme adjustments */
    body.dark-theme .dropdown-header {
        color: #adb5bd;
        border-bottom: 1px solid rgba(255,255,255,0.05);
        background-color: #2d2d2d;
    }
    
    body.dark-theme .custom-dropdown-menu {
        background-color: #222;
        border: 1px solid rgba(255,255,255,0.1);
    }
    
    body.dark-theme .custom-dropdown-item {
        color: #e0e0e0;
    }
    
    body.dark-theme .custom-dropdown-item:hover {
        background-color: #333;
        color: #fff;
    }
    
    body.dark-theme .custom-dropdown-item.active {
        background-color: #2a2a2a;
        color: #fff;
        border-left: 3px solid #38b5ff;
    }
</style>

<!-- Theme and Language Scripts -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Theme Toggle Functionality
        const themeToggle = document.getElementById('theme-toggle');
        const themeIcon = document.getElementById('theme-icon');

        // Check for saved theme preference or use preferred color scheme
        const currentTheme = localStorage.getItem('theme') ||
            (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');

        // Apply saved theme on page load
        if (currentTheme === 'dark') {
            document.body.classList.add('dark-theme');
            themeIcon.classList.remove('icofont-sun-alt');
            themeIcon.classList.add('icofont-moon');
        } else {
            document.body.classList.remove('dark-theme');
            themeIcon.classList.remove('icofont-moon');
            themeIcon.classList.add('icofont-sun-alt');
        }

        // Toggle theme when button is clicked
        themeToggle.addEventListener('click', function() {
            document.body.classList.toggle('dark-theme');

            // Update icon
            if (document.body.classList.contains('dark-theme')) {
                themeIcon.classList.remove('icofont-sun-alt');
                themeIcon.classList.add('icofont-moon');
                localStorage.setItem('theme', 'dark');
            } else {
                themeIcon.classList.remove('icofont-moon');
                themeIcon.classList.add('icofont-sun-alt');
                localStorage.setItem('theme', 'light');
            }
        });
        
        // Custom Language Dropdown Functionality
        const languageBtn = document.getElementById('language-toggle-btn');
        const languageMenu = document.getElementById('language-dropdown');
        
        if (languageBtn && languageMenu) {
            // Toggle language dropdown when button is clicked
            languageBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                // Toggle dropdown visibility
                languageMenu.classList.toggle('show');
            });
            
            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.custom-dropdown')) {
                    languageMenu.classList.remove('show');
                }
            });
        }
    });
</script>