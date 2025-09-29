@extends('layout.master-mini')

@section('content')
<div class="content-wrapper d-flex align-items-center justify-content-center auth theme-one" style="background-image: url({{ url('assets/images/auth/forgot.jpg') }}); background-size: cover;">
  <div class="row w-100">
    <div class="col-lg-4 mx-auto">
      <h2 class="text-center mb-4">Forgot Password</h2>
      <div class="auto-form-wrapper">
        <form method="POST" action="{{ route('password.email') }}">
          @csrf
          <input type="email" name="email" placeholder="Enter your email" required />
          <button type="submit">Send Reset Link</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
