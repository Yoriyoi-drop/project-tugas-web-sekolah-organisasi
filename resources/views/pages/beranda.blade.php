@extends('layouts.app')

@section('title', 'Beranda - MA NU Nusantara')

@section('content')
    <!-- Hero Section -->
    <section class="hero-section py-5 d-flex align-items-center" style="min-height: 80vh;">
        <div class="container" data-aos="fade-up">
            <div class="row align-items-center">
                <div class="col-lg-7 text-center text-lg-start">
                    <div class="mb-4 d-inline-block p-3 bg-white rounded-4 shadow-sm">
                        <img src="/images/logo.svg" alt="Logo" style="width: 80px; height: 80px; object-fit: contain;">
                    </div>
                    <h1 class="display-3 fw-bold mb-4">
                        Selamat Datang di<br>
                        <span class="text-warning"> {{ site_name() }} </span>
                    </h1>
                    <p class="lead mb-5 opacity-90">Membentuk generasi santri yang berakhlak mulia, cerdas, dan siap menghadapi tantangan zaman dengan tetap berpegang teguh pada nilai-nilai Islam Ahlussunnah Wal Jamaah.</p>

                    <div class="d-flex flex-wrap gap-3 justify-content-center justify-content-lg-start">
                        <a href="{{ route('ppdb.index') }}" class="btn btn-warning btn-lg px-4 py-3 fw-bold shadow">
                            <i class="bi bi-pencil-square me-2"></i>Daftar PPDB Online
                        </a>
                        <a href="{{ route('tentang') }}" class="btn btn-outline-light btn-lg px-4 py-3 fw-bold">
                            <i class="bi bi-info-circle me-2"></i>Tentang Kami
                        </a>
                    </div>
                </div>
                <div class="col-lg-5 d-none d-lg-block">
                    <div class="position-relative">
                        <div class="glass-morphism p-3 rounded-4 shadow-lg rotate-3">
                             <img src="https://images.unsplash.com/photo-1523050854058-8df90110c9f1?q=80&w=800" class="img-fluid rounded-3 shadow-sm" alt="School Life">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats -->
            <div class="row justify-content-center text-center mt-5" data-aos="fade-up" data-aos-delay="200">
                <div class="col-lg-10">
                    <div class="glass-morphism p-4 rounded-4 shadow-sm border-0">
                        <div class="row">
                            @foreach($statistics ?? [] as $stat)
                            <div class="col-md-3 col-6 mb-3 mb-md-0">
                                <div class="h2 fw-bold mb-1 text-warning">{{ $stat->value }}</div>
                                <p class="mb-0 text-white small opacity-75 text-uppercase fw-semibold">{{ $stat->description }}</p>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- PPDB Banner -->
    <section class="py-4 bg-warning">
        <div class="container text-center">
            <div class="d-flex flex-column flex-md-row align-items-center justify-content-center gap-4">
                <div class="text-dark d-flex align-items-center">
                    <i class="bi bi-megaphone-fill fs-2 me-3"></i>
                    <h5 class="fw-bold mb-0 text-start">Penerimaan Peserta Didik Baru (PPDB) Tahun Ajaran 2024/2025 Telah Dibuka!</h5>
                </div>
                <a href="{{ route('ppdb.index') }}" class="btn btn-primary btn-lg shadow-sm">
                    <i class="bi bi-info-circle me-2"></i>Informasi Selengkapnya
                </a>
                <a href="{{ route('ppdb.create') }}" class="btn btn-outline-dark btn-lg">
                    <i class="bi bi-pencil-square me-2"></i>Daftar Sekarang
                </a>
            </div>
        </div>
    </section>

    <!-- Organisasi Section -->
    <section class="py-5 bg-light" data-aos="fade-up">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center mb-5">
                    <h2 class="display-5 fw-bold mb-3 text-gradient">Organisasi Siswa</h2>
                    <p class="lead text-muted">Bergabunglah untuk mengembangkan potensi, kepemimpinan, dan karakter santri NU melalui berbagai organisasi yang tersedia.</p>
                </div>
            </div>

            <div class="row g-4">
                @foreach($organizations ?? [] as $org)
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    <x-card class="h-100 border-0 shadow-sm hover-up">
                        <div class="text-center p-3">
                            <div class="mb-4 d-inline-flex align-items-center justify-content-center rounded-circle bg-white shadow-sm" style="width: 80px; height: 80px;">
                                @if($org->image)
                                    <img src="{{ $org->image }}" alt="{{ $org->name }}" class="img-fluid" style="width: 48px; height: 48px; object-fit: contain;">
                                @else
                                    <i class="bi {{ $org->icon }} text-primary" style="font-size: 2.5rem;"></i>
                                @endif
                            </div>
                            <h4 class="fw-bold mb-2">{{ $org->name }}</h4>
                            <p class="text-primary small fw-semibold mb-3">{{ $org->type }}</p>
                            <p class="text-muted mb-4">{{ Str::limit($org->description, 120) }}</p>
                            
                            <div class="d-grid gap-2">
                                <a href="{{ route('organisasi.show', $org) }}" class="btn btn-outline-primary rounded-pill">
                                    Detail <i class="bi bi-arrow-right ms-2"></i>
                                </a>
                                <a href="{{ route('registration.show', $org) }}" class="btn btn-primary rounded-pill shadow-sm">
                                    Gabung Sekarang
                                </a>
                            </div>
                        </div>
                    </x-card>
                </div>
                @endforeach
            </div>

            <div class="row mt-5">
                <div class="col-lg-6 mx-auto text-center">
                    <a href="{{ route('organisasi') }}" class="btn btn-outline-primary btn-lg rounded-pill px-5">
                        <i class="bi bi-grid-fill me-2"></i>Jelajahi Semua Organisasi
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Organization Modals -->
    @foreach($organizations ?? [] as $org)
    <div class="modal fade" id="orgModal{{ $org->id }}" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-{{ $org->color }} text-white">
                    <div class="d-flex align-items-center">
                        @if($org->image)
                            <img src="{{ $org->image }}" alt="{{ $org->name }}" style="width: 32px; height: 32px; object-fit: contain; filter: brightness(0) invert(1);" class="me-3">
                        @endif
                        <div>
                            <h5 class="modal-title mb-0">{{ $org->name }}</h5>
                            <small class="opacity-75">{{ $org->type }}</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <p class="fst-italic text-{{ $org->color }}">"{{ $org->tagline }}"</p>
                        <p class="text-muted">{{ $org->description }}</p>
                    </div>

                    @if($org->programs)
                    <div class="mb-4">
                        <h6 class="fw-bold text-{{ $org->color }} mb-3"><i class="bi bi-target me-2"></i>Program Kerja</h6>
                        <div class="row">
                            @foreach($org->programs as $program)
                            <div class="col-md-6 mb-2">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-check-circle-fill text-success me-2 mt-1"></i>
                                    <span class="small">{{ $program }}</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    @if($org->leadership)
                    <div class="mb-4">
                        <h6 class="fw-bold text-{{ $org->color }} mb-3"><i class="bi bi-people me-2"></i>Struktur Kepengurusan</h6>
                        <div class="row">
                            @foreach($org->leadership as $leader)
                            <div class="col-md-6 mb-2">
                                <div class="card border-0 bg-light">
                                    <div class="card-body py-2 px-3">
                                        <div class="fw-semibold">{{ $leader['name'] }}</div>
                                        <small class="text-muted">{{ $leader['position'] }}</small>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="fw-bold text-{{ $org->color }} mb-3"><i class="bi bi-telephone me-2"></i>Kontak</h6>
                            @if($org->email)
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-envelope-fill text-muted me-2"></i>
                                <small>{{ $org->email }}</small>
                            </div>
                            @endif
                            @if($org->phone)
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-phone-fill text-muted me-2"></i>
                                <small>{{ $org->phone }}</small>
                            </div>
                            @endif
                            @if($org->location)
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-geo-alt-fill text-muted me-2"></i>
                                <small>{{ $org->location }}</small>
                            </div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            @if($org->tags)
                            <h6 class="fw-bold text-{{ $org->color }} mb-3"><i class="bi bi-tags me-2"></i>Fokus Kegiatan</h6>
                            @foreach($org->tags as $tag)
                                <span class="badge bg-{{ $org->color }} me-1 mb-1">{{ $tag }}</span>
                            @endforeach
                            @endif
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <a href="{{ route('registration.show', $org) }}" class="btn btn-{{ $org->color }}">
                        <i class="bi bi-person-plus me-2"></i>Bergabung Sekarang
                    </a>
                </div>
                
            </div>
        </div>
    </div>
    @endforeach

    <!-- Latest News Section -->
    <section class="py-5">
        <div class="container">
            <div class="d-flex justify-content-between align-items-end mb-5">
                <div>
                    <h2 class="display-5 fw-bold mb-0">Warta Akademik</h2>
                    <p class="lead text-muted mb-0">Informasi terbaru seputar kegiatan dan prestasi sekolah.</p>
                </div>
                <a href="{{ route('blog') }}" class="btn btn-outline-primary d-none d-md-inline-block">
                    Lihat Semua Berita <i class="bi bi-arrow-right ms-2"></i>
                </a>
            </div>

            <div class="row g-4">
                @foreach($latestPosts ?? [] as $post)
                <div class="col-lg-4 col-md-6">
                    <x-card class="h-100 border-0 shadow-sm hover-up">
                        <div class="mb-3 position-relative">
                            <div class="bg-{{ $post->color }} text-white rounded d-flex align-items-center justify-content-center" style="height: 160px;">
                                <i class="bi {{ $post->icon ?? 'bi-journal-text' }}" style="font-size: 3.5rem;"></i>
                            </div>
                            <span class="position-absolute top-0 end-0 badge bg-warning text-dark m-2">{{ $post->category }}</span>
                        </div>
                        <h5 class="fw-bold mb-2">{{ $post->title }}</h5>
                        <p class="text-muted small mb-3">{{ Str::limit(strip_tags($post->excerpt), 100) }}</p>
                        <div class="d-flex justify-content-between align-items-center mt-auto">
                            <small class="text-muted"><i class="bi bi-calendar-event me-1"></i>{{ ($post->published_at ?? $post->created_at)->format('d M Y') }}</small>
                            <a href="{{ route('blog.show', $post) }}" class="text-{{ $post->color }} text-decoration-none fw-bold">
                                Baca <i class="bi bi-chevron-right ms-1"></i>
                            </a>
                        </div>
                    </x-card>
                </div>
                @endforeach
            </div>
            
            <div class="text-center mt-4 d-md-none">
                <a href="{{ route('blog') }}" class="btn btn-primary">Lihat Semua Berita</a>
            </div>
        </div>
    </section>

    <!-- Upcoming Events Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row align-items-center mb-5">
                <div class="col-lg-6">
                    <h2 class="display-5 fw-bold mb-3">Agenda Terdekat</h2>
                    <p class="lead text-muted">Ayo berpartisipasi dalam agenda kegiatan mendatang di madrasah kami.</p>
                </div>
                <div class="col-lg-6 text-lg-end">
                    <a href="{{ route('kegiatan') }}" class="btn btn-primary">
                        Lihat Seluruh Agenda <i class="bi bi-calendar3 ms-2"></i>
                    </a>
                </div>
            </div>

            <div class="row g-4">
                @forelse($upcomingActivities ?? [] as $activity)
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4 d-flex">
                            <div class="text-center me-4">
                                <div class="bg-primary text-white rounded p-2 px-3 mb-1">
                                    <div class="h4 fw-bold mb-0">{{ $activity->date->format('d') }}</div>
                                    <small>{{ $activity->date->format('M') }}</small>
                                </div>
                                <small class="text-muted">{{ $activity->date->format('Y') }}</small>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="fw-bold mb-1">{{ $activity->title }}</h5>
                                <p class="text-muted small mb-2"><i class="bi bi-geo-alt-fill text-danger me-1"></i>{{ $activity->location }}</p>
                                <a href="{{ route('kegiatan.show', $activity) }}" class="btn btn-link text-primary p-0 text-decoration-none fw-bold">
                                    Detail Kegiatan <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="alert alert-info border-0 shadow-sm text-center py-4">
                        <i class="bi bi-calendar-x fs-1 mb-2 d-block"></i>
                        <h5>Belum ada agenda terdekat</h5>
                        <p class="mb-0">Pantau terus halaman ini untuk informasi kegiatan mendatang.</p>
                    </div>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5" data-aos="fade-up">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center mb-5">
                    <h2 class="display-5 fw-bold mb-3 text-gradient">Mengapa Harus Bergabung?</h2>
                    <p class="lead text-muted">Dapatkan pengalaman belajar dan berorganisasi terbaik untuk masa depan santri yang cemerlang.</p>
                </div>
            </div>

            <div class="row g-4">
                @php
                    $features = [
                        ['icon' => 'bi-trophy-fill', 'color' => 'warning', 'title' => 'Pengembangan Bakat', 'desc' => 'Menemukan dan mengasah potensi kepemimpinan dan kreativitas santri.'],
                        ['icon' => 'bi-people-fill', 'color' => 'primary', 'title' => 'Social Networking', 'desc' => 'Membangun relasi yang kuat antar santri dan alumni di seluruh nusantara.'],
                        ['icon' => 'bi-star-fill', 'color' => 'warning', 'title' => 'Prestasi Akademik', 'desc' => 'Mendukung pencapaian prestasi di tingkat regional maupun nasional.'],
                        ['icon' => 'bi-heart-pulse-fill', 'color' => 'danger', 'title' => 'Karakter Aswaja', 'desc' => 'Menanamkan nilai-nilai luhur Islam Ahlussunnah Wal Jamaah Annahdliyah.'],
                        ['icon' => 'bi-shield-check', 'color' => 'success', 'title' => 'Lingkungan Aman', 'desc' => 'Suasana belajar yang nyaman, religius, dan penuh kekeluargaan.'],
                        ['icon' => 'bi-lightbulb-fill', 'color' => 'info', 'title' => 'Inovasi Digital', 'desc' => 'Pemanfaatan teknologi modern dalam proses pembelajaran dan administrasi.']
                    ];
                @endphp

                @foreach($features as $f)
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
                    <div class="card h-100 border-0 shadow-sm p-4 hover-up">
                        <div class="mb-4 d-inline-flex align-items-center justify-content-center rounded-3 bg-{{ $f['color'] }} bg-opacity-10 text-{{ $f['color'] }}" style="width: 60px; height: 60px;">
                            <i class="bi {{ $f['icon'] }} fs-2"></i>
                        </div>
                        <h5 class="fw-bold mb-3">{{ $f['title'] }}</h5>
                        <p class="text-muted mb-0 small">{{ $f['desc'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-5 bg-primary text-white">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h2 class="display-5 fw-bold mb-3">Siap Bergabung?</h2>
                    <p class="lead mb-4">Temukan passion dan pengalaman berorganisasi yang tak terlupakan bersama kami!</p>
                    <div class="d-flex flex-wrap gap-3 justify-content-center">
                        <x-button href="{{ route('kontak') }}" variant="warning" size="lg">
                            <i class="bi bi-envelope-fill me-2"></i>Hubungi Kami
                        </x-button>
                        <x-button href="{{ route('organisasi') }}" variant="outline-primary" size="lg" class="text-white border-white">
                            <i class="bi bi-arrow-right me-2"></i>Lihat Organisasi
                        </x-button>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
