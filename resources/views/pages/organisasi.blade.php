@extends('layouts.app')

@section('title', 'Organisasi Siswa - MA NU Nusantara')

@section('content')
    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <div class="row justify-content-center text-center">
                <div class="col-lg-8">
                    <h1 class="display-4 fw-bold mb-3">
                        <i class="bi bi-people-fill me-3"></i>
                        Organisasi Siswa
                    </h1>
                    <p class="lead mb-0">Profil Lengkap Organisasi Nahdlatul Ulama di Madrasah</p>
                    <p class="mb-0">Membentuk karakter santri melalui kegiatan organisasi yang islami</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="py-5">
        <div class="container">
            <!-- Introduction -->
            <div class="row mb-5">
                <div class="col-lg-8 mx-auto text-center">
                    <h2 class="h3 mb-4">Organisasi Kesiswaan {{ site_name() }} </h2>
                    <p class="text-muted">Setiap organisasi di madrasah kami memiliki peran penting dalam membentuk karakter santri yang berakhlak mulia, cerdas, dan siap menghadapi tantangan zaman dengan tetap berpegang teguh pada nilai-nilai Islam Ahlussunnah Wal Jamaah.</p>
                </div>
            </div>

            <!-- Organizations Grid -->
            <div class="row g-4">
                @foreach(\App\Models\Organization::active()->ordered()->get() as $org)
                <div class="col-lg-6">
                    <div class="org-detail-card h-100">
                        <div class="org-header-section">
                            <div class="org-logo">
                                @if($org->image)
                                    <img src="{{ $org->image }}" alt="{{ $org->name }}" style="width: 64px; height: 64px; object-fit: contain;">
                                @else
                                    <i class="bi {{ $org->icon }}"></i>
                                @endif
                            </div>
                            <h3 class="org-name">{{ $org->name }}</h3>
                            <p class="org-tagline">"{{ $org->tagline }}"</p>
                        </div>
                        <div class="org-content">
                            <div class="org-section">
                                <h5><i class="bi bi-info-circle me-2"></i>Tentang Organisasi</h5>
                                <p>{{ $org->description }}</p>
                            </div>
                            @if($org->programs)
                            <div class="org-section">
                                <h5><i class="bi bi-target me-2"></i>Program Kerja</h5>
                                <ul class="org-list">
                                    @foreach($org->programs as $program)
                                    <li>{{ $program }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                            @if($org->leadership)
                            <div class="org-section">
                                <h5><i class="bi bi-people me-2"></i>Struktur Kepengurusan</h5>
                                <div class="leadership-grid">
                                    @foreach($org->leadership as $leader)
                                    <div class="leader-card">
                                        <div class="leader-name">{{ $leader['name'] }}</div>
                                        <div class="leader-position">{{ $leader['position'] }}</div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                            <div class="org-section">
                                <h5><i class="bi bi-telephone me-2"></i>Kontak</h5>
                                <div class="contact-info">
                                    @if($org->email)
                                    <div class="contact-item">
                                        <i class="bi bi-envelope-fill"></i>
                                        <span>{{ $org->email }}</span>
                                    </div>
                                    @endif
                                    @if($org->phone)
                                    <div class="contact-item">
                                        <i class="bi bi-phone-fill"></i>
                                        <span>{{ $org->phone }}</span>
                                    </div>
                                    @endif
                                    @if($org->location)
                                    <div class="contact-item">
                                        <i class="bi bi-geo-alt-fill"></i>
                                        <span>{{ $org->location }}</span>
                                    </div>
                                    @endif
                                </div>
                                <a href="{{ route('registration.show', $org) }}" class="btn btn-{{ $org->color }} w-100 mt-3">
                                    <i class="bi bi-person-plus me-2"></i>Bergabung
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Call to Action -->
            <div class="row mt-5">
                <div class="col-lg-8 mx-auto text-center">
                    <div class="card border-0 bg-light">
                        <div class="card-body p-5">
                            <h3 class="h4 mb-3">Tertarik Bergabung?</h3>
                            <p class="text-muted mb-4">Setiap organisasi memiliki keunikan dan manfaat tersendiri dalam membentuk karakter santri. Pilih organisasi yang sesuai dengan minat dan bakat Anda.</p>
                            <div class="d-flex flex-wrap gap-3 justify-content-center">
                                <a href="{{ route('kontak') }}" class="btn btn-primary">
                                    <i class="bi bi-envelope me-2"></i>Hubungi Kami
                                </a>
                                <a href="{{ route('kegiatan') }}" class="btn btn-outline-primary">
                                    <i class="bi bi-calendar-event me-2"></i>Lihat Kegiatan
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
