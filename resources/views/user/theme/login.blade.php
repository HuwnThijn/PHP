@extends('user.theme.auth-layout')

@section('content')
<section class="page-title bg-1">
  <div class="overlay"></div>
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="block text-center">
          <span class="text-white">Login</span>
          <h1 class="text-capitalize mb-5 text-lg">Account Login</h1>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-6">
        <div class="appoinment-wrap mt-5 mt-lg-0 pl-lg-5">
          <h2 class="mb-4 text-center">Login or <a href="{{ route('user.register') }}" class="text-color">Sign up</a></h2>
          
          <div class="row mb-4">
            <div class="col-md-4">
              <a href="{{ route('user.socialite.redirect', 'facebook') }}" class="btn btn-primary btn-block">
                <i class="icofont-facebook mr-2"></i> Facebook
              </a>
            </div>
            <div class="col-md-4">
              <a href="{{ route('user.socialite.redirect', 'twitter') }}" class="btn btn-info btn-block">
                <i class="icofont-twitter mr-2"></i> Twitter
              </a>
            </div>
            <div class="col-md-4">
              <a href="{{ route('user.socialite.redirect', 'google') }}" class="btn btn-danger btn-block">
                <i class="icofont-google-plus mr-2"></i> Google+
              </a>
            </div>
          </div>
          
          <div class="text-center mb-4">
            <span class="separator">or</span>
          </div>
          
          @if(session('error'))
          <div class="alert alert-danger">
            {{ session('error') }}
          </div>
          @endif
          
          @if(session('success'))
          <div class="alert alert-success">
            {{ session('success') }}
          </div>
          @endif
          
          <form id="login-form" class="appoinment-form" method="post" action="{{ route('login') }}">
            @csrf
            <input type="hidden" name="redirect" value="{{ request()->query('redirect', url()->previous()) }}">
            <div class="form-group">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="icofont-email"></i></span>
                </div>
                <input name="email" type="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email Address" value="{{ old('email') }}" required>
                @error('email')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
                @enderror
              </div>
            </div>
            
            <div class="form-group">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="icofont-ui-password"></i></span>
                </div>
                <input name="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password" required>
                @error('password')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
                @enderror
              </div>
              @if(session('password_error'))
              <div class="text-danger mt-2">
                {{ session('password_error') }}
              </div>
              @endif
            </div>
            
            <div class="form-group d-flex justify-content-between">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <label class="form-check-label" for="remember">
                  Remember Me
                </label>
              </div>
              <a href="{{ route('user.password.request') }}" class="text-color">Forgot password?</a>
            </div>
            
            <div class="text-center">
              <button class="btn btn-main btn-round-full" type="submit">Login <i class="icofont-simple-right ml-2"></i></button>
            </div>
          </form>
          
          <div class="text-center mt-4">
            <p>Don't have an account? <a href="{{ route('user.register') }}" class="text-color">Sign up</a></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<style>
  .separator {
    display: flex;
    align-items: center;
    text-align: center;
    color: #6F8BA4;
  }
  
  .separator::before,
  .separator::after {
    content: '';
    flex: 1;
    border-bottom: 1px solid #e9ecef;
  }
  
  .separator::before {
    margin-right: 1em;
  }
  
  .separator::after {
    margin-left: 1em;
  }
  
  .btn-block {
    display: block;
    width: 100%;
  }
</style>

<!-- Thêm script xử lý remember me -->
<script src="{{ asset('js/login-remember.js') }}"></script>
@endsection 