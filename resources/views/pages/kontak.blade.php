@extends('layouts.app')

@section('title', 'Hubungi Kami - Madrasah Aliyah Nusantara')

@push('styles')
<style>
.contact-hero {
    background: linear-gradient(135deg, var(--ma-green) 0%, var(--ma-green-light) 100%);
    color: white;
    padding: 8rem 0 5rem;
}

.contact-hero .hero-icon {
    font-size: 4rem;
    opacity: 0.9;
}

.contact-card {
    border: none;
    border-radius: 1.5rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
}

.contact-item {
    transition: all 0.3s ease;
}

.contact-icon-box {
    width: 3.5rem;
    height: 3.5rem;
    background: rgba(15, 118, 110, 0.1);
    color: var(--ma-green);
    border-radius: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

.map-container {
    border-radius: 1.5rem;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
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
                        
                        <div class="contact-item d-flex align-items-center mb-4">
                            <div class="contact-icon-box me-3">
                                <i class="bi bi-geo-alt-fill"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="fw-bold mb-1">Alamat</h5>
                                <p class="text-muted mb-0">Jl. Pendidikan No. 123, Kelurahan Nusantara, Jakarta Selatan 12345</p>
                            </div>
                        </div>

                        <div class="contact-item d-flex align-items-center mb-4">
                            <div class="contact-icon-box me-3">
                                <i class="bi bi-telephone-fill"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="fw-bold mb-1">Telepon</h5>
                                <a href="tel:+622112345678" class="text-decoration-none text-dark fw-semibold">(021) 1234-5678</a>
                            </div>
                        </div>

                        <div class="contact-item d-flex align-items-center mb-4">
                            <div class="contact-icon-box me-3">
                                <i class="bi bi-envelope-at-fill"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="fw-bold mb-1">Email</h5>
                                <a href="mailto:info@manusantara.sch.id" class="text-decoration-none text-dark fw-semibold">info@manusantara.sch.id</a>
                            </div>
                        </div>

                        <div class="contact-item d-flex align-items-center mb-4">
                            <div class="contact-icon-box me-3">
                                <i class="bi bi-clock-fill"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="fw-bold mb-1">Jam Operasional</h5>
                                <p class="text-muted mb-0 small">Senin - Jumat: 07:00 - 16:00 / Sabtu: 07:00 - 12:00</p>
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
                            <i class="bi bi-send-fill me-2 text-primary"></i>Kirim Pesan
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
                                <button type="submit" class="btn btn-primary d-flex align-items-center justify-content-center py-3 fw-bold shadow-sm" id="submitBtn">
                                    <span class="btn-text">
                                        <i class="bi bi-send-fill me-2"></i>Kirim Pesan
                                    </span>
                                    <span class="btn-loading d-none">
                                        <div class="spinner-border spinner-border-sm me-2" role="status"></div>
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
