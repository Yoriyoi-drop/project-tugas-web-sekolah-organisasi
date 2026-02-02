@extends('layouts.app')

@section('title', 'Pendaftaran Akun Siswa - MA NU Nusantara')

@section('content')
    <!-- Hero Section -->
    <section class="hero-section py-5 position-relative overflow-hidden" style="background: linear-gradient(135deg, #1e40af 0%, #7c3aed 50%, #dc2626 100%); min-height: 100vh;">
        <div class="container py-lg-5 position-relative z-1">
            <div class="row align-items-center min-vh-100">
                <div class="col-lg-6 text-white">
                    <div class="animate-fade-in">
                        <span class="badge bg-warning text-dark fw-bold mb-4 px-4 py-2 shadow-lg">
                            <i class="bi bi-star-fill me-2"></i>PENDAFTARAN AKUN SISWA 2024/2025
                        </span>
                        <h1 class="display-2 fw-bold mb-4 animate-slide-up">
                            Bergabunglah Bersama<br>
                            <span class="text-warning">MA NU Nusantara</span>
                        </h1>
                        <p class="lead mb-5 opacity-90 animate-slide-up-delay">
                            Daftarkan diri Anda untuk mendapatkan akses penuh ke sistem pembelajaran digital. 
                            Nikmati pengalaman belajar yang modern dengan teknologi terkini.
                        </p>
                        <div class="d-flex flex-wrap gap-3 animate-slide-up-delay-2">
                            <a href="{{ route('student-registration.create') }}" class="btn btn-warning btn-lg px-5 py-3 fw-bold shadow-lg hover-scale">
                                <i class="bi bi-person-plus-fill me-2"></i>Daftar Sekarang
                            </a>
                            <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg px-5 py-3 fw-bold border-2">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Login Siswa
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 d-none d-lg-block">
                    <div class="position-relative animate-float">
                        <div class="hero-image-container">
                            <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=1000" 
                                 class="img-fluid rounded-4 shadow-2xl" 
                                 alt="Student Registration"
                                 style="max-height: 600px; object-fit: cover;">
                            <div class="hero-overlay"></div>
                        </div>
                        <div class="floating-card floating-card-1">
                            <div class="card border-0 shadow-lg">
                                <div class="card-body text-center p-3">
                                    <i class="bi bi-people-fill text-primary fs-1"></i>
                                    <h6 class="fw-bold mb-0">500+ Siswa</h6>
                                </div>
                            </div>
                        </div>
                        <div class="floating-card floating-card-2">
                            <div class="card border-0 shadow-lg">
                                <div class="card-body text-center p-3">
                                    <i class="bi bi-trophy-fill text-warning fs-1"></i>
                                    <h6 class="fw-bold mb-0">Prestasi</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="hero-pattern"></div>
    </section>

    <!-- Statistics Section -->
    <section class="py-5 bg-white">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="text-center">
                        <div class="stat-number text-primary fw-bold display-4">50+</div>
                        <div class="stat-label text-muted">Guru Profesional</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center">
                        <div class="stat-number text-success fw-bold display-4">500+</div>
                        <div class="stat-label text-muted">Siswa Aktif</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center">
                        <div class="stat-number text-warning fw-bold display-4">20+</div>
                        <div class="stat-label text-muted">Ekstrakurikuler</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center">
                        <div class="stat-number text-info fw-bold display-4">98%</div>
                        <div class="stat-label text-muted">Kelulusan</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Benefits Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-lg-12">
                    <h2 class="display-4 fw-bold mb-3">Keunggulan Sistem Digital</h2>
                    <p class="lead text-muted">Nikmati kemudahan belajar dengan teknologi terkini</p>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="benefit-card h-100 text-center p-4">
                        <div class="benefit-icon bg-primary bg-opacity-10 rounded-circle mb-3 mx-auto">
                            <i class="bi bi-book-fill text-primary fs-1"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Materi Digital</h5>
                        <p class="text-muted">Akses materi pembelajaran kapan saja dan di mana saja</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="benefit-card h-100 text-center p-4">
                        <div class="benefit-icon bg-success bg-opacity-10 rounded-circle mb-3 mx-auto">
                            <i class="bi bi-clipboard-check-fill text-success fs-1"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Tugas Online</h5>
                        <p class="text-muted">Kerjakan dan kumpulkan tugas secara digital</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="benefit-card h-100 text-center p-4">
                        <div class="benefit-icon bg-warning bg-opacity-10 rounded-circle mb-3 mx-auto">
                            <i class="bi bi-graph-up text-warning fs-1"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Monitoring Nilai</h5>
                        <p class="text-muted">Pantau perkembangan akademik secara real-time</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="benefit-card h-100 text-center p-4">
                        <div class="benefit-icon bg-info bg-opacity-10 rounded-circle mb-3 mx-auto">
                            <i class="bi bi-chat-dots-fill text-info fs-1"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Komunikasi</h5>
                        <p class="text-muted">Terhubung dengan guru dan teman sekelas</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Process Section -->
    <section class="py-5 bg-white">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-lg-12">
                    <h2 class="display-4 fw-bold mb-3">Proses Pendaftaran</h2>
                    <p class="lead text-muted">3 langkah mudah untuk bergabung</p>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="process-step text-center">
                        <div class="step-number bg-primary text-white rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center">
                            <span class="fw-bold fs-3">1</span>
                        </div>
                        <h5 class="fw-bold mb-3">Isi Formulir</h5>
                        <p class="text-muted">Lengkapi data diri dengan benar dan lengkap</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="process-step text-center">
                        <div class="step-number bg-success text-white rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center">
                            <span class="fw-bold fs-3">2</span>
                        </div>
                        <h5 class="fw-bold mb-3">Verifikasi</h5>
                        <p class="text-muted">Tim kami akan memverifikasi data Anda</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="process-step text-center">
                        <div class="step-number bg-warning text-white rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center">
                            <span class="fw-bold fs-3">3</span>
                        </div>
                        <h5 class="fw-bold mb-3">Akun Aktif</h5>
                        <p class="text-muted">Login dan mulai belajar dengan akun Anda</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-5 bg-primary text-white">
        <div class="container text-center">
            <h2 class="display-4 fw-bold mb-4">Siap Memulai Perjalanan Digital?</h2>
            <p class="lead mb-5">Bergabunglah dengan ratusan siswa yang telah merasakan kemudahan belajar digital</p>
            <a href="{{ route('student-registration.create') }}" class="btn btn-warning btn-lg px-5 py-3 fw-bold shadow-lg">
                <i class="bi bi-rocket-takeoff me-2"></i>Daftar Sekarang
            </a>
        </div>
    </section>
