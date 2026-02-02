<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', site_name())</title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="@yield('meta_description', 'MA NU Nusantara - Membentuk generasi santri yang berakhlak mulia, cerdas, dan siap menghadapi tantangan zaman.')">
    <meta name="keywords" content="MA NU Nusantara, Madrasah Aliyah, Sekolah Islam, PPDB 2024, Pendidikan Karakter">
    <meta name="author" content="MA NU Nusantara">
    
    <!-- PWA Meta Tags -->
    <meta name="theme-color" content="#0d6efd">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="SekolahOrg">
    <meta name="application-name" content="SekolahOrg">
    <meta name="msapplication-TileColor" content="#0d6efd">
    <meta name="msapplication-config" content="/browserconfig.xml">
    
    <!-- PWA Manifest -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    
    <!-- Apple Touch Icons -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('icons/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('icons/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('icons/favicon-16x16.png') }}">
    <link rel="mask-icon" href="{{ asset('icons/safari-pinned-tab.svg') }}" color="#0d6efd">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', site_name())">
    <meta property="og:description" content="@yield('meta_description', 'MA NU Nusantara - Membentuk generasi santri yang berakhlak mulia, cerdas, dan siap menghadapi tantangan zaman.')">
    <meta property="og:image" content="{{ asset('images/og-image.png') }}">

    <!-- Preload critical resources -->
    <link rel="preload" href="{{ asset('css/bootstrap.min.css') }}" as="style">
    <link rel="preload" href="{{ asset('css/site.css') }}" as="style">
    <link rel="preload" href="{{ asset('css/fonts-google.css')}}" as="style">

    <!-- Critical CSS -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/site.css') }}" rel="stylesheet">

    <!-- Non-critical CSS -->
    <link href="{{ asset('css/fonts-google.css') }}" rel="stylesheet" media="print" onload="this.media='all'">
    <link href="{{ asset('css/bootstrap-icons-npm/bootstrap-icons.css') }}" rel="stylesheet" media="print" onload="this.media='all'">
    <link href="{{ asset('css/fontawesome/all.min.css') }}" rel="stylesheet" media="print" onload="this.media='all'">

    @stack('styles')
    @yield('css')
    
    <!-- Vite Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Base responsive styles */
        body { 
            padding-top: 72px; 
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        .container { max-width: 1200px; padding: 0 15px; }

        /* Mobile-first improvements */
        .navbar-dark .navbar-toggler { 
            border: none !important; 
            box-shadow: none !important; 
            color: white; 
            padding: 0.25rem 0.5rem;
        }
        .navbar-toggler:focus { box-shadow: none !important; }
        
        /* Mobile navigation improvements */
        .mobile-nav-btn {
            display: none;
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: #0d6efd;
            color: white;
            border: none;
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
            z-index: 1000;
            transition: all 0.3s ease;
        }
        
        .mobile-nav-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(13, 110, 253, 0.4);
        }
        
        /* Mobile bottom navigation */
        .mobile-bottom-nav {
            display: none;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            border-top: 1px solid #e9ecef;
            padding: 8px 0;
            z-index: 999;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
        }
        
        .mobile-bottom-nav .nav-link {
            color: #6c757d;
            text-align: center;
            padding: 8px;
            font-size: 0.75rem;
            transition: color 0.3s ease;
        }
        
        .mobile-bottom-nav .nav-link.active,
        .mobile-bottom-nav .nav-link:hover {
            color: #0d6efd;
        }
        
        .mobile-bottom-nav .nav-link i {
            display: block;
            font-size: 1.25rem;
            margin-bottom: 2px;
        }

        @media (max-width: 991.98px) {
            .navbar-collapse {
                background: linear-gradient(135deg, rgba(15, 118, 110, 0.95) 0%, rgba(20, 184, 166, 0.95) 100%);
                backdrop-filter: blur(10px);
                -webkit-backdrop-filter: blur(10px);
                margin: 0.5rem -1rem 0;
                padding: 1.5rem;
                border-radius: 1rem;
                box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3);
                max-height: 85vh;
                overflow-y: auto;
                border: 1px solid rgba(255, 255, 255, 0.1);
            }
            .navbar-nav .nav-link {
                padding: 0.75rem 1rem !important;
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            }
            .navbar-nav .nav-link:last-child {
                border-bottom: none;
            }
            .dropdown-menu {
                background: rgba(255, 255, 255, 0.1) !important;
                border: none !important;
                margin-top: 0.5rem;
            }
            .dropdown-item {
                color: white !important;
            }
            .dropdown-item:hover {
                background: rgba(255, 255, 255, 0.2) !important;
            }
        }

        /* Typography responsive */
        .display-1 { font-size: clamp(2.5rem, 8vw, 5rem); }
        .display-2 { font-size: clamp(2rem, 6vw, 4.5rem); }
        .display-3 { font-size: clamp(1.75rem, 5vw, 4rem); }
        .display-4 { font-size: clamp(1.5rem, 4vw, 3.5rem); }
        .display-5 { font-size: clamp(1.25rem, 3vw, 3rem); }
        .h1, h1 { font-size: clamp(1.75rem, 4vw, 2.5rem); }
        .h2, h2 { font-size: clamp(1.5rem, 3.5vw, 2rem); }
        .h3, h3 { font-size: clamp(1.25rem, 3vw, 1.75rem); }
        .lead { font-size: clamp(1rem, 2.5vw, 1.25rem); }

        /* Page sections responsive */
        .page-header { padding: clamp(2rem, 5vw, 5rem) 0; }
        .hero-section { padding: clamp(3rem, 8vw, 6rem) 0; }
        .py-5 { padding: clamp(2rem, 5vw, 3rem) 0 !important; }
        .mb-5 { margin-bottom: clamp(2rem, 5vw, 3rem) !important; }

        /* Cards and components */
        .card { margin-bottom: 1rem; }
        .card-body { padding: clamp(1rem, 3vw, 2rem); }
        .btn { min-height: 44px; padding: 0.75rem 1.5rem; }

        /* Mobile styles supplement */
        @media (max-width: 575.98px) {
            .btn { font-size: 0.9rem; padding: 0.5rem 1rem; }
            .org-detail-card { padding: 1.5rem; }
            .btn-group .btn { padding: 0.25rem 0.5rem; font-size: 0.75rem; }
        }

        /* Form responsive */
        .form-control, .form-select {
            min-height: 44px;
            font-size: 16px !important;
            padding: 0.75rem 1rem;
        }

        /* Prevent zoom on iOS */
        @media screen and (max-width: 767px) {
            .form-control, .form-select, input, textarea {
                font-size: 16px !important;
            }
        }

        .form-label {
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        /* Table responsive */
        .table-responsive {
            border-radius: 0.375rem;
            overflow-x: auto;
            box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
        }

        /* Images responsive */
        .img-fluid { max-width: 100%; height: auto; }
        .ratio { position: relative; width: 100%; }
        .ratio::before { display: block; padding-top: var(--bs-aspect-ratio); content: ""; }
        .ratio > * { position: absolute; top: 0; left: 0; width: 100%; height: 100%; }

        /* Better responsive images */
        img { max-width: 100%; height: auto; }
        iframe { max-width: 100%; }

        /* Responsive tables */
        .table-responsive {
            -webkit-overflow-scrolling: touch;
        }

        /* Touch targets */
        .btn, .nav-link, .dropdown-item {
            min-height: 44px;
            display: flex;
            align-items: center;
        }

        /* Accessibility */
        .btn:focus, .form-control:focus {
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }

        /* Print styles */
        @media print {
            .navbar, .btn, .modal { display: none !important; }
            .container { max-width: none !important; padding: 0 !important; }
            body { padding-top: 0 !important; }
        }
        
        /* Mobile-specific styles */
        @media (max-width: 767.98px) {
            .mobile-nav-btn {
                display: flex;
                align-items: center;
                justify-content: center;
            }
            
            .mobile-bottom-nav {
                display: flex;
            }
            
            /* Adjust body padding for mobile bottom nav */
            body {
                padding-bottom: 70px;
            }
            
            /* Mobile card improvements */
            .card {
                margin-bottom: 1rem;
                border-radius: 0.75rem;
            }
            
            /* Mobile button improvements */
            .btn {
                min-height: 48px;
                font-weight: 500;
            }
            
            /* Mobile form improvements */
            .form-control, .form-select {
                min-height: 48px;
                border-radius: 0.5rem;
            }
        }
        
        .glass-morphism {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        .hover-up {
            transition: all 0.3s ease;
        }
        .hover-up:hover {
            transform: translateY(-5px);
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">
    <!-- Navigation -->
    @include('components.navbar')

    <!-- Main Content -->
    <main class="flex-grow-1">
        @yield('content')
    </main>

    <!-- Mobile Bottom Navigation -->
    @if(request()->is('/organisasi*') || request()->is('/'))
    <nav class="mobile-bottom-nav">
        <div class="container">
            <div class="row">
                <div class="col">
                    <a href="{{ route('beranda') }}" class="nav-link {{ request()->routeIs('beranda') ? 'active' : '' }}">
                        <i class="bi bi-house-door"></i>
                        Beranda
                    </a>
                </div>
                <div class="col">
                    <a href="{{ route('organisasi') }}" class="nav-link {{ request()->routeIs('organisasi*') ? 'active' : '' }}">
                        <i class="bi bi-building"></i>
                        Organisasi
                    </a>
                </div>
                <div class="col">
                    <a href="{{ route('kegiatan') }}" class="nav-link {{ request()->routeIs('kegiatan*') ? 'active' : '' }}">
                        <i class="bi bi-calendar-event"></i>
                        Kegiatan
                    </a>
                </div>
                <div class="col">
                    <a href="{{ route('tentang') }}" class="nav-link {{ request()->routeIs('tentang') ? 'active' : '' }}">
                        <i class="bi bi-info-circle"></i>
                        Tentang
                    </a>
                </div>
            </div>
        </div>
    </nav>
    @endif

    <!-- Mobile Navigation Button (Floating Action Button) -->
    <button class="mobile-nav-btn" onclick="window.scrollTo({top: 0, behavior: 'smooth'})">
        <i class="bi bi-arrow-up"></i>
    </button>

    <!-- Footer -->
    @include('components.footer')

    <!-- Bootstrap 5 JS with defer -->
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}" defer></script>
    <script src="{{ asset('js/performance.js') }}" defer></script>

    <!-- Service Worker Registration -->
    <script>
        // Register Service Worker
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('{{ asset('sw.js') }}')
                    .then(registration => {
                        console.log('SW registered: ', registration);
                        
                        // Check for updates
                        registration.addEventListener('updatefound', () => {
                            const newWorker = registration.installing;
                            newWorker.addEventListener('statechange', () => {
                                if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                                    // New content is available
                                    if (confirm('New version available! Reload to update?')) {
                                        window.location.reload();
                                    }
                                }
                            });
                        });
                    })
                    .catch(registrationError => {
                        console.log('SW registration failed: ', registrationError);
                    });
            });
        }

        // PWA Install Prompt
        let deferredPrompt;
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            
            // Show install button (optional)
            const installBtn = document.createElement('button');
            installBtn.textContent = 'Install App';
            installBtn.className = 'btn btn-primary position-fixed';
            installBtn.style.cssText = 'bottom: 80px; right: 20px; z-index: 1001;';
            installBtn.onclick = () => {
                deferredPrompt.prompt();
                deferredPrompt.userChoice.then((choiceResult) => {
                    if (choiceResult.outcome === 'accepted') {
                        console.log('User accepted the install prompt');
                    } else {
                        console.log('User dismissed the install prompt');
                    }
                    deferredPrompt = null;
                    installBtn.remove();
                });
            };
            document.body.appendChild(installBtn);
        });

        // Mobile Navigation Active State
        document.addEventListener('DOMContentLoaded', function() {
            // Set active nav based on current URL
            const currentPath = window.location.pathname;
            const navLinks = document.querySelectorAll('.mobile-bottom-nav .nav-link');
            
            navLinks.forEach(link => {
                if (link.getAttribute('href') === currentPath || 
                    (currentPath.startsWith(link.getAttribute('href')) && link.getAttribute('href') !== '/')) {
                    link.classList.add('active');
                }
            });
            
            // Show/hide scroll to top button
            const scrollBtn = document.querySelector('.mobile-nav-btn');
            window.addEventListener('scroll', () => {
                if (window.pageYOffset > 300) {
                    scrollBtn.style.display = 'flex';
                } else {
                    scrollBtn.style.display = 'none';
                }
            });
        });

        // AOS (Animate on Scroll) Trigger
        document.addEventListener('DOMContentLoaded', function() {
            const observerOptions = {
                threshold: 0.1
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('aos-animate');
                    }
                });
            }, observerOptions);

            document.querySelectorAll('[data-aos]').forEach(el => observer.observe(el));
        });
    </script>

    @stack('scripts')
</body>
</html>
