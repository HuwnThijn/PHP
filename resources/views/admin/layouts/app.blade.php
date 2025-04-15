<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Quản trị - @yield('title')</title>
    <!-- Custom fonts for this template-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    
    <!-- Custom styles for this template-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    
    <style>
        /* Custom CSS cho tương thích với Bootstrap 5 */
        .bg-gradient-primary {
            background-color: #4e73df;
            background-image: linear-gradient(180deg, #4e73df 10%, #224abe 100%);
            background-size: cover;
        }
        .sidebar-dark .nav-item .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 0.85rem 1.2rem;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
            font-weight: 500;
            display: flex;
            align-items: center;
        }
        .sidebar-dark .nav-item .nav-link i {
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.9rem;
            margin-right: 0.85rem;
            transition: all 0.3s ease;
            width: 1.2rem;
            text-align: center;
        }
        .sidebar-dark .nav-item .nav-link:active, 
        .sidebar-dark .nav-item .nav-link:focus, 
        .sidebar-dark .nav-item .nav-link:hover {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.1);
            border-left: 3px solid #fff;
        }
        .sidebar-dark .nav-item .nav-link:active i, 
        .sidebar-dark .nav-item .nav-link:focus i, 
        .sidebar-dark .nav-item .nav-link:hover i {
            color: #fff;
            transform: translateX(3px);
        }
        .sidebar-dark .nav-item.active .nav-link {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.15);
            border-left: 3px solid #fff;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) inset;
        }
        .sidebar-dark .nav-item.active .nav-link i {
            color: #fff;
        }
        .sidebar-dark .sidebar-heading {
            color: rgba(255, 255, 255, 0.4);
            font-size: 0.75rem;
            letter-spacing: 1px;
            padding: 1rem 1.2rem 0.5rem;
            text-transform: uppercase;
            font-weight: 700;
        }
        .sidebar-dark hr.sidebar-divider {
            border-top: 1px solid rgba(255, 255, 255, 0.15);
            margin: 0.75rem 1rem;
        }
        .sidebar-brand {
            padding: 1.5rem 1rem;
            font-weight: 800;
            font-size: 1.15rem;
            letter-spacing: 0.05rem;
            text-transform: uppercase;
        }
        .sidebar-brand-text {
            margin-left: 0.25rem;
        }
        .sidebar .sidebar-brand .sidebar-brand-text {
            font-size: 1.15rem;
        }
        .sidebar-dark .nav-item .nav-link span {
            font-size: 0.875rem;
        }
        #sidebarToggle {
            background-color: rgba(255, 255, 255, 0.2);
            height: 2.5rem;
            width: 2.5rem;
            text-align: center;
            margin-bottom: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        #sidebarToggle:hover {
            background-color: rgba(255, 255, 255, 0.3);
        }
        #sidebarToggle i {
            color: rgba(255, 255, 255, 0.5);
            font-size: 1rem;
            line-height: 2.5rem;
            transition: all 0.3s ease;
        }
        #sidebarToggle:hover i {
            color: #fff;
            transform: rotate(-180deg);
        }
        .nav-item:last-child {
            margin-bottom: 1rem;
        }
        .sidebar-dark .nav-item button.nav-link {
            width: 100%;
            text-align: left;
            cursor: pointer;
        }
        .badge {
            margin-left: auto;
            padding: 0.35em 0.65em;
            font-size: 0.7em;
        }
        .sidebar-brand-icon {
            font-size: 1.8rem;
            color: white;
        }
        .topbar .nav-item .nav-link {
            height: 4.375rem;
            display: flex;
            align-items: center;
            padding: 0 0.75rem;
        }
        .topbar .nav-item .nav-link:hover {
            background-color: #f8f9fc;
        }
        .dropdown-toggle::after {
            margin-left: 0.5rem;
        }
        .dropdown-menu {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.175);
            padding: 0.5rem 0;
            border: none;
        }
        .dropdown-item {
            padding: 0.5rem 1.5rem;
            font-weight: 400;
            font-size: 0.85rem;
        }
        .dropdown-item:hover {
            background-color: #f8f9fc;
        }
        .dropdown-item i {
            margin-right: 0.5rem;
        }
        .dropdown-item.active, .dropdown-item:active {
            background-color: #4e73df;
        }
        .dropdown-divider {
            margin: 0.5rem 0;
        }
        #wrapper {
            display: flex;
        }
        #content-wrapper {
            background-color: #f8f9fc;
            width: 100%;
            overflow-x: hidden;
        }
        .sidebar {
            width: 14rem !important;
            min-height: 100vh;
        }
        .sidebar .nav-item .nav-link {
            padding: 0.75rem 1rem;
            width: 14rem;
        }
        .sidebar .nav-item .nav-link span {
            font-size: 0.85rem;
            display: inline;
        }
        .sidebar .nav-item .nav-link i {
            margin-right: 0.25rem;
            font-size: 0.85rem;
        }
        .sidebar .sidebar-heading {
            text-align: center;
            padding: 0 1rem;
            font-weight: 800;
            font-size: 0.65rem;
            text-transform: uppercase;
        }
        @media (min-width: 768px) {
            .sidebar {
                width: 14rem !important;
            }
            .sidebar .nav-item .nav-link {
                width: 14rem;
            }
            .sidebar.toggled {
                width: 6.5rem !important;
                overflow: visible;
            }
            .sidebar.toggled .nav-item .nav-link {
                width: 6.5rem;
                text-align: center;
                padding: 0.75rem 1rem;
            }
            .sidebar.toggled .nav-item .nav-link span {
                display: none;
            }
            .sidebar.toggled .nav-item .nav-link i {
                font-size: 1.25rem;
                margin-right: 0;
            }
            .sidebar.toggled .sidebar-heading {
                display: none;
            }
        }
    </style>
    @stack('styles')
