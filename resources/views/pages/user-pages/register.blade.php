@extends('layout.master-mini')

@section('content')
<div class="content-wrapper d-flex align-items-center justify-content-center auth theme-one" style="background-image: url({{ url('assets/images/auth/register.jpg') }}); background-size: cover; min-height: 100vh;">
  <div class="row w-100">
    <div class="col-lg-4 mx-auto">
      <div class="card shadow-lg border-0 rounded-lg">
        <div class="card-body p-4">
          <h2 class="text-center mb-4 font-weight-bold">Create Account</h2>
          
          <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Username -->
            <div class="form-group mb-3">
              <label for="name" class="form-label">Username</label>
              <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" placeholder="Enter your username" required autofocus>
              @error('name')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>

            <!-- Email -->
            <div class="form-group mb-3">
              <label for="email" class="form-label">Email</label>
              <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Enter your email" required>
              @error('email')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>

            <!-- Password -->
            <div class="form-group mb-3">
              <label for="password" class="form-label">Password</label>
              <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Enter your password" required>
              @error('password')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>

            <!-- Confirm Password -->
            <div class="form-group mb-4">
              <label for="password_confirmation" class="form-label">Confirm Password</label>
              <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" placeholder="Confirm your password" required>
            </div>

            <!-- Submit -->
            <div class="d-grid">
              <button type="submit" class="btn btn-primary btn-block font-weight-bold">Register</button>
            </div>

          </form>

          <!-- Login link -->
          <div class="text-center mt-3">
            <small>Already have an account? <a href="{{ route('login') }}">Login here</a></small>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection