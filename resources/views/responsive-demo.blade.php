<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bootstrap 5 Responsive Demo</title>
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap-icons-npm/bootstrap-icons.css') }}" rel="stylesheet">
    <style>
        /* Custom responsive styles */
        .hero-section { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
        .navbar-brand { font-weight: 700; }
        .card-hover:hover { transform: translateY(-5px); transition: all 0.3s ease; }
        
        /* Mobile-first responsive typography */
        .display-responsive { font-size: clamp(1.5rem, 4vw, 3rem); }
        .lead-responsive { font-size: clamp(1rem, 2.5vw, 1.25rem); }
        
        /* Responsive spacing */
        .section-padding { padding: clamp(2rem, 5vw, 5rem) 0; }
        
        /* Dark mode support */
        [data-bs-theme="dark"] .hero-section { background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%); }
        
        /* Print styles */
        @media print {
            .navbar, .btn, .modal { display: none !important; }
            .container { max-width: none !important; }
        }
    </style>
</head>
<body>

<!-- 1. RESPONSIVE NAVIGATION -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
    <div class="container">
        <!-- Logo - Always visible -->
        <a class="navbar-brand d-flex align-items-center" href="#">
            <i class="bi bi-bootstrap-fill text-primary me-2 fs-4"></i>
            <span class="d-none d-sm-inline">Bootstrap Demo</span>
            <span class="d-sm-none">BS5</span>
        </a>

        <!-- Mobile toggle button -->
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <i class="bi bi-list fs-4"></i>
        </button>

        <!-- Collapsible menu -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link active px-3" href="#home">
                        <i class="bi bi-house me-1 d-lg-none"></i>Home
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle px-3" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-grid me-1 d-lg-none"></i>Components
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#grid">Grid System</a></li>
                        <li><a class="dropdown-item" href="#forms">Forms</a></li>
                        <li><a class="dropdown-item" href="#tables">Tables</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3" href="#contact">
                        <i class="bi bi-envelope me-1 d-lg-none"></i>Contact
                    </a>
                </li>
                <!-- Dark mode toggle -->
                <li class="nav-item">
                    <button class="btn btn-outline-secondary btn-sm ms-2" onclick="toggleTheme()">
                        <i class="bi bi-moon-stars"></i>
                    </button>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- 2. HERO SECTION - Responsive Typography -->
<section class="hero-section section-padding" id="home">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-12 col-lg-8">
                <h1 class="display-responsive fw-bold mb-4">Bootstrap 5 Responsive Design</h1>
                <p class="lead-responsive mb-4">Mobile-first approach with fluid typography and adaptive layouts</p>
                <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                    <button class="btn btn-light btn-lg">Get Started</button>
                    <button class="btn btn-outline-light btn-lg">Learn More</button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 3. RESPONSIVE GRID SYSTEM -->
<section class="section-padding bg-light" id="grid">
    <div class="container">
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h2 class="display-6 fw-bold mb-3">Responsive Grid System</h2>
                <p class="lead text-muted">Adaptive layouts for all screen sizes</p>
            </div>
        </div>
        
        <!-- Grid Examples -->
        <div class="row g-4 mb-5">
            <!-- Full width on mobile, half on tablet, third on desktop -->
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 card-hover shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-phone text-primary fs-1 mb-3"></i>
                        <h5>Mobile First</h5>
                        <p class="text-muted">col-12 col-md-6 col-lg-4</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 card-hover shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-tablet text-success fs-1 mb-3"></i>
                        <h5>Tablet Optimized</h5>
                        <p class="text-muted">Responsive breakpoints</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 card-hover shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-laptop text-info fs-1 mb-3"></i>
                        <h5>Desktop Ready</h5>
                        <p class="text-muted">Full responsive design</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Responsive Image Example -->
        <div class="row">
            <div class="col-12 col-lg-6 mb-4">
                <h4>Responsive Images</h4>
                <div class="ratio ratio-16x9 mb-3">
                    <img src="https://picsum.photos/800/450" class="img-fluid rounded" alt="Responsive image">
                </div>
                <code>.img-fluid .ratio .ratio-16x9</code>
            </div>
            <div class="col-12 col-lg-6">
                <h4>Offcanvas Sidebar</h4>
                <button class="btn btn-primary mb-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample">
                    <i class="bi bi-list"></i> Toggle Sidebar
                </button>
                <p class="text-muted">Perfect for mobile navigation and filters</p>
            </div>
        </div>
    </div>
</section>

