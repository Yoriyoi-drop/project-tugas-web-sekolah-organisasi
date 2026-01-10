@extends('layouts.app')

@section('title', 'Sesi Berakhir')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center text-center py-5">
        <div class="col-lg-6" data-aos="zoom-in">
            <div class="mb-4">
                <i class="bi bi-clock-history text-warning" style="font-size: 5rem;"></i>
            </div>
            <h1 class="display-4 fw-bold mb-4">419</h1>
            <h2 class="h3 mb-4">Sesi Telah Berakhir</h2>
            <p class="lead text-muted mb-5">Keamanan sesi Anda telah berakhir. Silakan segarkan halaman dan coba lagi.</p>
            <a href="{{ url()->previous() }}" class="btn btn-warning btn-lg rounded-pill px-5">
                <i class="bi bi-arrow-clockwise me-2"></i>Segarkan Halaman
            </a>
        </div>
    </div>
</div>
@endsection
