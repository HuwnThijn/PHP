@extends('user.theme.auth-layout')

@section('content')
<section class="page-title bg-1">
  <div class="overlay"></div>
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="block text-center">
          <span class="text-white">User Profile</span>
          <h1 class="text-capitalize mb-5 text-lg">Change Password</h1>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="row">
      <!-- Sidebar -->
      <div class="col-lg-4 col-md-5">
        <div class="sidebar-widget schedule-widget mb-3">
          <div class="text-center">
            <img src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : asset('user/theme/images/user-default.png') }}" alt="User Avatar" class="img-fluid rounded-circle mb-4" style="width: 150px; height: 150px; object-fit: cover;">
            <h4>{{ auth()->user()->name }}</h4>
            <p class="text-muted">{{ auth()->user()->email }}</p>
          </div>
          
          <ul class="list-unstyled">
            <li class="d-flex justify-content-between align-items-center">
              <a href="{{ route('user.profile') }}" class="text-color {{ request()->routeIs('user.profile') ? 'active' : '' }}">
                <i class="icofont-user mr-2"></i> Profile Information
              </a>
              <i class="icofont-rounded-right"></i>
            </li>
            <li class="d-flex justify-content-between align-items-center">
              <a href="{{ route('user.profile.password') }}" class="text-color {{ request()->routeIs('user.profile.password') ? 'active' : '' }}">
                <i class="icofont-ui-password mr-2"></i> Change Password
              </a>
              <i class="icofont-rounded-right"></i>
            </li>
            <li class="d-flex justify-content-between align-items-center">
              <a href="{{ route('user.cart') }}" class="text-color {{ request()->routeIs('user.cart') ? 'active' : '' }}">
                <i class="icofont-shopping-cart mr-2"></i> My Cart
              </a>
              <i class="icofont-rounded-right"></i>
            </li>
            <li class="d-flex justify-content-between align-items-center">
              <a href="{{ route('user.orders') }}" class="text-color {{ request()->routeIs('user.orders') ? 'active' : '' }}">
                <i class="icofont-file-document mr-2"></i> Order History
              </a>
              <i class="icofont-rounded-right"></i>
            </li>
            <li class="d-flex justify-content-between align-items-center">
              <a href="{{ route('user.logout') }}" class="text-color" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="icofont-logout mr-2"></i> Logout
              </a>
              <i class="icofont-rounded-right"></i>
            </li>
          </ul>
          
          <form id="logout-form" action="{{ route('user.logout') }}" method="POST" class="d-none">
            @csrf
          </form>
        </div>
      </div>
      
      <!-- Main Content -->
      <div class="col-lg-8 col-md-7">
        <div class="appoinment-wrap mt-5 mt-lg-0">
          <h2 class="mb-4">Change Password</h2>
          
          @if(session('success'))
          <div class="alert alert-success">
            {{ session('success') }}
          </div>
          @endif
          
          @if(session('error'))
          <div class="alert alert-danger">
            {{ session('error') }}
          </div>
          @endif
          
          <form class="appoinment-form" method="post" action="{{ route('user.profile.password.update') }}">
            @csrf
            @method('PUT')
            
            <div class="form-group">
              <label>Current Password</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="icofont-ui-password"></i></span>
                </div>
                <input name="current_password" type="password" class="form-control @error('current_password') is-invalid @enderror" placeholder="Enter current password" required>
                @error('current_password')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
                @enderror
              </div>
            </div>
            
            <div class="form-group">
              <label>New Password</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="icofont-ui-password"></i></span>
                </div>
                <input name="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Enter new password" required>
                @error('password')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
                @enderror
              </div>
              <small class="text-muted">Password must be at least 8 characters long</small>
            </div>
            
            <div class="form-group">
              <label>Confirm New Password</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="icofont-ui-password"></i></span>
                </div>
                <input name="password_confirmation" type="password" class="form-control" placeholder="Confirm new password" required>
              </div>
            </div>
            
            <div class="text-right">
              <button class="btn btn-main btn-round-full" type="submit">Update Password <i class="icofont-simple-right ml-2"></i></button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

<style>
  .sidebar-widget {
    background: #f4f9fc;
    padding: 30px;
    border-radius: 5px;
  }
  
  .sidebar-widget ul li {
    padding: 12px 0;
    border-bottom: 1px solid #e9e9e9;
  }
  
  .sidebar-widget ul li:last-child {
    border-bottom: 0;
  }
  
  .sidebar-widget ul li a {
    color: #6F8BA4;
    font-weight: 500;
  }
  
  .sidebar-widget ul li a.active {
    color: #223a66;
    font-weight: 600;
  }
</style>
@endsection 