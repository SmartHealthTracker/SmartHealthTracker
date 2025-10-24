<nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex flex-row shadow-sm bg-white">
  <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
    <a class="navbar-brand brand-logo" href="{{ url('/') }}">
      <img src="{{ url('assets/images/logo.png') }}" alt="logo" /> 
    </a>
   
  </div>

  <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
    {{-- Profile Dropdown --}}
    <ul class="navbar-nav navbar-nav-right">
      <li class="nav-item dropdown d-none d-xl-inline-block">
        <a class="nav-link dropdown-toggle" id="UserDropdown" href="#" data-toggle="dropdown" aria-expanded="false">
          <span class="profile-text d-none d-md-inline-flex">
            {{ Auth::user()->name ?? 'Utilisateur' }}
          </span>
          <img class="img-xs rounded-circle ml-2" 
               src="{{ Auth::user()->profile_image ?? url('assets/images/faces/face8.jpg') }}" 
               alt="Profile image"> 
        </a>
        <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
          <a class="dropdown-item mt-2">Manage Account</a>
          <a class="dropdown-item">Change Password</a>
          <div class="dropdown-divider"></div>
          {{-- Sign Out --}}
          <a class="dropdown-item text-danger" href="{{ route('logout') }}"
             onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="mdi mdi-logout mr-2"></i> Sign Out
          </a>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
          </form>
        </div>
      </li>
    </ul>

    {{-- Mobile toggle --}}
    <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
      <span class="mdi mdi-menu icon-menu"></span>
    </button>
  </div>
</nav>
