<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center fw-bold" href="{{ route('beranda') }}">
            <i class="bi bi-mortarboard-fill me-2 text-primary"></i>
            {{ site_name() }}
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <i class="bi bi-list fs-4"></i>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link py-2 px-3 {{ request()->routeIs('beranda') ? 'active fw-bold text-primary' : '' }}" href="{{ route('beranda') }}">
                        <i class="bi bi-house-fill me-2 d-lg-none"></i>Beranda
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link py-2 px-3 {{ request()->routeIs('tentang') ? 'active fw-bold text-primary' : '' }}" href="{{ route('tentang') }}">
                        <i class="bi bi-info-circle-fill me-2 d-lg-none"></i>Tentang
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link py-2 px-3 {{ request()->routeIs('kegiatan') ? 'active fw-bold text-primary' : '' }}" href="{{ route('kegiatan') }}">
                        <i class="bi bi-calendar-event-fill me-2 d-lg-none"></i>Kegiatan
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link py-2 px-3 {{ request()->routeIs('organisasi') ? 'active fw-bold text-primary' : '' }}" href="{{ route('organisasi') }}">
                        <i class="bi bi-people-fill me-2 d-lg-none"></i>Organisasi
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link py-2 px-3 {{ request()->routeIs('blog') ? 'active fw-bold text-primary' : '' }}" href="{{ route('blog') }}">
                        <i class="bi bi-journal-text me-2 d-lg-none"></i>Blog
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link py-2 px-3 {{ request()->routeIs('fasilitas*') ? 'active fw-bold text-primary' : '' }}" href="{{ route('fasilitas') }}">
                        <i class="bi bi-building me-2 d-lg-none"></i>Fasilitas
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link py-2 px-3 {{ request()->routeIs('kontak') ? 'active fw-bold text-primary' : '' }}" href="{{ route('kontak') }}">
                        <i class="bi bi-envelope-fill me-2 d-lg-none"></i>Kontak
                    </a>
                </li>
                @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle px-3 d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle me-2"></i>
                            <span class="d-lg-inline d-none">{{ Auth::user()->name }}</span>
                            <span class="d-lg-none">Profile</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                            <li><h6 class="dropdown-header text-muted">{{ Auth::user()->email }}</h6></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item py-2" href="{{ route('profile.show') }}">
                                    <i class="bi bi-person me-2 text-primary"></i>Profile Saya
                                </a>
                            </li>
                            @if(Auth::user()->isAdmin())
                                <li>
                                    <a class="dropdown-item py-2" href="{{ route('admin.dashboard') }}">
                                        <i class="bi bi-speedometer2 me-2 text-success"></i>Dashboard Admin
                                    </a>
                                </li>
                            @endif
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item py-2 text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item mt-2 mt-lg-0">
                        <a class="btn btn-primary w-100 w-lg-auto ms-lg-3" href="{{ route('login') }}">
                            <i class="bi bi-pencil-square me-1"></i>Daftar Sekarang
                        </a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
