@extends('layout.master-mini')

@section('content')
<div class="content-wrapper d-flex align-items-center justify-content-center auth theme-one" style="background-image: url({{ url('assets/images/auth/reset.jpg') }}); background-size: cover;">
  <div class="row w-100">
    <div class="col-lg-4 mx-auto">
      <h2 class="text-center mb-4">Reset Password</h2>
      <div class="auto-form-wrapper">
        <form method="POST" action="{{ route('password.update') }}">
          @csrf
          <input type="hidden" name="token" value="{{ $token }}">
          <input type="email" name="email" value="{{ $email ?? old('email') }}" placeholder="Email" required />
          <input type="password" name="password" placeholder="New Password" required />
          <input type="password" name="password_confirmation" placeholder="Confirm Password" required />
          <button type="submit">Reset Password</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
