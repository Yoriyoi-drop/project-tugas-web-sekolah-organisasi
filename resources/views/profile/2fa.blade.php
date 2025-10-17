@extends('layouts.app')

@section('title', '2FA Setup - MA NU Nusantara')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0"><i class="bi bi-shield-lock me-2"></i>Two-Factor Authentication</h4>
                </div>
                <div class="card-body">
                    @if(!Auth::user()->two_factor_enabled)
                        <div class="text-center mb-4">
                            <h5>Setup Two-Factor Authentication</h5>
                            <div class="mb-4">
                                <h6>Scan QR Code:</h6>
                                <div class="d-flex justify-content-center">
                                    {!! $qrCode !!}
                                </div>
                                <p class="mt-2 small text-muted">Scan dengan Google Authenticator atau Authy</p>
                            </div>
                            <div class="alert alert-info">
                                <h6>Manual Setup:</h6>
                                <p><strong>Secret Key:</strong></p>
                                <code class="bg-light p-2 rounded d-block">{{ $manualEntryKey }}</code>
                                <p class="mt-2 small">Masukkan kode ini secara manual jika tidak bisa scan QR code</p>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('2fa.enable') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="code" class="form-label">Masukkan Kode 6 Digit</label>
                                <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                       id="code" name="code" maxlength="6" required>
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-check-circle me-2"></i>Aktifkan 2FA
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle me-2"></i>
                            2FA sudah aktif dan melindungi akun Anda.
                        </div>

                        <form method="POST" action="{{ route('2fa.disable') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="password" class="form-label">Password untuk Menonaktifkan</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-x-circle me-2"></i>Nonaktifkan 2FA
                            </button>
                        </form>
                    @endif

                    <div class="mt-4">
                        <a href="{{ route('profile.show') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Kembali ke Profile
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection