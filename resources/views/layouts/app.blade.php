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
        body { padding-top: 72px; }
        .container { max-width: 1200px; padding: 0 15px; }

        /* Navbar responsive */
        .navbar-dark .navbar-toggler { border: none !important; box-shadow: none !important; color: white; }
        .navbar-toggler:focus { box-shadow: none !important; }

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

    <!-- Footer -->
    @include('components.footer')

    <!-- Bootstrap 5 JS with defer -->
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}" defer></script>
    <script src="{{ asset('js/performance.js') }}" defer></script>

    <script>
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
