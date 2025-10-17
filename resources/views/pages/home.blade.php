@extends('layouts.app')

@section('title', 'Beranda - MA NU Nusantara')

@section('content')
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row justify-content-center text-center">
                <div class="col-12 col-lg-10 col-xl-8">
                    <div class="mb-4">
                        <i class="bi bi-mortarboard-fill" style="font-size: clamp(3rem, 8vw, 5rem);"></i>
                    </div>
                    <h1 class="display-3 fw-bold mb-4">
                        Selamat Datang di<br class="d-none d-sm-block">
                        <span class="text-warning">{{ site_name() }}</span>
                    </h1>
                    <p class="lead mb-4">Membentuk generasi santri yang berakhlak mulia, cerdas, dan siap menghadapi tantangan zaman dengan tetap berpegang teguh pada nilai-nilai Islam Ahlussunnah Wal Jamaah.</p>
                    
                    <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                        <a href="{{ route('organisasi') }}" class="btn btn-warning btn-lg">
                            <i class="bi bi-people-fill me-2"></i>Lihat Organisasi
                        </a>
                        <a href="{{ route('tentang') }}" class="btn btn-outline-light btn-lg">
                            <i class="bi bi-info-circle me-2"></i>Tentang Kami
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Stats -->
            <div class="row justify-content-center text-center mt-5">
                @foreach(\App\Models\Statistic::active()->ordered()->get() as $stat)
                <div class="col-6 col-md-3 mb-3">
                    <div class="h2 fw-bold mb-1" style="font-size: clamp(1.5rem, 4vw, 2.5rem);">{{ $stat->value }}</div>
                    <p class="mb-0 text-light opacity-75 small">{{ $stat->description }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Organisasi Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center mb-5">
                    <h2 class="display-5 fw-bold mb-3">Organisasi Siswa</h2>
                    <p class="lead text-muted">Bergabunglah untuk mengembangkan potensi, kepemimpinan, dan karakter santri NU melalui berbagai organisasi yang tersedia.</p>
                </div>
            </div>

            <div class="row g-4">
                @foreach(\App\Models\Organization::active()->ordered()->get() as $org)
                <div class="col-12 col-sm-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="bi {{ $org->icon }} text-{{ $org->color }}" style="font-size: clamp(2.5rem, 6vw, 3.5rem);"></i>
                            </div>
                            <h5 class="fw-bold mb-2">{{ $org->name }}</h5>
                            <p class="text-{{ $org->color }} small fw-semibold mb-3">{{ $org->type }}</p>
                            <p class="text-muted mb-3 small">{{ Str::limit($org->description, 100) }}</p>
                            <div class="mb-3">
                                @if($org->tags)
                                    @foreach($org->tags as $tag)
                                        <span class="badge bg-light text-dark border me-1 mb-1 small">{{ $tag }}</span>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="row mt-5">
                <div class="col-lg-6 mx-auto text-center">
                    <x-button href="{{ route('organisasi') }}" variant="primary" size="lg">
                        <i class="bi bi-arrow-right me-2"></i>Lihat Semua Organisasi
                    </x-button>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center mb-5">
                    <h2 class="display-5 fw-bold mb-3">Mengapa Bergabung?</h2>
                    <p class="lead text-muted">Manfaat yang akan Anda dapatkan dengan bergabung dalam organisasi siswa di MA NU Nusantara.</p>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-12 col-sm-6 col-lg-4">
                    <div class="text-center">
                        <div class="mb-3">
                            <i class="bi bi-trophy-fill text-warning" style="font-size: clamp(2.5rem, 6vw, 3.5rem);"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Pengembangan Kepemimpinan</h5>
                        <p class="text-muted">Melatih kemampuan memimpin, mengorganisir, dan mengambil keputusan dengan bijak sesuai nilai-nilai Islam.</p>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-4">
                    <div class="text-center">
                        <div class="mb-3">
                            <i class="bi bi-people-fill text-primary" style="font-size: clamp(2.5rem, 6vw, 3.5rem);"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Networking & Persahabatan</h5>
                        <p class="text-muted">Membangun jaringan pertemanan yang luas dengan sesama santri dari berbagai latar belakang.</p>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-4">
                    <div class="text-center">
                        <div class="mb-3">
                            <i class="bi bi-star-fill text-success" style="font-size: clamp(2.5rem, 6vw, 3.5rem);"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Pengembangan Bakat</h5>
                        <p class="text-muted">Menyalurkan dan mengembangkan bakat serta minat dalam berbagai bidang sesuai passion Anda.</p>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-4">
                    <div class="text-center">
                        <div class="mb-3">
                            <i class="bi bi-heart-fill text-danger" style="font-size: clamp(2.5rem, 6vw, 3.5rem);"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Karakter Islami</h5>
                        <p class="text-muted">Membentuk karakter yang kuat berdasarkan nilai-nilai Islam Ahlussunnah Wal Jamaah.</p>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-4">
                    <div class="text-center">
                        <div class="mb-3">
                            <i class="bi bi-lightbulb-fill text-info" style="font-size: clamp(2.5rem, 6vw, 3.5rem);"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Kreativitas & Inovasi</h5>
                        <p class="text-muted">Mengembangkan kreativitas dan kemampuan berinovasi dalam berbagai kegiatan dan program.</p>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-4">
                    <div class="text-center">
                        <div class="mb-3">
                            <i class="bi bi-globe text-secondary" style="font-size: clamp(2.5rem, 6vw, 3.5rem);"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Wawasan Kebangsaan</h5>
                        <p class="text-muted">Memperluas wawasan tentang kebangsaan, keislaman, dan peran sebagai generasi penerus bangsa.</p>
                    </div>
                </div>
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
                    <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                        <a href="{{ route('kontak') }}" class="btn btn-warning btn-lg">
                            <i class="bi bi-envelope-fill me-2"></i>Hubungi Kami
                        </a>
                        <a href="{{ route('organisasi') }}" class="btn btn-outline-light btn-lg">
                            <i class="bi bi-arrow-right me-2"></i>Lihat Organisasi
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection