@extends('layout.master-mini')

@section('content')
<div class="content-wrapper d-flex align-items-center justify-content-center auth theme-one" style="background-image: url({{ url('assets/images/auth/register.jpg') }}); background-size: cover;">
  <div class="row w-100">
    <div class="col-lg-4 mx-auto">
      <h2 class="text-center mb-4">Register</h2>
      <div class="auto-form-wrapper">
        <!-- Formulaire corrigé -->
        <form method="POST" action="{{ route('register') }}">
    @csrf
    <input type="text" name="name" placeholder="Username" />
    <input type="email" name="email" placeholder="Email" />
    <input type="password" name="password" placeholder="Password" />
    <input type="password" name="password_confirmation" placeholder="Confirm Password" />
    <button type="submit">Register</button>
</form>

      </div>
    </div>
  </div>
</div>
@endsection
