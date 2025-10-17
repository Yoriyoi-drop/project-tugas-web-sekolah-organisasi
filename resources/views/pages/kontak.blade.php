@extends('layouts.app')

@section('title', 'Hubungi Kami - Madrasah Aliyah Nusantara')

@push('styles')
<style>
:root {
    --primary-gradient: linear-gradient(135deg, #009688 0%, #00796B 100%);
    --accent-color: #00BFA5;
    --teal-primary: #009688;
    --teal-dark: #00796B;
}

.contact-hero {
    background: var(--primary-gradient);
    color: white;
    padding: 6rem 0 4rem;
}

.contact-hero .hero-icon {
    font-size: 4rem;
    opacity: 0.9;
}

.contact-card {
    border: none;
    border-radius: 1rem;
    box-shadow: 0 6px 20px rgba(3, 15, 20, 0.08);
    transition: all 0.3s ease;
    height: 100%;
}

.contact-card:hover {
    box-shadow: 0 8px 25px rgba(3, 15, 20, 0.12);
    transform: translateY(-2px);
}

.contact-item {
    padding: 1rem;
    border-radius: 0.5rem;
    transition: all 0.3s ease;
    margin-bottom: 1.5rem;
}

.contact-item:hover {
    background-color: #f8f9fa;
}

.contact-item i {
    color: var(--accent-color);
    font-size: 1.5rem;
    width: 2rem;
    text-align: center;
}

.contact-item a {
    color: var(--accent-color);
    text-decoration: none;
}

.contact-item a:hover {
    color: var(--teal-dark);
    text-decoration: underline;
}

.contact-form .form-control {
    border: 2px solid #e9ecef;
    border-radius: 0.5rem;
    padding: 0.875rem 1rem;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.contact-form .form-control:focus {
    border-color: var(--accent-color);
    box-shadow: 0 0 0 0.2rem rgba(0, 191, 165, 0.25);
}

.contact-form .form-control.is-invalid {
    border-color: #dc3545;
}

.btn-contact {
    background: var(--primary-gradient);
    border: none;
    color: white;
    padding: 0.875rem 2rem;
    border-radius: 0.5rem;
    font-weight: 600;
    transition: all 0.3s ease;
    min-height: 44px;
}

.btn-contact:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(3, 15, 20, 0.12);
    color: white;
}

.btn-contact:disabled {
    opacity: 0.7;
    transform: none;
}

.map-container {
    border-radius: 1rem;
    overflow: hidden;
    box-shadow: 0 6px 20px rgba(3, 15, 20, 0.08);
}

.invalid-feedback {
    display: block;
    color: #dc3545;
    font-size: 0.875rem;
    margin-top: 0.25rem;
}

@media (max-width: 575.98px) {
    .contact-hero {
        padding: 3rem 0 2rem;
    }
    
    .contact-hero .hero-icon {
        font-size: 3rem;
    }
    
    .contact-hero h1 {
        font-size: 2rem;
    }
    
    .contact-card {
        margin-bottom: 1.5rem;
    }
    
    .contact-item {
        padding: 0.75rem;
        margin-bottom: 1rem;
    }
    
    .contact-item i {
        font-size: 1.25rem;
    }
}

/* Extra small devices */
@media (max-width: 375px) {
    .contact-hero {
        padding: 2rem 0 1.5rem;
    }
    
    .contact-hero .hero-icon {
        font-size: 2.5rem;
    }
    
    .card-body {
        padding: 1rem !important;
    }
}

/* Large screens optimization */
@media (min-width: 1200px) {
    .contact-hero {
        padding: 8rem 0 5rem;
    }
    
    .contact-card {
        padding: 3rem;
    }
}

/* Landscape mobile optimization */
@media (max-height: 500px) and (orientation: landscape) {
    .contact-hero {
        padding: 2rem 0 1rem;
    }
    
    .contact-hero .hero-icon {
        font-size: 2rem;
        margin-bottom: 0.5rem;
    }
}

@media (prefers-reduced-motion: reduce) {
    * {
        animation-duration: 0.01ms !important;
        transition-duration: 0.01ms !important;
    }
}
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="contact-hero text-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="hero-icon mb-4">
                    <i class="fas fa-envelope" aria-hidden="true"></i>
                </div>
                <h1 class="display-4 fw-bold mb-4">Hubungi Kami</h1>
                <p class="lead mb-0">Kami siap membantu Anda. Hubungi kami melalui informasi kontak di bawah atau kirim pesan langsung.</p>
            </div>
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <!-- Contact Information Card -->
            <div class="col-lg-6">
                <div class="card contact-card h-100">
                    <div class="card-body p-4">
                        <h2 class="h4 fw-bold mb-4">
                            <i class="fas fa-info-circle me-2 text-primary"></i>Informasi Kontak
                        </h2>
                        
                        <div class="contact-item d-flex align-items-start">
                            <i class="fas fa-map-marker-alt me-3 mt-1" aria-hidden="true"></i>
                            <div class="flex-grow-1">
                                <h5 class="fw-semibold mb-2">Alamat</h5>
                                <p class="text-muted mb-0">
                                    Jl. Pendidikan No. 123<br>
                                    Kelurahan Nusantara<br>
                                    Jakarta Selatan 12345
                                </p>
                            </div>
                        </div>

                        <div class="contact-item d-flex align-items-start">
                            <i class="fas fa-phone me-3 mt-1" aria-hidden="true"></i>
                            <div class="flex-grow-1">
                                <h5 class="fw-semibold mb-2">Telepon</h5>
                                <a href="tel:+622112345678" class="text-decoration-none">(021) 1234-5678</a>
                            </div>
                        </div>

                        <div class="contact-item d-flex align-items-start">
                            <i class="fas fa-envelope me-3 mt-1" aria-hidden="true"></i>
                            <div class="flex-grow-1">
                                <h5 class="fw-semibold mb-2">Email</h5>
                                <a href="mailto:info@manusantara.sch.id" class="text-decoration-none">info@manusantara.sch.id</a>
                            </div>
                        </div>

                        <div class="contact-item d-flex align-items-start">
                            <i class="fas fa-clock me-3 mt-1" aria-hidden="true"></i>
                            <div class="flex-grow-1">
                                <h5 class="fw-semibold mb-2">Jam Operasional</h5>
                                <p class="text-muted mb-0">
                                    Senin - Jumat: 07:00 - 16:00<br>
                                    Sabtu: 07:00 - 12:00<br>
                                    Minggu: Tutup
                                </p>
                            </div>
                        </div>

                        <!-- Map -->
                        <div class="map-container mt-4">
                            <div class="ratio ratio-16x9">
                                <iframe 
                                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.521260322283!2d106.8195613!3d-6.2087634!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f5390917b759%3A0x6b45e67356080477!2sJakarta%2C%20Indonesia!5e0!3m2!1sen!2sid!4v1635123456789!5m2!1sen!2sid"
                                    allowfullscreen="" 
                                    loading="lazy" 
                                    referrerpolicy="no-referrer-when-downgrade"
                                    title="Lokasi Madrasah Aliyah Nusantara">
                                </iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="col-lg-6">
                <div class="card contact-card h-100">
                    <div class="card-body p-4">
                        <h2 class="h4 fw-bold mb-4">
                            <i class="fas fa-paper-plane me-2 text-primary"></i>Kirim Pesan
                        </h2>
                        
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        
                        <form class="contact-form" id="contactForm" action="{{ route('kontak.send') }}" method="POST" novalidate>
                            @csrf
                            <div class="mb-3">
                                <label for="nama" class="form-label fw-semibold">Nama Lengkap *</label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="nama" 
                                       name="name" 
                                       required 
                                       value="{{ old('name') }}">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold">Email *</label>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       required 
                                       value="{{ old('email') }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="subjek" class="form-label fw-semibold">Subjek</label>
                                <input type="text" 
                                       class="form-control @error('subject') is-invalid @enderror" 
                                       id="subjek" 
                                       name="subject" 
                                       value="{{ old('subject') }}">
                                @error('subject')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="pesan" class="form-label fw-semibold">Pesan *</label>
                                <textarea class="form-control @error('message') is-invalid @enderror" 
                                          id="pesan" 
                                          name="message" 
                                          rows="5" 
                                          required 
                                          minlength="10">{{ old('message') }}</textarea>
                                @error('message')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-contact" id="submitBtn">
                                    <span class="btn-text">
                                        <i class="fas fa-paper-plane me-2"></i>Kirim Pesan
                                    </span>
                                    <span class="btn-loading d-none">
                                        <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                                        Mengirim...
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('contactForm');
    const submitBtn = document.getElementById('submitBtn');
    
    if (!form || !submitBtn) return;
    
    // Real-time validation
    form.querySelectorAll('input, textarea').forEach(field => {
        field.addEventListener('input', function() {
            if (this.classList.contains('is-invalid')) {
                this.classList.remove('is-invalid');
                const feedback = this.nextElementSibling;
                if (feedback && feedback.classList.contains('invalid-feedback')) {
                    feedback.style.display = 'none';
                }
            }
        });
        
        field.addEventListener('blur', function() {
            validateField(this);
        });
    });
    
    // Form submission
    form.addEventListener('submit', function(e) {
        if (submitBtn.disabled) {
            e.preventDefault();
            return;
        }
        
        let isValid = true;
        form.querySelectorAll('input[required], textarea[required]').forEach(field => {
            if (!validateField(field)) {
                isValid = false;
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            const firstInvalid = form.querySelector('.is-invalid');
            if (firstInvalid) firstInvalid.focus();
            return;
        }
        
        // Show loading state
        setLoadingState(true);
    });
    
    function validateField(field) {
        const value = field.value.trim();
        let isValid = true;
        let message = '';
        
        if (field.hasAttribute('required') && !value) {
            isValid = false;
            message = getFieldLabel(field.name) + ' wajib diisi.';
        } else if (field.type === 'email' && value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
            isValid = false;
            message = 'Format email tidak valid.';
        } else if (field.name === 'message' && value && value.length < 10) {
            isValid = false;
            message = 'Pesan minimal 10 karakter.';
        }
        
        if (!isValid) {
            field.classList.add('is-invalid');
            showError(field, message);
        } else {
            field.classList.remove('is-invalid');
            hideError(field);
        }
        
        return isValid;
    }
    
    function showError(field, message) {
        let feedback = field.nextElementSibling;
        if (!feedback || !feedback.classList.contains('invalid-feedback')) {
            feedback = document.createElement('div');
            feedback.className = 'invalid-feedback';
            field.parentNode.insertBefore(feedback, field.nextSibling);
        }
        feedback.textContent = message;
        feedback.style.display = 'block';
    }
    
    function hideError(field) {
        const feedback = field.nextElementSibling;
        if (feedback && feedback.classList.contains('invalid-feedback')) {
            feedback.style.display = 'none';
        }
    }
    
    function getFieldLabel(fieldName) {
        const labels = {
            'name': 'Nama Lengkap',
            'email': 'Email',
            'subject': 'Subjek',
            'message': 'Pesan'
        };
        return labels[fieldName] || fieldName;
    }
    
    function setLoadingState(loading) {
        submitBtn.disabled = loading;
        const btnText = submitBtn.querySelector('.btn-text');
        const btnLoading = submitBtn.querySelector('.btn-loading');
        
        if (btnText && btnLoading) {
            if (loading) {
                btnText.classList.add('d-none');
                btnLoading.classList.remove('d-none');
            } else {
                btnText.classList.remove('d-none');
                btnLoading.classList.add('d-none');
            }
        }
    }
});
</script>
@endpush
