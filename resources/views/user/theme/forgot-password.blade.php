@extends('user.theme.auth-layout')

@section('content')
<section class="page-title bg-1">
  <div class="overlay"></div>
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="block text-center">
          <span class="text-white">Forgot Password</span>
          <h1 class="text-capitalize mb-5 text-lg">Reset Your Password</h1>
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
          <h2 class="mb-4 text-center">Forgot Password</h2>
          
          <p class="text-center mb-4">Enter your email address and we'll send you a link to reset your password.</p>
          
          @if(session('status'))
          <div class="alert alert-success">
            {{ session('status') }}
          </div>
          @endif
          
          @if(session('error'))
          <div class="alert alert-danger">
            {{ session('error') }}
          </div>
          @endif
          
          <form id="forgot-password-form" class="appoinment-form" method="post" action="{{ route('user.password.email') }}">
            @csrf
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
            
            <div class="text-center">
              <button class="btn btn-main btn-round-full" type="submit">Send Password Reset Link <i class="icofont-simple-right ml-2"></i></button>
            </div>
          </form>
          
          <div class="text-center mt-4">
            <p>Remember your password? <a href="{{ route('user.login') }}" class="text-color">Login</a></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection 