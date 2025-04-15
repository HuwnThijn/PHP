@extends('user.theme.auth-layout')

@section('content')
<section class="page-title bg-1">
  <div class="overlay"></div>
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="block text-center">
          <span class="text-white">User Profile</span>
          <h1 class="text-capitalize mb-5 text-lg">My Account</h1>
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
            <img src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : asset('user/theme/images/shin.png') }}" alt="User Avatar" class="img-fluid rounded-circle mb-4" style="width: 150px; height: 150px; object-fit: cover;">
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
          <h2 class="mb-4">Profile Information</h2>
          
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
          
          {{-- @if(session('debug'))
          <div class="alert alert-info">
            <h5>Debug Info:</h5>
            <ul>
              @foreach(session('debug') as $key => $value)
              <li><strong>{{ $key }}:</strong> {{ $value }}</li>
              @endforeach
            </ul>
          </div>
          @endif --}}
          
          <form class="appoinment-form" method="post" action="{{ route('user.profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="row">
              <div class="col-lg-6">
                <div class="form-group">
                  <label>Email</label>
                  <input type="email" class="form-control" value="{{ auth()->user()->email }}" readonly disabled>
                  <small class="text-muted">Email cannot be changed</small>
                </div>
              </div>
              
              <div class="col-lg-6">
                <div class="form-group">
                  <label>Full Name</label>
                  <input name="name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{ auth()->user()->name }}" readonly disabled>
                  <small class="text-muted">Name cannot be changed</small>
                  @error('name')
                  <div class="invalid-feedback">
                    {{ $message }}
                  </div>
                  @enderror
                </div>
              </div>
              
              <div class="col-lg-6">
                <div class="form-group">
                  <label>Phone Number <span class="text-danger">*</span></label>
                  <input name="phone" type="text" class="form-control @error('phone') is-invalid @enderror" value="{{ auth()->user()->phone }}" required>
                  <small class="text-muted">This field is required and cannot be empty</small>
                  @error('phone')
                  <div class="invalid-feedback">
                    {{ $message }}
                  </div>
                  @enderror
                </div>
              </div>
              
              <div class="col-lg-6">
                <div class="form-group">
                  <label>Age</label>
                  <input name="age" type="number" class="form-control @error('age') is-invalid @enderror" value="{{ auth()->user()->age }}">
                  @error('age')
                  <div class="invalid-feedback">
                    {{ $message }}
                  </div>
                  @enderror
                </div>
              </div>
              
              <div class="col-lg-6">
                <div class="form-group">
                  <label>Gender</label>
                  <select name="gender" class="form-control @error('gender') is-invalid @enderror">
                    <option value="">Select Gender</option>
                    <option value="male" {{ auth()->user()->gender == 'male' ? 'selected' : '' }}>Male</option>
                    <option value="female" {{ auth()->user()->gender == 'female' ? 'selected' : '' }}>Female</option>
                    <option value="other" {{ auth()->user()->gender == 'other' ? 'selected' : '' }}>Other</option>
                  </select>
                  @error('gender')
                  <div class="invalid-feedback">
                    {{ $message }}
                  </div>
                  @enderror
                </div>
              </div>
              
              <div class="col-lg-6">
                <div class="form-group">
                  <label>Profile Picture</label>
                  <input name="avatar" type="file" class="form-control-file @error('avatar') is-invalid @enderror">
                  <small class="text-muted">Upload a new profile picture (JPG, PNG, max 2MB)</small>
                  @error('avatar')
                  <div class="invalid-feedback">
                    {{ $message }}
                  </div>
                  @enderror
                </div>
              </div>
              
              <div class="col-lg-12">
                <div class="form-group">
                  <label>Address</label>
                  <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="3">{{ auth()->user()->address }}</textarea>
                  @error('address')
                  <div class="invalid-feedback">
                    {{ $message }}
                  </div>
                  @enderror
                </div>
              </div>
            </div>
            
            <div class="text-right">
              <button class="btn btn-main btn-round-full" type="submit">Update Profile <i class="icofont-simple-right ml-2"></i></button>
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

<script>
  // Form validation before submission
  document.addEventListener('DOMContentLoaded', function() {
    const profileForm = document.querySelector('.appoinment-form');
    
    profileForm.addEventListener('submit', function(event) {
      const phoneInput = document.querySelector('input[name="phone"]');
      
      if (!phoneInput.value.trim()) {
        event.preventDefault();
        
        // Create alert message
        const alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-danger';
        alertDiv.textContent = 'Số điện thoại không được để trống. Vui lòng nhập số điện thoại của bạn.';
        
        // Insert alert at the top of the form
        const firstElement = profileForm.firstElementChild;
        profileForm.insertBefore(alertDiv, firstElement);
        
        // Scroll to the alert
        alertDiv.scrollIntoView({ behavior: 'smooth' });
        
        // Focus on the phone input
        phoneInput.focus();
        
        // Remove alert after 5 seconds
        setTimeout(() => {
          alertDiv.remove();
        }, 5000);
      }
    });
  });
</script>
@endsection 