@extends('layouts.app')

@section('title', $activity->title . ' - Kegiatan MA NU Nusantara')

@section('content')
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <nav aria-label="breadcrumb" class="mb-4">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('kegiatan') }}">Kegiatan</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $activity->title }}</li>
                        </ol>
                    </nav>

                    <div class="card border-0 shadow-lg overflow-hidden">
                        <div class="position-relative">
                            @if($activity->image)
                                <img src="{{ asset('storage/' . $activity->image) }}" alt="{{ $activity->title }}" class="card-img-top" style="height: 300px; object-fit: cover;">
                            @else
                                <div class="bg-primary text-white d-flex align-items-center justify-content-center" style="height: 300px;">
                                    <i class="bi bi-calendar-event" style="font-size: 5rem;"></i>
                                </div>
                            @endif
                            <div class="position-absolute top-0 end-0 m-3">
                                <span class="badge bg-warning text-dark fs-5 shadow">{{ $activity->date->format('d F Y') }}</span>
                            </div>
                        </div>
                        <div class="card-body p-5">
                            <h1 class="card-title fw-bold mb-3">{{ $activity->title }}</h1>
                            
                            <div class="d-flex flex-wrap gap-4 mb-4 text-muted">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-geo-alt-fill text-primary"></i>
                                    <span>{{ $activity->location ?? 'Lokasi belum ditentukan' }}</span>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-clock-fill text-primary"></i>
                                    <span>{{ $activity->date->format('H:i') }} WIB</span>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-tag-fill text-primary"></i>
                                    <span>{{ $activity->category }}</span>
                                </div>
                            </div>

                            <div class="content fs-5 lh-lg mb-4 text-secondary">
                                {!! nl2br(e($activity->description)) !!}
                            </div>

                            @if($activity->date->isFuture())
                                <div class="alert alert-info border-0 shadow-sm d-flex align-items-center mb-4">
                                    <i class="bi bi-info-circle-fill fs-4 me-3"></i>
                                    <div>
                                        <strong>Pendaftaran Dibuka!</strong>
                                        <p class="mb-0">Segera daftarkan diri Anda sebelum tanggal pelaksanaan.</p>
                                    </div>
                                    <a href="#" class="btn btn-primary ms-auto">Daftar Sekarang</a>
                                </div>
                            @else
                                <div class="alert alert-secondary border-0 shadow-sm d-flex align-items-center mb-4">
                                    <i class="bi bi-check-circle-fill fs-4 me-3"></i>
                                    <div>
                                        <strong>Kegiatan Telah Selesai</strong>
                                        <p class="mb-0">Kegiatan ini sudah terlaksana pada {{ $activity->date->format('d F Y') }}.</p>
                                    </div>
                                </div>
                            @endif

                            <div class="d-flex justify-content-between align-items-center border-top pt-4 mt-4">
                                <div>
                                    <small class="text-muted d-block mb-1">Bagikan:</small>
                                    <div class="d-flex gap-2">
                                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}" target="_blank" class="btn btn-sm btn-outline-primary"><i class="bi bi-facebook"></i></a>
                                        <a href="https://twitter.com/intent/tweet?url={{ url()->current() }}&text={{ $activity->title }}" target="_blank" class="btn btn-sm btn-outline-info"><i class="bi bi-twitter"></i></a>
                                        <a href="https://wa.me/?text={{ $activity->title }}%20{{ url()->current() }}" target="_blank" class="btn btn-sm btn-outline-success"><i class="bi bi-whatsapp"></i></a>
                                    </div>
                                </div>
                                <a href="{{ route('kegiatan') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left me-2"></i>Kembali
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