@endsection

@push('styles')
<style>
    /* Hero Section Styles */
    .hero-section {
        position: relative;
        overflow: hidden;
    }
    
    .hero-pattern {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-image: 
            radial-gradient(circle at 20% 50%, rgba(255,255,255,0.1) 0%, transparent 50%),
            radial-gradient(circle at 80% 80%, rgba(255,255,255,0.1) 0%, transparent 50%);
        pointer-events: none;
    }
    
    .hero-image-container {
        position: relative;
        border-radius: 1rem;
        overflow: hidden;
    }
    
    .hero-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(45deg, rgba(30, 64, 175, 0.1), rgba(124, 58, 237, 0.1));
        border-radius: 1rem;
    }
    
    .floating-card {
        position: absolute;
        animation: float 3s ease-in-out infinite;
    }
    
    .floating-card-1 {
        top: 10%;
        right: -10%;
        animation-delay: 0s;
    }
    
    .floating-card-2 {
        bottom: 10%;
        left: -10%;
        animation-delay: 1.5s;
    }
    
    /* Benefit Cards */
    .benefit-card {
        background: white;
        border-radius: 1rem;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        border: 1px solid rgba(0,0,0,0.05);
    }
    
    .benefit-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 25px rgba(0,0,0,0.15);
    }
    
    .benefit-icon {
        width: 100px;
        height: 100px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.3s ease;
    }
    
    .benefit-card:hover .benefit-icon {
        transform: scale(1.1);
    }
    
    /* Process Steps */
    .step-number {
        width: 80px;
        height: 80px;
        font-size: 1.5rem;
        font-weight: bold;
        transition: transform 0.3s ease;
    }
    
    .process-step:hover .step-number {
        transform: scale(1.1);
    }
    
    /* Animations */
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
    }
    
    @keyframes fade-in {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    @keyframes slide-up {
        from { 
            opacity: 0;
            transform: translateY(30px);
        }
        to { 
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-fade-in {
        animation: fade-in 1s ease-out;
    }
    
    .animate-slide-up {
        animation: slide-up 0.8s ease-out;
    }
    
    .animate-slide-up-delay {
        animation: slide-up 0.8s ease-out 0.2s both;
    }
    
    .animate-slide-up-delay-2 {
        animation: slide-up 0.8s ease-out 0.4s both;
    }
    
    .animate-float {
        animation: float 4s ease-in-out infinite;
    }
    
    .hover-scale {
        transition: transform 0.3s ease;
    }
    
    .hover-scale:hover {
        transform: scale(1.05);
    }
    
    /* Statistics */
    .stat-number {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-weight: 700;
        line-height: 1;
    }
    
    .stat-label {
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .hero-section {
            min-height: auto;
            padding: 3rem 0;
        }
        
        .display-2 {
            font-size: 2.5rem;
        }
        
        .floating-card {
            display: none;
        }
    }
</style>
@endpush