<!-- 4. RESPONSIVE FORMS -->
<section class="section-padding" id="forms">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                <h2 class="display-6 fw-bold text-center mb-5">Responsive Forms</h2>
                
                <form class="needs-validation" novalidate>
                    <!-- Stacked on mobile, side-by-side on desktop -->
                    <div class="row g-3 mb-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label">First Name</label>
                            <input type="text" class="form-control" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Last Name</label>
                            <input type="text" class="form-control" required>
                        </div>
                    </div>
                    
                    <div class="row g-3 mb-3">
                        <div class="col-12 col-md-8">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" required>
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label">Phone</label>
                            <input type="tel" class="form-control">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Message</label>
                        <textarea class="form-control" rows="4"></textarea>
                    </div>
                    
                    <!-- Responsive button group -->
                    <div class="d-flex flex-column flex-sm-row gap-2">
                        <button type="submit" class="btn btn-primary flex-fill">Submit Form</button>
                        <button type="reset" class="btn btn-outline-secondary">Reset</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- 5. RESPONSIVE TABLES -->
<section class="section-padding bg-light" id="tables">
    <div class="container">
        <h2 class="display-6 fw-bold text-center mb-5">Responsive Tables</h2>
        
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Department</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>John Doe</td>
                        <td>john@example.com</td>
                        <td>Engineering</td>
                        <td><span class="badge bg-success">Active</span></td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-primary"><i class="bi bi-eye"></i></button>
                                <button class="btn btn-outline-warning"><i class="bi bi-pencil"></i></button>
                                <button class="btn btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Jane Smith</td>
                        <td>jane@example.com</td>
                        <td>Marketing</td>
                        <td><span class="badge bg-warning">Pending</span></td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-primary"><i class="bi bi-eye"></i></button>
                                <button class="btn btn-outline-warning"><i class="bi bi-pencil"></i></button>
                                <button class="btn btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>

<!-- 6. INTERACTIVE COMPONENTS -->
<section class="section-padding">
    <div class="container">
        <h2 class="display-6 fw-bold text-center mb-5">Interactive Components</h2>
        
        <div class="row g-4">
            <!-- Accordion for mobile-friendly content -->
            <div class="col-12 col-lg-6">
                <h4>Responsive Accordion</h4>
                <div class="accordion" id="accordionExample">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne">
                                Mobile Optimization
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                Perfect for organizing content on smaller screens while maintaining accessibility.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo">
                                Touch-Friendly Design
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                All interactive elements are sized for touch interaction with minimum 44px touch targets.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Modal trigger -->
            <div class="col-12 col-lg-6">
                <h4>Responsive Modal</h4>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    Launch Demo Modal
                </button>
                <p class="text-muted mt-2">Modals automatically adapt to screen size and orientation</p>
            </div>
        </div>
    </div>
</section>

<!-- OFFCANVAS SIDEBAR -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title">Responsive Sidebar</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body">
        <div class="list-group">
            <a href="#" class="list-group-item list-group-item-action">
                <i class="bi bi-house me-2"></i>Dashboard
            </a>
            <a href="#" class="list-group-item list-group-item-action">
                <i class="bi bi-person me-2"></i>Profile
            </a>
            <a href="#" class="list-group-item list-group-item-action">
                <i class="bi bi-gear me-2"></i>Settings
            </a>
        </div>
    </div>
</div>

<!-- RESPONSIVE MODAL -->
<div class="modal fade" id="exampleModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Responsive Modal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <img src="https://picsum.photos/400/300" class="img-fluid rounded" alt="Modal image">
                    </div>
                    <div class="col-12 col-md-6">
                        <h6>Mobile-First Modal Design</h6>
                        <p>This modal adapts to different screen sizes and maintains usability across all devices.</p>
                        <ul class="list-unstyled">
                            <li><i class="bi bi-check-circle text-success me-2"></i>Touch-friendly controls</li>
                            <li><i class="bi bi-check-circle text-success me-2"></i>Responsive layout</li>
                            <li><i class="bi bi-check-circle text-success me-2"></i>Accessible design</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save Changes</button>
            </div>
        </div>
    </div>
</div>

<!-- FOOTER -->
<footer class="bg-dark text-white py-4 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-6">
                <h5>Bootstrap 5 Responsive Demo</h5>
                <p class="text-muted">Mobile-first responsive design implementation</p>
            </div>
            <div class="col-12 col-md-6 text-md-end">
                <p class="mb-0">&copy; 2024 Responsive Design Demo</p>
            </div>
        </div>
    </div>
</footer>

<!-- Bootstrap 5 JS -->
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>

<script>
// Dark mode toggle
function toggleTheme() {
    const html = document.documentElement;
    const currentTheme = html.getAttribute('data-bs-theme');
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
    html.setAttribute('data-bs-theme', newTheme);
    localStorage.setItem('theme', newTheme);
}

// Load saved theme
document.addEventListener('DOMContentLoaded', function() {
    const savedTheme = localStorage.getItem('theme') || 'light';
    document.documentElement.setAttribute('data-bs-theme', savedTheme);
});

// Form validation
(function() {
    'use strict';
    window.addEventListener('load', function() {
        var forms = document.getElementsByClassName('needs-validation');
        var validation = Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();

// Smooth scrolling for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});
</script>

</body>
</html>