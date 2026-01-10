@extends('layouts.app')

@section('title', 'Dashboard - ' . config('app.name'))

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white py-3">
                    <h4 class="mb-0 text-center">
                        <i class="bi bi-speedometer2 me-2"></i> Dashboard Pengguna
                    </h4>
                </div>
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <h2 class="display-6">Selamat Datang!</h2>
                        <p class="text-muted">Anda berhasil masuk ke sistem</p>
                    </div>

                    <div class="alert alert-info d-flex align-items-center" role="alert">
                        <i class="bi bi-person-circle fs-4 me-3"></i>
                        <div>
                            <strong>Status Login:</strong>
                            <span class="d-block">Halo, <span class="fw-bold">{{ $user['nama'] ?? 'Pengguna' }}</span></span>
                        </div>
                    </div>

                    <div class="d-grid gap-2 col-md-6 mx-auto mt-4">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-danger w-100 py-2">
                                <i class="bi bi-box-arrow-right me-2"></i> Keluar / Logout
                            </button>
                        </form>
                    </div>
                </div>
                <div class="card-footer bg-light text-center py-3">
                    <small class="text-muted">
                        &copy; {{ date('Y') }} Dashboard dibuat oleh <strong>{{ $user['nama'] ?? 'Developer' }}</strong>.
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection