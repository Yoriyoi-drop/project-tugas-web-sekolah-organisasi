@extends('layouts.app')

@section('title', 'Kesalahan Server')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center text-center py-5">
        <div class="col-lg-6" data-aos="zoom-in">
            <div class="mb-4">
                <i class="bi bi-gear-fill text-secondary" style="font-size: 5rem;"></i>
            </div>
            <h1 class="display-4 fw-bold mb-4">500</h1>
            <h2 class="h3 mb-4">Kesalahan Server Internal</h2>
            <p class="lead text-muted mb-5">Terjadi kesalahan pada server kami. Tim teknis kami telah diberitahu dan sedang berusaha memperbaikinya.</p>
            <a href="{{ route('beranda') }}" class="btn btn-secondary btn-lg rounded-pill px-5">
                <i class="bi bi-house-fill me-2"></i>Kembali ke Beranda
            </a>
        </div>
    </div>
</div>
@endsection
