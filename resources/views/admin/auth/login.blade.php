

@section('content')
<div class="login-box">
    <div class="login-logo">
        <b>Admin</b> Login
    </div>
    <div class="card">
        <div class="card-body login-card-body">
            <form action="{{ route('admin.login.post') }}" method="post">
                @csrf
                <div class="input-group mb-3">
                    <input type="username" name="username" class="form-control" placeholder="Email" required>
                </div>
                <div class="input-group mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Mật khẩu" required>
                </div>
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-block">Đăng nhập</button>
                    </div>
                </div>
            </form>
            <p class="mt-3 text-center">
            </p>
        </div>
    </div>
</div>
@endsection
