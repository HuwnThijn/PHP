<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Đăng nhập - Quản trị hệ thống</title>
    
    <!-- Custom fonts for this template-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary: #4e73df;
            --success: #1cc88a;
            --info: #36b9cc;
            --warning: #f6c23e;
            --danger: #e74a3b;
        }
        
        body {
            background-color: #4e73df;
            background-image: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            background-size: cover;
            font-family: 'Nunito', sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
        }
        
        .card {
            border: none;
            border-radius: 1rem;
        }
        
        .card-body {
            padding: 2rem;
        }
        
        .login-heading {
            font-weight: 700;
            color: #4e73df;
            margin-bottom: 1.5rem;
        }
        
        .form-control {
            border-radius: 10rem;
            padding: 1.5rem 1rem;
            font-size: 0.9rem;
        }
        
        .btn-login {
            font-size: 0.9rem;
            letter-spacing: 0.05rem;
            padding: 0.75rem 1rem;
            border-radius: 10rem;
            background-color: #4e73df;
            border-color: #4e73df;
            font-weight: bold;
        }
        
        .btn-login:hover {
            background-color: #224abe;
            border-color: #224abe;
        }
        
        .logo-wrapper {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .logo-text {
            font-size: 1.75rem;
            font-weight: 800;
            color: #4e73df;
            text-transform: uppercase;
            letter-spacing: 0.05rem;
        }
        
        .form-label {
            font-weight: 600;
            color: #5a5c69;
        }
        
        .form-floating > .form-control {
            padding-top: 1.625rem;
            padding-bottom: 0.625rem;
        }
        
        .form-floating > label {
            padding: 1rem 0.75rem;
        }
        
        .alert {
            border-radius: 0.35rem;
            border-left: 0.25rem solid #e74a3b;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12 col-md-9">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-login-image" style="background: url('https://source.unsplash.com/K4mSJ7kc0As/600x800'); background-position: center; background-size: cover;"></div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="logo-wrapper">
                                        <div class="logo-text mb-2">QUẢN TRỊ</div>
                                        <div class="text-center">
                                            <i class="fas fa-stethoscope fa-3x text-primary mb-3"></i>
                                        </div>
                                    </div>
                                    
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 login-heading mb-4">Đăng nhập hệ thống</h1>
                                    </div>
                                    
                                    @if($errors->has('email'))
                                        <div class="alert alert-danger mb-4 font-weight-bold">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            {{ $errors->first('email') }}
                                        </div>
                                    @endif

                                    @if($errors->has('password'))
                                        <div class="alert alert-danger mb-4 font-weight-bold">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            {{ $errors->first('password') }}
                                        </div>
                                    @endif
                                    
                                    <form class="user" action="{{ route('admin.login.submit') }}" method="POST">
                                        @csrf
                                        <div class="form-floating mb-4">
                                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                                id="email" name="email" value="{{ old('email') }}" placeholder="Nhập địa chỉ email" required>
                                            <label for="email">Email</label>
                                        </div>
                                        
                                        <div class="form-floating mb-4">
                                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                                id="password" name="password" placeholder="Nhập mật khẩu" required>
                                            <label for="password">Mật khẩu</label>
                                        </div>
                                        
                                        <div class="form-check mb-4">
                                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                            <label class="form-check-label" for="remember">Ghi nhớ đăng nhập</label>
                                        </div>
                                        
                                        <button type="submit" class="btn btn-primary btn-login btn-user w-100">
                                            <i class="fas fa-sign-in-alt me-2"></i> Đăng nhập
                                        </button>
                                    </form>
                                    
                                    <hr>
                                    
                                    <div class="text-center mt-4">
                                        <a class="small text-decoration-none" href="#"><i class="fas fa-key me-1"></i> Quên mật khẩu?</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 