<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - MA NU Nusantara</title>
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .sidebar { min-height: calc(100vh - 56px); }
        .border-left-primary { border-left: 4px solid #007bff !important; }
        .border-left-success { border-left: 4px solid #28a745 !important; }
        .border-left-warning { border-left: 4px solid #ffc107 !important; }
        .border-left-info { border-left: 4px solid #17a2b8 !important; }
        .icon-circle { width: 2rem; height: 2rem; border-radius: 50%; display: flex; align-items: center; justify-content: center; }
        .text-gray-300 { color: #dddfeb !important; }
        .text-gray-500 { color: #858796 !important; }
        .text-gray-800 { color: #5a5c69 !important; }
        .font-weight-bold { font-weight: 700 !important; }
        .text-xs { font-size: 0.7rem; }
        .btn-block { width: 100%; }
        .shadow { box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important; }

        /* Mobile responsive */
        @media (max-width: 767.98px) {
            .sidebar { display: none; }
            .col-md-10 { flex: 0 0 100%; max-width: 100%; }
            .table-responsive { font-size: 0.875rem; }
            .btn-group .btn { padding: 0.25rem 0.4rem; font-size: 0.75rem; }
            .card-body { padding: 1rem; }
        }

        @media (min-width: 768px) and (max-width: 991.98px) {
            .col-md-2 { flex: 0 0 25%; max-width: 25%; }
            .col-md-10 { flex: 0 0 75%; max-width: 75%; }
        }
    </style>
    @yield('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-gradient-primary" style="background: linear-gradient(135deg, #0f766e 0%, #14b8a6 100%) !important;">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('admin.dashboard') }}">
                <i class="bi bi-mortarboard-fill me-2"></i>
                <span>MA NU Admin</span>
            </a>
            <div class="d-flex align-items-center">
                <span class="text-white me-3">Welcome, {{ auth()->user()->name }}</span>
                <a class="btn btn-outline-light btn-sm me-2" href="{{ route('beranda') }}" target="_blank">
                    <i class="bi bi-eye me-1"></i>View Site
                </a>
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button class="btn btn-light btn-sm">
                        <i class="bi bi-box-arrow-right me-1"></i>Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-2 d-md-block bg-white sidebar shadow-sm">
                <div class="position-sticky pt-3">
                    <div class="mb-3 px-3">
                        <h6 class="text-muted text-uppercase font-weight-bold">Main Menu</h6>
                    </div>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active bg-primary text-white' : 'text-dark' }}" href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-speedometer2 me-2"></i>Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.posts.*') ? 'active bg-primary text-white' : 'text-dark' }}" href="{{ route('admin.posts.index') }}">
                                <i class="bi bi-file-text me-2"></i>Posts
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.organizations.*') ? 'active bg-primary text-white' : 'text-dark' }}" href="{{ route('admin.organizations.index') }}">
                                <i class="bi bi-people me-2"></i>Organizations
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.activities.*') ? 'active bg-primary text-white' : 'text-dark' }}" href="{{ route('admin.activities.index') }}">
                                <i class="bi bi-calendar-event me-2"></i>Activities
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.statistics.*') ? 'active bg-primary text-white' : 'text-dark' }}" href="{{ route('admin.statistics.index') }}">
                                <i class="bi bi-bar-chart me-2"></i>Statistics
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.facilities.*') ? 'active bg-primary text-white' : 'text-dark' }}" href="{{ route('admin.facilities.index') }}">
                                <i class="bi bi-building me-2"></i>Facilities
                            </a>
                        </li>
                    </ul>

                    <div class="mt-4 mb-3 px-3">
                        <h6 class="text-muted text-uppercase font-weight-bold">Management</h6>
                    </div>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.students.*') ? 'active bg-primary text-white' : 'text-dark' }}" href="{{ route('admin.students.index') }}">
                                <i class="bi bi-mortarboard me-2"></i>Students
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.teachers.*') ? 'active bg-primary text-white' : 'text-dark' }}" href="{{ route('admin.teachers.index') }}">
                                <i class="bi bi-person-badge me-2"></i>Teachers
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active bg-primary text-white' : 'text-dark' }}" href="{{ route('admin.users.index') }}">
                                <i class="bi bi-people-fill me-2"></i>Users
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.messages.*') ? 'active bg-primary text-white' : 'text-dark' }}" href="{{ route('admin.messages.index') }}">
                                <i class="bi bi-envelope me-2"></i>Messages
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.registrations.*') ? 'active bg-primary text-white' : 'text-dark' }}" href="{{ route('admin.registrations.index') }}">
                                <i class="bi bi-person-plus me-2"></i>Registrations
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active bg-primary text-white' : 'text-dark' }}" href="{{ route('admin.settings.index') }}">
                                <i class="bi bi-gear me-2"></i>Settings
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="col-md-10 ms-sm-auto px-md-4" style="background-color: #f8f9fc;">
                <div class="d-sm-flex align-items-center justify-content-between mb-4 pt-3">
                    <h1 class="h3 mb-0 text-gray-800">@yield('title', 'Dashboard')</h1>
                    <div class="d-none d-sm-inline-block">
                        <span class="text-muted">{{ now()->format('l, F j, Y') }}</span>
                    </div>
                </div>
                @yield('content')
            </main>
        </div>
    </div>

    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
