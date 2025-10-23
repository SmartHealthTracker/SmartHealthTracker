<nav class="sidebar sidebar-offcanvas dynamic-active-class-disabled" id="sidebar">
  <ul class="nav">
    <li class="nav-item nav-profile not-navigation-link">
      <div class="nav-link">
        <div class="user-wrapper">
          <div class="profile-image">
            <img src="{{ url('assets/images/faces/face8.jpg') }}" alt="profile image">
          </div>
          <div class="text-wrapper">
            <p class="profile-name">Richard V.Welsh</p>
            <div class="dropdown" data-display="static">
              <a href="#" class="nav-link d-flex user-switch-dropdown-toggler" id="UsersettingsDropdown" href="#" data-toggle="dropdown" aria-expanded="false">
                <small class="designation text-muted">Manager</small>
                <span class="status-indicator online"></span>
              </a>
              <div class="dropdown-menu" aria-labelledby="UsersettingsDropdown">
                <a class="dropdown-item p-0">
                  <div class="d-flex border-bottom">
                    <div class="py-3 px-4 d-flex align-items-center justify-content-center">
                      <i class="mdi mdi-bookmark-plus-outline mr-0 text-gray"></i>
                    </div>
                    <div class="py-3 px-4 d-flex align-items-center justify-content-center border-left border-right">
                      <i class="mdi mdi-account-outline mr-0 text-gray"></i>
                    </div>
                    <div class="py-3 px-4 d-flex align-items-center justify-content-center">
                      <i class="mdi mdi-alarm-check mr-0 text-gray"></i>
                    </div>
                  </div>
                </a>
                <a class="dropdown-item mt-2"> Manage Accounts </a>
                <a class="dropdown-item"> Change Password </a>
                <a class="dropdown-item"> Check Inbox </a>
                <a class="dropdown-item"> Sign Out </a>
              </div>
            </div>
          </div>
        </div>
        <button class="btn btn-success btn-block">New Project <i class="mdi mdi-plus"></i>
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
<li class="nav-item {{ active_class(['health/*']) }}">
  <a class="nav-link" data-toggle="collapse" href="#healthMenu" aria-expanded="{{ is_active_route(['health/*']) }}" aria-controls="healthMenu">
    <i class="menu-icon mdi mdi-heart-pulse"></i>
    <span class="menu-title">Smart Health Tracker</span>
    <i class="menu-arrow"></i>
  </a>
  <div class="collapse {{ show_class(['health/*']) }}" id="healthMenu">
    <ul class="nav flex-column sub-menu">
      <li class="nav-item {{ active_class(['health']) }}">
        <a class="nav-link" href="{{ route('health.index') }}">Dashboard</a>
      </li>
      <li class="nav-item {{ active_class(['health/logs']) }}">
        <a class="nav-link" href="{{ route('health.logs') }}">Logs</a>
      </li>
    </ul>
  </div>
</li>


   {{-- Lien vers les habitudes --}}
    <li class="nav-item {{ active_class(['habits*']) }}">
        <a class="nav-link" data-toggle="collapse" href="#habitsMenu" aria-expanded="{{ is_active_route(['habits*']) }}" aria-controls="habitsMenu">
        <i class="menu-icon mdi mdi-clipboard-check"></i>
        <span class="menu-title">Habits</span>
        <i class="menu-arrow"></i>
         </a>
    <div class="collapse {{ show_class(['habits*']) }}" id="habitsMenu">
        <ul class="nav flex-column sub-menu">
            <li class="nav-item {{ active_class(['habits']) }}">
                <a class="nav-link" href="{{ route('habits.index') }}">Liste des Habits</a>
            </li>
            <li class="nav-item {{ active_class(['habits/create']) }}">
                <a class="nav-link" href="{{ route('habits.create') }}">Ajouter un Habit</a>
            </li>
        </ul>
    </div>
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
    <span class="menu-title">Habit Objectif</span>
    <i class="menu-arrow"></i>
  </a>
  <div class="collapse {{ show_class(['basic-ui/*']) }}" id="basic-ui">
    <ul class="nav flex-column sub-menu">
      <li class="nav-item {{ active_class(['habitssaif']) }}">
        <a class="nav-link" href="{{ url('/habitssaif') }}">Objectifs</a>
      </li>
      <li class="nav-item {{ active_class(['habit-logs']) }}">
        <a class="nav-link" href="{{ url('/habit-logs') }}">Logs</a>
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
        <a class="nav-link" href="{{ url('/cha-parti-dashboard') }}">OverView</a>
      </li>
      <li class="nav-item {{ active_class(['user-pages/login']) }}">
        <a class="nav-link" href="{{ url('/participations') }}">Participation</a>
      </li>
      <li class="nav-item {{ active_class(['user-pages/register']) }}">
        <a class="nav-link" href="{{ url('/challenges') }}">Challenge</a>
      </li>
      <li class="nav-item {{ active_class(['user-pages/register']) }}">
        <a class="nav-link" href="{{ url('/challenges/calendar') }}">Calendar</a>
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
          <li class="nav-item {{ active_class(['user-pages/deleteusers']) }}">
            <a class="nav-link" href="{{ url('/user-pages/deleteusers') }}"> Users</a>
          </li>
        </ul>
      </div>
    </li>
    <li class="nav-item {{ active_class(['activities/*']) }}">
      <a class="nav-link" data-toggle="collapse" href="#activities" aria-expanded="{{ is_active_route(['activities/*']) }}" aria-controls="activities">
        <i class="menu-icon mdi mdi-dna"></i>
        <span class="menu-title">Activities</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse {{ show_class(['activities/*']) }}" id="activities">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item {{ active_class(['activities']) }}">
            <a class="nav-link" href="{{ route('activities.index') }}">Activities</a>
          </li>
        </ul>
      </div>
    </li>  
    <li class="nav-item {{ active_class(['activity-logs/*']) }}">
  <a class="nav-link" data-toggle="collapse" href="#activity-logs" aria-expanded="{{ is_active_route(['activity-logs/*']) }}" aria-controls="activity-logs">
    <i class="menu-icon mdi mdi-notebook"></i>
    <span class="menu-title">Activity Logs</span>
    <i class="menu-arrow"></i>
  </a>
  <div class="collapse {{ show_class(['activity-logs/*']) }}" id="activity-logs">
    <ul class="nav flex-column sub-menu">
      <li class="nav-item {{ active_class(['activity-logs']) }}">
        <a class="nav-link" href="{{ route('activity_logs.index') }}">Activity Logs</a>
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
