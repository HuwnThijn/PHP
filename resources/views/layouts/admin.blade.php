<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Quản trị hệ thống</title>
    <!-- CSS -->
    <link href="{{ asset('admin/css/sb-admin-2.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            background-color: #f8f9fc;
            margin: 0;
            padding: 0;
        }
        .wrapper {
            display: flex;
            width: 100%;
            position: relative;
        }
        .sidebar {
            width: 230px;
            min-height: 100vh;
            background-color: #4e73df;
            background-image: linear-gradient(180deg, #4e73df 10%, #224abe 100%);
            background-size: cover;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 100;
            overflow-y: auto;
        }
        .sidebar-brand {
            height: 4.375rem;
            text-decoration: none;
            font-size: 1rem;
            font-weight: 800;
            padding: 1.5rem 1rem;
            text-align: center;
            letter-spacing: 0.05rem;
            color: white;
        }
        .sidebar-brand-text {
            color: white;
        }
        .sidebar hr {
            margin: 0 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.15);
        }
        .sidebar-heading {
            color: rgba(255, 255, 255, 0.4);
            text-align: left;
            padding: 0 1rem;
            font-weight: 800;
            font-size: 0.65rem;
            margin-top: 1rem;
        }
        .nav-item {
            position: relative;
        }
        .nav-item .nav-link {
            display: block;
            padding: 0.75rem 1rem;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
        }
        .nav-item .nav-link:hover {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.1);
        }
        .nav-item .nav-link i {
            margin-right: 0.5rem;
            color: rgba(255, 255, 255, 0.3);
        }
        .content-wrapper {
            width: calc(100% - 230px);
            min-height: 100vh;
            margin-left: 230px;
            transition: all 0.3s;
        }
        .topbar {
            background-color: #fff;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            height: 4.375rem;
            position: relative;
            z-index: 90;
        }
        .content {
            padding: 1.5rem;
        }
        .card {
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            margin-bottom: 1.5rem;
        }
        .card-header {
            background-color: #f8f9fc;
            border-bottom: 1px solid #e3e6f0;
        }
        @media (max-width: 768px) {
            .sidebar {
                width: 100px;
            }
            .sidebar .nav-item .nav-link span {
                display: none;
            }
            .content-wrapper {
                margin-left: 100px;
                width: calc(100% - 100px);
            }
        }
    </style>
    @yield('styles')
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <div class="sidebar">
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('admin.dashboard') }}">
                <div class="sidebar-brand-text mx-3">Quản trị</div>
            </a>

            <hr class="sidebar-divider my-0">

            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-fw fa-tachometer-alt"></i>
                        <span>Trang chủ</span>
                    </a>
                </li>

                <hr class="sidebar-divider">

                <div class="sidebar-heading">
                    Quản lý nhân viên
                </div>

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.staff.index') }}">
                        <i class="fas fa-fw fa-users"></i>
                        <span>Nhân viên</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.member.index') }}">
                        <i class="fas fa-fw fa-user-friends"></i>
                        <span>Khách hàng</span>
                    </a>
                </li>

                <hr class="sidebar-divider">

                <div class="sidebar-heading">
                    Quản lý dịch vụ
                </div>

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.medicine.index') }}">
                        <i class="fas fa-fw fa-pills"></i>
                        <span>Thuốc</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.treatment.index') }}">
                        <i class="fas fa-fw fa-spa"></i>
                        <span>Trị liệu</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.cosmetics.index') }}">
                        <i class="fas fa-fw fa-magic"></i>
                        <span>Mỹ phẩm</span>
                    </a>
                </li>

                <hr class="sidebar-divider">

                <div class="sidebar-heading">
                    Thống kê
                </div>

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.revenue.index') }}">
                        <i class="fas fa-fw fa-chart-line"></i>
                        <span>Doanh thu</span>
                    </a>
                </li>

                <hr class="sidebar-divider d-none d-md-block">

                <li class="nav-item">
                    <form action="{{ route('admin.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="nav-link border-0 bg-transparent w-100 text-left">
                            <i class="fas fa-fw fa-sign-out-alt"></i>
                            <span>Đăng xuất</span>
                        </button>
                    </form>
                </li>
            </ul>
        </div>

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <!-- Topbar -->
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                <button id="sidebarToggle" class="btn btn-link d-md-none rounded-circle mr-3">
                    <i class="fa fa-bars"></i>
                </button>
                <div class="ml-auto">
                    <span>Xin chào, {{ Auth::user()->name ?? 'Admin' }}</span>
                </div>
            </nav>

            <!-- Main Content -->
            <div class="content">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @yield('content')
            </div>

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2024</span>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle sidebar
        $("#sidebarToggle").on('click', function(e) {
            e.preventDefault();
            $(".sidebar").toggleClass("toggled");
            if ($(".sidebar").hasClass("toggled")) {
                $('.sidebar .nav-item .nav-link span').hide();
                $('.sidebar').css('width', '80px');
                $('.content-wrapper').css('margin-left', '80px');
                $('.content-wrapper').css('width', 'calc(100% - 80px)');
            } else {
                $('.sidebar .nav-item .nav-link span').show();
                $('.sidebar').css('width', '230px');
                $('.content-wrapper').css('margin-left', '230px');
                $('.content-wrapper').css('width', 'calc(100% - 230px)');
            }
        });
    </script>
    @yield('scripts')
</body>
</html> 