@extends('layout.master-mini')

@section('content')
<div class="content-wrapper d-flex align-items-center justify-content-center auth theme-one" style="background-image: url({{ url('assets/images/auth/login_1.jpg') }}); background-size: cover;">
  <div class="row w-100">
    <div class="col-lg-4 mx-auto">
      <div class="auto-form-wrapper">

        <!-- Formulaire de connexion -->
        <form method="POST" action="{{ route('login.post') }}">
          @csrf

          <!-- Affichage des erreurs -->
          @if($errors->any())
            <div class="alert alert-danger">
              {{ $errors->first() }}
            </div>
          @endif

          <!-- Email -->
          <div class="form-group">
            <label class="label">Email</label>
            <div class="input-group">
              <input type="email" name="email" class="form-control" placeholder="Email" required>
              <div class="input-group-append">
                <span class="input-group-text">
                  <i class="mdi mdi-check-circle-outline"></i>
                </span>
              </div>
            </div>
          </div>

          <!-- Password -->
          <div class="form-group">
            <label class="label">Password</label>
            <div class="input-group">
              <input type="password" name="password" class="form-control" placeholder="*********" required>
              <div class="input-group-append">
                <span class="input-group-text">
                  <i class="mdi mdi-check-circle-outline"></i>
                </span>
              </div>
            </div>
          </div>

          <!-- Remember me -->
          <div class="form-group d-flex justify-content-between">
            <div class="form-check form-check-flat mt-0">
              <label class="form-check-label">
                <input type="checkbox" name="remember" class="form-check-input"> Keep me signed in
              </label>
            </div>
          <a href="{{ route('password.request') }}" class="text-small forgot-password text-black">Forgot Password</a>
          </div>

          <!-- Bouton login -->
          <div class="form-group">
            <button type="submit" class="btn btn-primary submit-btn btn-block">Login</button>
          </div>

          <!-- Login with Google -->
       <div class="form-group">
    <a href="{{ route('auth.google') }}" class="btn btn-block g-login">
        <img class="mr-3" src="{{ url('assets/images/file-icons/icon-google.svg') }}" alt="">
        Log in with Google
    </a>
</div>


          <!-- Lien vers inscription -->
          <div class="text-block text-center my-3">
            <span class="text-small font-weight-semibold">Not a member ?</span>
            <a href="{{ url('/user-pages/register') }}" class="text-black text-small">Create new account</a>
          </div>
        </form>

      </div>

      <!-- Footer -->
      <ul class="auth-footer">
        <li><a href="#">Conditions</a></li>
        <li><a href="#">Help</a></li>
        <li><a href="#">Terms</a></li>
      </ul>
      <p class="footer-text text-center">copyright © 2018 Bootstrapdash. All rights reserved.</p>
    </div>
  </div>
</div>
@endsection
