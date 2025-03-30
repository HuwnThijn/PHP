@extends('user.theme.auth-layout')

@section('content')
<section class="page-title bg-1">
  <div class="overlay"></div>
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="block text-center">
          <span class="text-white">Reset Password</span>
          <h1 class="text-capitalize mb-5 text-lg">Create New Password</h1>
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
          <h2 class="mb-4 text-center">Reset Your Password</h2>
          
          <p class="text-center mb-4">Please create a new password for your account.</p>
          
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
          
          <form id="reset-password-form" class="appoinment-form" method="post" action="{{ route('user.password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            
            <div class="form-group">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="icofont-email"></i></span>
                </div>
                <input name="email" type="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email Address" value="{{ $email ?? old('email') }}" required>
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
                <input name="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="New Password" required>
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
                <input name="password_confirmation" type="password" class="form-control" placeholder="Confirm New Password" required>
              </div>
            </div>
            
            <div class="text-center">
              <button class="btn btn-main btn-round-full" type="submit">Reset Password <i class="icofont-simple-right ml-2"></i></button>
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