<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', '{{ site_name() }}')</title>

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
    <style>
        /* Base responsive styles */
        body { padding-top: 0; }
        .container { max-width: 100%; padding: 0 15px; }

        /* Navbar responsive */
        .navbar-toggler { border: none !important; box-shadow: none !important; }
        .navbar-toggler:focus { box-shadow: none !important; }

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

        /* Organization cards responsive */
        .org-detail-card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
            padding: clamp(1.5rem, 4vw, 2.5rem);
            margin-bottom: 2rem;
        }

        .org-logo i {
            font-size: clamp(2.5rem, 6vw, 4rem);
            margin-bottom: 1rem;
        }

        .org-name {
            font-size: clamp(1.5rem, 4vw, 2rem);
            margin-bottom: 0.5rem;
        }

        .leadership-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .leader-card {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 0.5rem;
            text-align: center;
        }

        /* Mobile styles */
        @media (max-width: 575.98px) {
            .container { padding: 0 10px; }
            .btn { font-size: 0.9rem; padding: 0.5rem 1rem; }
            .card-body { padding: 1rem; }
            .org-detail-card { padding: 1.5rem; }
            .leadership-grid { grid-template-columns: 1fr; }
            .btn-group .btn { padding: 0.25rem 0.5rem; font-size: 0.75rem; }
            .table-responsive table { font-size: 0.875rem; }
            .modal-dialog { margin: 0.5rem; }
            .offcanvas { width: 85vw; }
        }

        /* Tablet styles */
        @media (min-width: 576px) and (max-width: 991.98px) {
            .navbar-collapse {
                background: white;
                border-top: 1px solid #dee2e6;
                margin-top: 1rem;
                padding-top: 1rem;
            }
            .leadership-grid { grid-template-columns: repeat(2, 1fr); }
        }

        /* Form responsive */
        .form-control, .form-select {
            min-height: 44px;
            font-size: 16px;
            padding: 0.75rem 1rem;
        }

        /* Prevent zoom on iOS */
        @media screen and (max-width: 767px) {
            .form-control, .form-select, input, textarea {
                font-size: 16px !important;
            }
        }

        /* Better mobile navbar */
        @media (max-width: 991.98px) {
            .navbar-collapse {
                background: white;
                margin: 0.5rem -1rem -1rem;
                padding: 1rem;
                border-radius: 0 0 0.5rem 0.5rem;
                box-shadow: 0 4px 6px rgba(0,0,0,0.1);
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

    @stack('scripts')
</body>
</html>
