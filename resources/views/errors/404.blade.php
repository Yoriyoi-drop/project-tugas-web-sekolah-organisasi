@extends('layouts.app')

@section('title', 'Halaman Tidak Ditemukan')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center text-center py-5">
        <div class="col-lg-6" data-aos="zoom-in">
            <div class="mb-4">
                <i class="bi bi-exclamation-octagon text-danger" style="font-size: 5rem;"></i>
            </div>
            <h1 class="display-4 fw-bold mb-4">404</h1>
            <h2 class="h3 mb-4">Halaman Tidak Ditemukan</h2>
            <p class="lead text-muted mb-5">Maaf, halaman yang Anda cari tidak tersedia atau telah dipindahkan.</p>
            <a href="{{ route('beranda') }}" class="btn btn-primary btn-lg rounded-pill px-5">
                <i class="bi bi-house-fill me-2"></i>Kembali ke Beranda
            </a>
        </div>
    </div>
</div>
@endsection