</head>

<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('admin.dashboard') }}">
                <div class="sidebar-brand-icon">
                    <i class="fas fa-clinic-medical"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Y-Clinic</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item {{ Request::routeIs('admin.dashboard') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Trang chủ</span>
                </a>
            </li>

            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Quản lý nhân sự
            </div>

            <!-- Nav Item - Staff -->
            <li class="nav-item {{ Request::routeIs('admin.staff.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.staff.index') }}">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Nhân viên</span>
                </a>
            </li>

            <li class="nav-item {{ Request::routeIs('admin.member.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.member.index') }}">
                    <i class="fas fa-fw fa-user-friends"></i>
                    <span>Khách hàng</span>
                    <span class="badge bg-info rounded-pill text-white ml-auto">{{ \App\Models\User::where('id_role', 4)->count() }}</span>
                </a>
            </li>

            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Quản lý sản phẩm
            </div>

            <!-- Nav Item - Medicines -->
            <li class="nav-item {{ Request::routeIs('admin.medicine.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.medicine.index') }}">
                    <i class="fas fa-fw fa-pills"></i>
                    <span>Thuốc</span>
                </a>
            </li>

            <!-- Nav Item - Treatments -->
            <li class="nav-item {{ Request::routeIs('admin.treatment.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.treatment.index') }}">
                    <i class="fas fa-fw fa-spa"></i>
                    <span>Trị liệu</span>
                </a>
            </li>
            
            <!-- Nav Item - Cosmetics -->
            <li class="nav-item {{ Request::routeIs('admin.cosmetics.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.cosmetics.index') }}">
                    <i class="fas fa-fw fa-magic"></i>
                    <span>Mỹ phẩm</span>
                </a>
            </li>

            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Quản lý đơn hàng
            </div>

            <!-- Nav Item - Orders -->
            <li class="nav-item {{ Request::routeIs('admin.orders.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.orders.index') }}">
                    <i class="fas fa-fw fa-shopping-cart"></i>
                    <span>Đơn hàng</span>
                    @php
                        $pendingOrdersCount = \App\Models\Order::where('status', 'pending')->count();
                    @endphp
                    @if($pendingOrdersCount > 0)
                        <span class="badge bg-warning rounded-pill text-white ml-auto">{{ $pendingOrdersCount }}</span>
                    @endif
                </a>
            </li>

            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Báo cáo & Thống kê
            </div>

            <!-- Nav Item - Revenue -->
            <li class="nav-item {{ Request::routeIs('admin.revenue.*') ? 'active' : '' }}">
                <a class="nav-link d-flex align-items-center" href="{{ route('admin.revenue.index') }}">
                    <i class="fas fa-fw fa-chart-line"></i>
                    <span>Doanh thu</span>
                </a>
            </li>

            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle">
                    <i class="fas fa-angle-left"></i>
                </button>
            </div>

            <!-- Nav Item - Logout -->
            <li class="nav-item">
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="nav-link bg-transparent border-0 w-100 text-left">
                        <i class="fas fa-fw fa-sign-out-alt"></i>
                        <span>Đăng xuất</span>
                    </button>
                </form>
            </li>
        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ Auth::user()->name ?? 'Admin' }}</span>
                                <i class="fas fa-user fa-sm fa-fw text-gray-400"></i>
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-end shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Hồ sơ
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Cài đặt
                                </a>
                                <div class="dropdown-divider"></div>
                                <form action="{{ route('admin.logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                        Đăng xuất
                                    </button>
                                </form>
                            </div>
                        </li>
                    </ul>
                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">@yield('title')</h1>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @yield('content')
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2024</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->
        </div>
        <!-- End of Content Wrapper -->
    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Bootstrap core JavaScript-->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script>
        // Toggle the side navigation
        $(document).ready(function() {
            $("#sidebarToggle, #sidebarToggleTop").on('click', function(e) {
                $("body").toggleClass("sidebar-toggled");
                $(".sidebar").toggleClass("toggled");
                if ($(".sidebar").hasClass("toggled")) {
                    $('.sidebar .collapse').collapse('hide');
                };
            });

            // Close any open menu accordions when window is resized below 768px
            $(window).resize(function() {
                if ($(window).width() < 768) {
                    $('.sidebar .collapse').collapse('hide');
                };
            });

            // Prevent the content wrapper from scrolling when the fixed side navigation hovered over
            $('body.fixed-nav .sidebar').on('mousewheel DOMMouseScroll wheel', function(e) {
                if ($(window).width() > 768) {
                    var e0 = e.originalEvent,
                        delta = e0.wheelDelta || -e0.detail;
                    this.scrollTop += (delta < 0 ? 1 : -1) * 30;
                    e.preventDefault();
                }
            });

            // Scroll to top button appear
            $(document).on('scroll', function() {
                var scrollDistance = $(this).scrollTop();
                if (scrollDistance > 100) {
                    $('.scroll-to-top').fadeIn();
                } else {
                    $('.scroll-to-top').fadeOut();
                }
            });

            // Smooth scrolling using jQuery easing
            $(document).on('click', 'a.scroll-to-top', function(e) {
                var $anchor = $(this);
                $('html, body').stop().animate({
                    scrollTop: ($($anchor.attr('href')).offset().top)
                }, 1000, 'easeInOutExpo');
                e.preventDefault();
            });
        });
    </script>
    @stack('scripts')
</body>
</html> 