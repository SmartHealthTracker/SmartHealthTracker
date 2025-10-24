<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">
    <!-- Profile Section -->
    <li class="nav-item nav-profile not-navigation-link">
      <div class="nav-link">
        <div class="user-wrapper d-flex align-items-center">
          <div class="profile-image">
            <img 
              src="{{ Auth::user()->profile_image ?? url('assets/images/faces/face8.jpg') }}" 
              alt="profile image"
              class="rounded-circle"
            >
          </div>
          <div class="text-wrapper ml-3">
            <p class="profile-name mb-0">{{ Auth::user()->name ?? 'Utilisateur' }}</p>
            <div class="dropdown" data-display="static">
              <a href="#" 
                 class="nav-link d-flex align-items-center user-switch-dropdown-toggler p-0" 
                 id="UserSettingsDropdown"
                 data-toggle="dropdown" 
                 aria-expanded="false">
                <small class="designation text-muted">Membre</small>
                <span class="status-indicator online ml-1"></span>
              </a>
              <div class="dropdown-menu" aria-labelledby="UserSettingsDropdown">
                <a class="dropdown-item">Manage Account</a>
                <a class="dropdown-item">Change Password</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                  <i class="mdi mdi-logout mr-2"></i> Sign Out
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                  @csrf
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </li>

    <!-- Dashboard -->
    <li class="nav-item {{ active_class(['/']) }}">
      <a class="nav-link" href="{{ url('/') }}">
        <i class="menu-icon mdi mdi-television"></i>
        <span class="menu-title">Dashboard</span>
      </a>
    </li>

    <!-- Users -->
    <li class="nav-item {{ active_class(['admin/users']) }}">
      <a class="nav-link" href="{{ url('/admin/users') }}">
        <i class="menu-icon mdi mdi-account-multiple"></i>
        <span class="menu-title">Utilisateurs</span>
      </a>
    </li>

    <!-- Smart Health Tracker -->
    <li class="nav-item {{ active_class(['health/*']) }}">
      <a class="nav-link" data-toggle="collapse" href="#healthMenu" aria-expanded="{{ is_active_route(['health/*']) }}" aria-controls="healthMenu">
        <i class="menu-icon mdi mdi-heart-pulse"></i>
        <span class="menu-title">Smart Health Tracker</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse {{ show_class(['health/*']) }}" id="healthMenu">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"><a class="nav-link" href="{{ route('health.index') }}">Dashboard</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('health.logs') }}">Logs</a></li>
        </ul>
      </div>
    </li>

    <!-- Habits -->
    <li class="nav-item {{ active_class(['habits*']) }}">
      <a class="nav-link" data-toggle="collapse" href="#habitsMenu" aria-expanded="{{ is_active_route(['habits*']) }}" aria-controls="habitsMenu">
        <i class="menu-icon mdi mdi-clipboard-check"></i>
        <span class="menu-title">Habits</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse {{ show_class(['habits*']) }}" id="habitsMenu">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"><a class="nav-link" href="{{ route('habits.index') }}">Liste des Habits</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('habits.create') }}">Ajouter un Habit</a></li>
        </ul>
      </div>
    </li>

    <!-- Habit Objectif -->
    <li class="nav-item {{ active_class(['basic-ui/*']) }}">
      <a class="nav-link" data-toggle="collapse" href="#basic-ui" aria-expanded="{{ is_active_route(['basic-ui/*']) }}" aria-controls="basic-ui">
        <i class="menu-icon mdi mdi-dna"></i>
        <span class="menu-title">Habit Objectif</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse {{ show_class(['basic-ui/*']) }}" id="basic-ui">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"><a class="nav-link" href="{{ url('/habitssaif') }}">Objectifs</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ url('/habit-logs') }}">Logs</a></li>
        </ul>
      </div>
    </li>

    <!-- Resources & Comments -->
    <li class="nav-item {{ active_class(['/admin/resources', '/admin/comments']) }}">
      <a class="nav-link" data-toggle="collapse" href="#resourcesComments" aria-expanded="{{ is_active_route(['/admin/resources', '/admin/comments']) }}" aria-controls="resourcesComments">
        <i class="menu-icon mdi mdi-folder-multiple"></i>
        <span class="menu-title">Resources & Comments</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse {{ show_class(['/admin/resources', '/admin/comments']) }}" id="resourcesComments">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"><a class="nav-link" href="{{ url('/admin/resources') }}">Resources</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ url('/admin/comments') }}">Comments</a></li>
        </ul>
      </div>
    </li>

    <!-- Events -->
    <li class="nav-item {{ active_class(['user-pages/*']) }}">
      <a class="nav-link" data-toggle="collapse" href="#user-pages" aria-expanded="{{ is_active_route(['user-pages/*']) }}" aria-controls="user-pages">
        <i class="menu-icon mdi mdi-calendar"></i>
        <span class="menu-title">Events</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse {{ show_class(['user-pages/*']) }}" id="user-pages">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"><a class="nav-link" href="{{ url('/cha-parti-dashboard') }}">Overview</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ url('/participations') }}">Participation</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ url('/challenges') }}">Challenge</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ url('/challenges/calendar') }}">Calendar</a></li>
        </ul>
      </div>
    </li>

    <!-- Activities -->
    <li class="nav-item {{ active_class(['activities/*', 'activity-logs/*']) }}">
      <a class="nav-link" data-toggle="collapse" href="#activitiesMenu" aria-expanded="{{ is_active_route(['activities/*', 'activity-logs/*']) }}" aria-controls="activitiesMenu">
        <i class="menu-icon mdi mdi-run"></i>
        <span class="menu-title">Activities</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse {{ show_class(['activities/*', 'activity-logs/*']) }}" id="activitiesMenu">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"><a class="nav-link" href="{{ route('activities.index') }}">Activities</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('activity_logs.index') }}">Activity Logs</a></li>
        </ul>
      </div>
    </li>
  </ul>
</nav>
