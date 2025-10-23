{{-- resources/views/profile.blade.php --}}
@extends('layout.master')

@section('title', 'User Profile')

@section('content')
<div class="main-content">
    <!-- Top Navbar -->
    <nav class="navbar navbar-top navbar-expand-md navbar-dark" id="navbar-main">
        <div class="container-fluid">
            <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block" href="#">User Profile</a>
            
            <!-- Search Form -->
            <form class="navbar-search navbar-search-dark form-inline mr-3 d-none d-md-flex ml-lg-auto">
                <div class="form-group mb-0">
                    <div class="input-group input-group-alternative">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                        </div>
                        <input class="form-control" placeholder="Search" type="text">
                    </div>
                </div>
            </form>

            <!-- User Menu -->
            <ul class="navbar-nav align-items-center d-none d-md-flex">
                <li class="nav-item dropdown">
                    <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <div class="media align-items-center">
                            <span class="avatar avatar-sm rounded-circle">
                                <img alt="User Image" src="{{ asset('images/team-4.jpg') }}">
                            </span>
                            <div class="media-body ml-2 d-none d-lg-block">
                                <span class="mb-0 text-sm font-weight-bold">{{ Auth::user()->name ?? 'Guest' }}</span>
                            </div>
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
                        <div class="dropdown-header noti-title">
                            <h6 class="text-overflow m-0">Welcome!</h6>
                        </div>
                        <a href="{{ route('profile.show') }}" class="dropdown-item">
                            <i class="ni ni-single-02"></i> My profile
                        </a>
                        <a href="{{ route('settings') }}" class="dropdown-item">
                            <i class="ni ni-settings-gear-65"></i> Settings
                        </a>
                        <a href="#" class="dropdown-item">
                            <i class="ni ni-calendar-grid-58"></i> Activity
                        </a>
                        <a href="#" class="dropdown-item">
                            <i class="ni ni-support-16"></i> Support
                        </a>
                        <div class="dropdown-divider"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item">
                                <i class="ni ni-user-run"></i> Logout
                            </button>
                        </form>
                    </div>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Header -->
    <div class="header pb-8 pt-5 pt-lg-8 d-flex align-items-center position-relative" 
         style="min-height: 500px; background-image: url('{{ asset('images/profile-cover.jpg') }}'); background-size: cover; background-position: center top;">
        <div style="position:absolute; top:0; left:0; width:100%; height:100%; background-color: rgba(0,0,0,0.6);"></div>
        <div class="container-fluid d-flex align-items-center position-relative">
            <div class="row">
                <div class="col-lg-7 col-md-10 text-white">
                    <h1 class="display-2">Hello {{ Auth::user()->name ?? 'Guest' }}</h1>
                    <p class="mt-0 mb-4">Welcome to your profile page. You can update your personal information below.</p>
                    <a href="{{ route('profile.edit') }}" class="btn btn-primary">Edit Profile</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Page Content -->
    <div class="container-fluid mt--7">
        <div class="row">
            <!-- Profile Card -->
            <div class="col-xl-4 order-xl-2 mb-5 mb-xl-0">
                <div class="card card-profile shadow">
                    <div class="row justify-content-center">
                        <div class="col-lg-3 order-lg-2">
                            <div class="card-profile-image">
                                <a href="#">
                                    <img src="{{ asset('images/team-4.jpg') }}" class="rounded-circle shadow">
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-header text-center border-0 pt-8 pt-md-4 pb-0 pb-md-4">
                        <div class="d-flex justify-content-between">
                            <a href="#" class="btn btn-primary btn-sm mr-4">Connect</a>
                            <a href="#" class="btn btn-secondary btn-sm">Message</a>
                        </div>
                    </div>
                    <div class="card-body pt-0 pt-md-4 text-center">
                        <div class="card-profile-stats d-flex justify-content-center mt-md-5 mb-4">
                            <div>
                                <span class="heading">22</span>
                                <span class="description">Friends</span>
                            </div>
                            <div>
                                <span class="heading">10</span>
                                <span class="description">Photos</span>
                            </div>
                            <div>
                                <span class="heading">89</span>
                                <span class="description">Comments</span>
                            </div>
                        </div>
                        <h3>{{ Auth::user()->name ?? 'Jessica Jones' }}<span class="font-weight-light">, 27</span></h3>
                        <div class="h5 font-weight-300 mb-2">
                            <i class="ni location_pin mr-2"></i>Bucharest, Romania
                        </div>
                        <div class="h5 font-weight-300 mb-2">
                            <i class="ni business_briefcase-24 mr-2"></i>Solution Manager
                        </div>
                        <div class="h5 font-weight-300 mb-4">
                            <i class="ni education_hat mr-2"></i>University of Computer Science
                        </div>
                        <p>A short description about the user goes here...</p>
                        <a href="#">Show more</a>
                    </div>
                </div>
            </div>

            <!-- Account Form -->
            <div class="col-xl-8 order-xl-1">
                <div class="card bg-secondary shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">My Account</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('settings') }}" class="btn btn-primary btn-sm">Settings</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('profile.update') }}">
                            @csrf
                            @method('PUT')

                            <h6 class="heading-small text-muted mb-4">User Information</h6>
                            <div class="pl-lg-4 mb-4">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group focused">
                                            <label for="input-username">Username</label>
                                            <input type="text" id="input-username" name="username" class="form-control form-control-alternative" value="{{ old('username', Auth::user()->name ?? '') }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group focused">
                                            <label for="input-email">Email Address</label>
                                            <input type="email" id="input-email" name="email" class="form-control form-control-alternative" value="{{ old('email', Auth::user()->email ?? '') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <h6 class="heading-small text-muted mb-4">About Me</h6>
                            <div class="pl-lg-4 mb-4">
                                <div class="form-group focused">
                                    <label>About Me</label>
                                    <textarea rows="4" name="about" class="form-control form-control-alternative">{{ old('about', Auth::user()->about ?? '') }}</textarea>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-success">Save Changes</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="footer bg-light py-3 mt-5">
    <div class="container text-center">
        <span>© 2025 YourCompany. Made with <i class="fas fa-heart text-danger"></i> using <a href="https://www.creative-tim.com/product/argon-dashboard" target="_blank">Argon Dashboard</a>.</span>
    </div>
</footer>
@endsection
