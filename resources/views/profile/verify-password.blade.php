@extends('layouts.app')

@section('title', 'Verifikasi Ubah Password - Madrasah Aliyah Nusantara')

@push('styles')
<style>
:root {
    --primary-gradient: linear-gradient(135deg, #009688 0%, #00796B 100%);
    --accent-color: #00BFA5;
}

.verify-hero {
    background: var(--primary-gradient);
    color: white;
    padding: 4rem 0 2rem;
}

.verify-card {
    border: none;
    border-radius: 1rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    margin-top: -3rem;
    position: relative;
    z-index: 10;
}

.code-input {
    font-size: 1.8rem;
    letter-spacing: 0.8rem;
    text-align: center;
    border: 3px solid #e9ecef;
    border-radius: 0.75rem;
    padding: 1rem;
    transition: all 0.3s ease;
}

.code-input:focus {
    border-color: var(--accent-color);
    box-shadow: 0 0 0 0.2rem rgba(0, 191, 165, 0.25);
    transform: scale(1.02);
}

.btn-verify {
    background: var(--primary-gradient);
    border: none;
    border-radius: 0.75rem;
    padding: 1rem 2rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-verify:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 150, 136, 0.3);
}

.security-info {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 1rem;
    padding: 1.5rem;
    border-left: 4px solid var(--accent-color);
}

.countdown {
    font-weight: bold;
    color: var(--accent-color);
}

@media (max-width: 768px) {
    .verify-hero {
        padding: 3rem 0 1.5rem;
    }
    
    .verify-card {
        margin-top: -2rem;
    }
    
    .code-input {
        font-size: 1.5rem;
        letter-spacing: 0.5rem;
    }
}
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="verify-hero text-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="mb-4">
                    <i class="fas fa-shield-alt" style="font-size: 4rem; opacity: 0.9;"></i>
                </div>
                <h1 class="display-5 fw-bold mb-3">Verifikasi Keamanan</h1>
                <p class="lead mb-0">Langkah terakhir untuk mengubah password Anda</p>
            </div>
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="card verify-card">
                    <div class="card-body p-4 p-md-5">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <div class="text-center mb-4">
                            <div class="mb-3">
                                <i class="fas fa-envelope-open-text text-primary" style="font-size: 3rem;"></i>
                            </div>
                            <h4 class="fw-bold mb-3">Masukkan Kode Verifikasi</h4>
                            <p class="text-muted">Kami telah mengirim kode verifikasi 6 digit ke email Anda. Masukkan kode tersebut di bawah ini.</p>
                        </div>

                        <form method="POST" action="{{ route('profile.verify-password') }}" id="verifyForm">
                            @csrf
                            
                            <div class="mb-4">
                                <label for="verification_code" class="form-label fw-semibold">Kode Verifikasi</label>
                                <input type="text" 
                                       class="form-control code-input @error('verification_code') is-invalid @enderror @error('rate_limit') is-invalid @enderror" 
                                       id="verification_code" 
                                       name="verification_code" 
                                       maxlength="6" 
                                       placeholder="000000"
                                       autocomplete="off"
                                       required>
                                @error('verification_code')
                                    <div class="invalid-feedback text-center">{{ $message }}</div>
                                @enderror
                                @error('rate_limit')
                                    <div class="invalid-feedback text-center">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-grid gap-3 mb-4">
                                <button type="submit" class="btn btn-verify btn-lg text-white" id="submitBtn">
                                    <span class="btn-text">
                                        <i class="fas fa-check-circle me-2"></i>Verifikasi & Ubah Password
                                    </span>
                                    <span class="btn-loading d-none">
                                        <span class="spinner-border spinner-border-sm me-2"></span>Memverifikasi...
                                    </span>
                                </button>
                                <a href="{{ route('profile.edit') }}" class="btn btn-outline-secondary btn-lg">
                                    <i class="fas fa-arrow-left me-2"></i>Kembali ke Profile
                                </a>
                            </div>
                        </form>

                        <div class="security-info">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-info-circle text-primary me-3 mt-1"></i>
                                <div>
                                    <h6 class="fw-bold mb-2">Informasi Keamanan</h6>
                                    <ul class="list-unstyled mb-0 small text-muted">
                                        <li class="mb-1">• Kode berlaku selama <span class="countdown" id="countdown">10:00</span></li>
                                        <li class="mb-1">• Periksa folder spam jika tidak menerima email</li>
                                        <li class="mb-1">• Jangan bagikan kode ini kepada siapapun</li>
                                        <li>• Kode hanya dapat digunakan sekali</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
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
    const codeInput = document.getElementById('verification_code');
    const form = document.getElementById('verifyForm');
    const submitBtn = document.getElementById('submitBtn');
    const countdownEl = document.getElementById('countdown');
    
    // Format input to numbers only
    codeInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/[^0-9]/g, '');
        if (value.length > 6) value = value.slice(0, 6);
        e.target.value = value;
        
        // Auto submit when 6 digits entered
        if (value.length === 6) {
            setTimeout(() => form.submit(), 500);
        }
    });
    
    // Paste handling
    codeInput.addEventListener('paste', function(e) {
        e.preventDefault();
        const paste = (e.clipboardData || window.clipboardData).getData('text');
        const numbers = paste.replace(/[^0-9]/g, '').slice(0, 6);
        this.value = numbers;
        
        if (numbers.length === 6) {
            setTimeout(() => form.submit(), 500);
        }
    });
    
    // Form submission with loading state
    form.addEventListener('submit', function(e) {
        if (submitBtn.disabled) {
            e.preventDefault();
            return;
        }
        
        const btnText = submitBtn.querySelector('.btn-text');
        const btnLoading = submitBtn.querySelector('.btn-loading');
        
        submitBtn.disabled = true;
        btnText.classList.add('d-none');
        btnLoading.classList.remove('d-none');
    });
    
    // Countdown timer
    let timeLeft = 600; // 10 minutes
    
    function updateCountdown() {
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;
        countdownEl.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
        
        if (timeLeft <= 0) {
            countdownEl.textContent = 'Kedaluwarsa';
            countdownEl.style.color = '#dc3545';
            codeInput.disabled = true;
            submitBtn.disabled = true;
            return;
        }
        
        if (timeLeft <= 60) {
            countdownEl.style.color = '#dc3545';
        } else if (timeLeft <= 180) {
            countdownEl.style.color = '#fd7e14';
        }
        
        timeLeft--;
        setTimeout(updateCountdown, 1000);
    }
    
    updateCountdown();
    
    // Focus on input
    codeInput.focus();
});
</script>
@endpush