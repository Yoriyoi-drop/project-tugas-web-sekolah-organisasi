{{-- Admin Sidebar --}}
<div class="sidebar bg-dark text-white" style="width: 250px; min-height: 100vh; position: fixed; top: 0; left: 0; z-index: 1000;">
    <div class="sidebar-header p-3 border-bottom border-secondary">
        <h5 class="mb-0">
            <i class="bi bi-speedometer2 me-2"></i>Admin Panel
        </h5>
    </div>
    
    <nav class="sidebar-nav p-0">
        <ul class="nav flex-column">
            <!-- Dashboard -->
            <li class="nav-item">
                <a class="nav-link text-white d-flex align-items-center py-3 px-3 {{ request()->routeIs('admin.dashboard') ? 'bg-primary' : '' }}" 
                   href="{{ route('admin.dashboard') }}">
                    <i class="bi bi-house-door me-3"></i>Dashboard
                </a>
            </li>
            
            <!-- Content Management -->
            <li class="nav-item">
                <a class="nav-link text-white d-flex align-items-center py-3 px-3" 
                   data-bs-toggle="collapse" href="#contentMenu" role="button">
                    <i class="bi bi-file-text me-3"></i>Konten
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <div class="collapse {{ request()->routeIs('admin.posts.*') || request()->routeIs('admin.activities.*') ? 'show' : '' }}" id="contentMenu">
                    <ul class="nav flex-column ms-3">
                        <li class="nav-item">
                            <a class="nav-link text-white-50 py-2 px-3 {{ request()->routeIs('admin.posts.*') ? 'text-white bg-secondary' : '' }}" 
                               href="{{ route('admin.posts.index') }}">
                                <i class="bi bi-journal-text me-2"></i>Blog Posts
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50 py-2 px-3 {{ request()->routeIs('admin.activities.*') ? 'text-white bg-secondary' : '' }}" 
                               href="{{ route('admin.activities.index') }}">
                                <i class="bi bi-calendar-event me-2"></i>Kegiatan
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            
            <!-- Organization Management -->
            <li class="nav-item">
                <a class="nav-link text-white d-flex align-items-center py-3 px-3" 
                   data-bs-toggle="collapse" href="#orgMenu" role="button">
                    <i class="bi bi-people me-3"></i>Organisasi
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <div class="collapse {{ request()->routeIs('admin.organizations.*') || request()->routeIs('admin.students.*') || request()->routeIs('admin.teachers.*') ? 'show' : '' }}" id="orgMenu">
                    <ul class="nav flex-column ms-3">
                        <li class="nav-item">
                            <a class="nav-link text-white-50 py-2 px-3 {{ request()->routeIs('admin.organizations.*') ? 'text-white bg-secondary' : '' }}" 
                               href="{{ route('admin.organizations.index') }}">
                                <i class="bi bi-diagram-3 me-2"></i>Kelola Organisasi
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50 py-2 px-3 {{ request()->routeIs('admin.students.*') ? 'text-white bg-secondary' : '' }}" 
                               href="{{ route('admin.students.index') }}">
                                <i class="bi bi-person-badge me-2"></i>Data Siswa
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50 py-2 px-3 {{ request()->routeIs('admin.teachers.*') ? 'text-white bg-secondary' : '' }}" 
                               href="{{ route('admin.teachers.index') }}">
                                <i class="bi bi-person-workspace me-2"></i>Data Guru
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            
            <!-- Facilities -->
            <li class="nav-item">
                <a class="nav-link text-white d-flex align-items-center py-3 px-3 {{ request()->routeIs('admin.facilities.*') ? 'bg-primary' : '' }}" 
                   href="{{ route('admin.facilities.index') }}">
                    <i class="bi bi-building me-3"></i>Fasilitas
                </a>
            </li>
            
            <!-- Communication -->
            <li class="nav-item">
                <a class="nav-link text-white d-flex align-items-center py-3 px-3" 
                   data-bs-toggle="collapse" href="#commMenu" role="button">
                    <i class="bi bi-chat-dots me-3"></i>Komunikasi
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <div class="collapse {{ request()->routeIs('admin.messages.*') || request()->routeIs('admin.registrations.*') ? 'show' : '' }}" id="commMenu">
                    <ul class="nav flex-column ms-3">
                        <li class="nav-item">
                            <a class="nav-link text-white-50 py-2 px-3 {{ request()->routeIs('admin.messages.*') ? 'text-white bg-secondary' : '' }}" 
                               href="{{ route('admin.messages.index') }}">
                                <i class="bi bi-envelope me-2"></i>Pesan Masuk
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50 py-2 px-3 {{ request()->routeIs('admin.registrations.*') ? 'text-white bg-secondary' : '' }}" 
                               href="{{ route('admin.registrations.index') }}">
                                <i class="bi bi-person-plus me-2"></i>Pendaftaran
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            
            <!-- Statistics -->
            <li class="nav-item">
                <a class="nav-link text-white d-flex align-items-center py-3 px-3 {{ request()->routeIs('admin.statistics.*') ? 'bg-primary' : '' }}" 
                   href="{{ route('admin.statistics.index') }}">
                    <i class="bi bi-bar-chart me-3"></i>Statistik
                </a>
            </li>
            
            <!-- Settings -->
            <li class="nav-item">
                <a class="nav-link text-white d-flex align-items-center py-3 px-3 {{ request()->routeIs('admin.settings.*') ? 'bg-primary' : '' }}" 
                   href="{{ route('admin.settings.index') }}">
                    <i class="bi bi-gear me-3"></i>Pengaturan
                </a>
            </li>
            
            <!-- Divider -->
            <li class="nav-item">
                <hr class="border-secondary mx-3">
            </li>
            
            <!-- Back to Site -->
            <li class="nav-item">
                <a class="nav-link text-white d-flex align-items-center py-3 px-3" 
                   href="{{ route('home') }}" target="_blank">
                    <i class="bi bi-box-arrow-up-right me-3"></i>Lihat Website
                </a>
            </li>
            
            <!-- Logout -->
            <li class="nav-item">
                <form method="POST" action="{{ route('logout') }}" class="d-inline w-100">
                    @csrf
                    <button type="submit" class="nav-link text-white d-flex align-items-center py-3 px-3 w-100 border-0 bg-transparent text-start">
                        <i class="bi bi-box-arrow-right me-3"></i>Logout
                    </button>
                </form>
            </li>
        </ul>
    </nav>
</div>

<style>
.sidebar .nav-link:hover {
    background-color: rgba(255, 255, 255, 0.1) !important;
}

.sidebar .collapse .nav-link:hover {
    background-color: rgba(255, 255, 255, 0.05) !important;
}

.sidebar .nav-link {
    transition: all 0.3s ease;
    border: none;
}

.sidebar .nav-link.active {
    background-color: #0d6efd !important;
}
</style>
