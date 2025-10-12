<nav class="sidebar sidebar-offcanvas dynamic-active-class-disabled" id="sidebar">
  <ul class="nav">
    <li class="nav-item nav-profile not-navigation-link">
  <div class="nav-link">
    <div class="user-wrapper">
      <div class="profile-image">
        <img src="{{ Auth::user()->profile_image ?? url('assets/images/faces/face8.jpg') }}" alt="profile image">
      </div>
      <div class="text-wrapper">
        <p class="profile-name">{{ Auth::user()->name ?? 'Utilisateur' }}</p>
        <div class="dropdown" data-display="static">
          <a href="#" class="nav-link d-flex user-switch-dropdown-toggler" id="UsersettingsDropdown" data-toggle="dropdown" aria-expanded="false">
            <small class="designation text-muted">
              {{ Auth::user()->role ?? 'Member' }}
            </small>
            <span class="status-indicator online"></span>
          </a>
          <div class="dropdown-menu" aria-labelledby="UsersettingsDropdown">
            <a class="dropdown-item mt-2">Manage Accounts</a>
            <a class="dropdown-item">Change Password</a>
            <a class="dropdown-item">Check Inbox</a>

            {{-- Déconnexion --}}
            <a class="dropdown-item" href="{{ route('logout') }}"
               onclick="event.preventDefault(); document.getElementById('logout-form-sidebar').submit();">
              Sign Out
            </a>
            <form id="logout-form-sidebar" action="{{ route('logout') }}" method="POST" style="display: none;">
              @csrf
            </form>
          </div>
        </div>
      </div>
    </div>
    <button class="btn btn-success btn-block">
      New Project <i class="mdi mdi-plus"></i>
    </button>
  </div>
</li>

    <li class="nav-item {{ active_class(['/']) }}">
      <a class="nav-link" href="{{ url('/') }}">
        <i class="menu-icon mdi mdi-television"></i>
        <span class="menu-title">Dashboard</span>
      </a>
    </li>
    {{-- Lien vers les utilisateurs --}}
    <li class="nav-item {{ active_class(['admin/users']) }}">
      <a class="nav-link" href="{{ url('/admin/users') }}">
        <i class="menu-icon mdi mdi-account-multiple"></i>
        <span class="menu-title">Utilisateurs</span>
      </a>
    </li>
    <li class="nav-item {{ active_class(['/participations']) }}">
      <a class="nav-link" href="{{ url('/participations') }}">
          <i class="menu-icon mdi mdi-account-multiple"></i>
          <span class="menu-title">Participations</span>
      </a>  
    </li>
    <li class="nav-item {{ active_class(['/challenges']) }}">
      <a class="nav-link" href="{{ url('/challenges') }}">
          <i class="menu-icon mdi mdi-account-multiple"></i>
          <span class="menu-title">Challenges</span>
      </a>  
    </li>
    <li class="nav-item {{ active_class(['/admin/resources']) }}">
      <a class="nav-link" href="{{ url('/admin/resources') }}">
          <i class="menu-icon mdi mdi-account-multiple"></i>
          <span class="menu-title">Resources</span>
      </a>  
    </li>
    <li class="nav-item {{ active_class(['/admin/comments']) }}">
      <a class="nav-link" href="{{ url('/admin/comments') }}">
          <i class="menu-icon mdi mdi-account-multiple"></i>
          <span class="menu-title">Comments</span>
      </a>  
    </li>

    
    <li class="nav-item {{ active_class(['basic-ui/*']) }}">
      <a class="nav-link" data-toggle="collapse" href="#basic-ui" aria-expanded="{{ is_active_route(['basic-ui/*']) }}" aria-controls="basic-ui">
        <i class="menu-icon mdi mdi-dna"></i>
        <span class="menu-title">Basic UI Elements</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse {{ show_class(['basic-ui/*']) }}" id="basic-ui">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item {{ active_class(['basic-ui/buttons']) }}">
            <a class="nav-link" href="{{ url('/basic-ui/buttons') }}">Buttons</a>
          </li>
          <li class="nav-item {{ active_class(['basic-ui/dropdowns']) }}">
            <a class="nav-link" href="{{ url('/basic-ui/dropdowns') }}">Dropdowns</a>
          </li>
          <li class="nav-item {{ active_class(['basic-ui/typography']) }}">
            <a class="nav-link" href="{{ url('/basic-ui/typography') }}">Typography</a>
          </li>
        </ul>
      </div>
    </li>

    <li class="nav-item {{ active_class(['charts/chartjs']) }}">
      <a class="nav-link" href="{{ url('/charts/chartjs') }}">
        <i class="menu-icon mdi mdi-chart-line"></i>
        <span class="menu-title">Charts</span>
      </a>
    </li>
    <li class="nav-item {{ active_class(['tables/basic-table']) }}">
      <a class="nav-link" href="{{ url('/tables/basic-table') }}">
        <i class="menu-icon mdi mdi-table-large"></i>
        <span class="menu-title">Tables</span>
      </a>
    </li>
   <li class="nav-item {{ active_class(['user-pages/*']) }}">
  <a class="nav-link" data-toggle="collapse" href="#user-pages" aria-expanded="{{ is_active_route(['user-pages/*']) }}" aria-controls="user-pages">
    <i class="menu-icon mdi mdi-calendar"></i> <!-- changed icon here -->
    <span class="menu-title">Events</span>
    <i class="menu-arrow"></i>
  </a>
  <div class="collapse {{ show_class(['user-pages/*']) }}" id="user-pages">
    <ul class="nav flex-column sub-menu">
      <li class="nav-item {{ active_class(['user-pages/login']) }}">
        <a class="nav-link" href="{{ url('/participations') }}">Participation</a>
      </li>
      <li class="nav-item {{ active_class(['user-pages/register']) }}">
        <a class="nav-link" href="{{ url('/challenges') }}">Challenge</a>
      </li>
    </ul>
  </div>
</li>

    <li class="nav-item {{ active_class(['user-pages/*']) }}">
      <a class="nav-link" data-toggle="collapse" href="#user-pages" aria-expanded="{{ is_active_route(['user-pages/*']) }}" aria-controls="user-pages">
        <i class="menu-icon mdi mdi-lock-outline"></i>
        <span class="menu-title">User Pages</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse {{ show_class(['user-pages/*']) }}" id="user-pages">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item {{ active_class(['user-pages/login']) }}">
            <a class="nav-link" href="{{ url('/user-pages/login') }}">Login</a>
          </li>
          <li class="nav-item {{ active_class(['user-pages/register']) }}">
            <a class="nav-link" href="{{ url('/user-pages/register') }}">Register</a>
          </li>
          <li class="nav-item {{ active_class(['user-pages/lock-screen']) }}">
            <a class="nav-link" href="{{ url('/user-pages/lock-screen') }}">Lock Screen</a>
          </li>
        </ul>                                 
      </div>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="https://www.bootstrapdash.com/demo/star-laravel-free/documentation/documentation.html" target="_blank">
        <i class="menu-icon mdi mdi-file-outline"></i>
        <span class="menu-title">Documentation</span>
      </a>
    </li>
  </ul>
</nav>