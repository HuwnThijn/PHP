@extends('user.theme.auth-layout')

@section('content')
<section class="page-title bg-1">
  <div class="overlay"></div>
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="block text-center">
          <span class="text-white">Register</span>
          <h1 class="text-capitalize mb-5 text-lg">Create Account</h1>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-7">
        <div class="appoinment-wrap mt-5 mt-lg-0 pl-lg-5">
          <h2 class="mb-4 text-center">Sign up or <a href="{{ route('user.login') }}" class="text-color">Login</a></h2>
          
          <div class="row mb-4">
            <div class="col-md-4">
              <a href="{{ route('auth.social', 'facebook') }}" class="btn btn-facebook btn-block">
                <i class="icofont-facebook mr-2"></i> Facebook
              </a>
            </div>
            <div class="col-md-4">
              <a href="{{ route('auth.social', 'twitter') }}" class="btn btn-twitter btn-block">
                <i class="icofont-twitter mr-2"></i> Twitter
              </a>
            </div>
            <div class="col-md-4">
              <a href="{{ route('auth.social', 'google') }}" class="btn btn-google btn-block">
                <i class="icofont-google-plus mr-2"></i> Google
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
          
          <form id="register-form" class="appoinment-form" method="post" action="{{ route('user.register.submit') }}">
            @csrf
            <div class="form-group">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="icofont-user"></i></span>
                </div>
                <input name="name" type="text" class="form-control @error('name') is-invalid @enderror" placeholder="Full Name" value="{{ old('name') }}" required>
                @error('name')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
                @enderror
              </div>
            </div>
            
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
            </div>
            
            <div class="form-group">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="icofont-ui-password"></i></span>
                </div>
                <input name="password_confirmation" type="password" class="form-control" placeholder="Confirm Password" required>
              </div>
            </div>
            
            <div class="form-group">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="terms" id="terms" required>
                <label class="form-check-label" for="terms">
                  I agree to the <a href="#" class="text-color">Terms of Service</a> and <a href="#" class="text-color">Privacy Policy</a>
                </label>
                @error('terms')
                <div class="text-danger">
                  {{ $message }}
                </div>
                @enderror
              </div>
            </div>
            
            <div class="text-center">
              <button class="btn btn-main btn-round-full" type="submit">Register <i class="icofont-simple-right ml-2"></i></button>
            </div>
          </form>
          
          <div class="text-center mt-4">
            <p>Already have an account? <a href="{{ route('user.login') }}" class="text-color">Login</a></p>
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
  
  /* CSS cho nút đăng nhập mạng xã hội */
  .btn-facebook {
    background-color: #3b5998;
    border-color: #3b5998;
    color: white;
  }
  .btn-facebook:hover {
    background-color: #2d4373;
    border-color: #2d4373;
    color: white;
  }
  
  .btn-twitter {
    background-color: #1da1f2;
    border-color: #1da1f2;
    color: white;
  }
  .btn-twitter:hover {
    background-color: #0c85d0;
    border-color: #0c85d0;
    color: white;
  }
  
  .btn-google {
    background-color: #ea4335;
    border-color: #ea4335;
    color: white;
  }
  .btn-google:hover {
    background-color: #d62516;
    border-color: #d62516;
    color: white;
  }
</style>
@endsection 