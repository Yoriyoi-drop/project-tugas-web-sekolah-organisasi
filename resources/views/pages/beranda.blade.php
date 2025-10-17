@extends('layouts.app')

@section('title', 'Beranda - MA NU Nusantara')

@section('content')
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row justify-content-center text-center">
                <div class="col-lg-8">
                    <div class="mb-4" style="width: 64px; height: 64px; margin: 0 auto;">
                        <img src="/images/logo.svg" alt="MA NU Nusantara" style="width: 64px; height: 64px; object-fit: contain;">
                    </div>
                    <h1 class="display-3 fw-bold mb-4">
                        Selamat Datang di<br>
                        <span class="text-warning"> {{ site_name() }} </span>
                    </h1>
                    <p class="lead mb-4">Membentuk generasi santri yang berakhlak mulia, cerdas, dan siap menghadapi tantangan zaman dengan tetap berpegang teguh pada nilai-nilai Islam Ahlussunnah Wal Jamaah.</p>

                    <div class="d-flex flex-wrap gap-3 justify-content-center">
                        <x-button href="{{ route('organisasi') }}" variant="warning" size="lg">
                            <i class="bi bi-people-fill me-2"></i>Lihat Organisasi
                        </x-button>
                        <x-button href="{{ route('tentang') }}" variant="outline-primary" size="lg">
                            <i class="bi bi-info-circle me-2"></i>Tentang Kami
                        </x-button>
                    </div>
                </div>
            </div>

            <!-- Stats -->
            <div class="row justify-content-center text-center mt-5">
                @foreach($statistics ?? [] as $stat)
                <div class="col-md-3 col-6 mb-3">
                    <div class="h2 fw-bold mb-1">{{ $stat->value }}</div>
                    <p class="mb-0 text-light opacity-75">{{ $stat->description }}</p>
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
                @foreach($organizations ?? [] as $org)
                <div class="col-lg-4 col-md-6">
                    <x-card class="h-100 border-0 shadow-sm">
                        <div class="text-center">
                            <div class="mb-3" style="width: 48px; height: 48px; margin: 0 auto;">
                                @if($org->image)
                                    <img src="{{ $org->image }}" alt="{{ $org->name }}" class="img-fluid" style="width: 48px; height: 48px; object-fit: contain;">
                                @else
                                    <i class="bi {{ $org->icon }} text-{{ $org->color }}" style="font-size: 3rem;"></i>
                                @endif
                            </div>
                            <h5 class="fw-bold mb-2">{{ $org->name }}</h5>
                            <p class="text-{{ $org->color }} small fw-semibold mb-3">{{ $org->type }}</p>
                            <p class="text-muted mb-3">{{ $org->description }}</p>
                            <div class="mb-3">
                                @if($org->tags)
                                    @foreach($org->tags as $tag)
                                        <span class="badge bg-light text-dark border me-1 mb-1">{{ $tag }}</span>
                                    @endforeach
                                @endif
                            </div>
                            <div class="d-grid gap-2">
                                <a href="{{ route('registration.show', $org) }}" class="btn btn-{{ $org->color }}">
                                    <i class="bi bi-person-plus me-2"></i>Bergabung
                                </a>
                                <button type="button" class="btn btn-outline-{{ $org->color }}" data-bs-toggle="modal" data-bs-target="#orgModal{{ $org->id }}">
                                    <i class="bi bi-info-circle me-2"></i>Selengkapnya
                                </button>
                            </div>
                        </div>
                    </x-card>
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
                <div class="col-lg-4 col-md-6">
                    <div class="text-center">
                        <div class="mb-3">
                            <i class="bi bi-trophy-fill text-warning" style="font-size: 3rem;"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Pengembangan Kepemimpinan</h5>
                        <p class="text-muted">Melatih kemampuan memimpin, mengorganisir, dan mengambil keputusan dengan bijak sesuai nilai-nilai Islam.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="text-center">
                        <div class="mb-3">
                            <i class="bi bi-people-fill text-primary" style="font-size: 3rem;"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Networking & Persahabatan</h5>
                        <p class="text-muted">Membangun jaringan pertemanan yang luas dengan sesama santri dari berbagai latar belakang.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="text-center">
                        <div class="mb-3">
                            <i class="bi bi-star-fill text-success" style="font-size: 3rem;"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Pengembangan Bakat</h5>
                        <p class="text-muted">Menyalurkan dan mengembangkan bakat serta minat dalam berbagai bidang sesuai passion Anda.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="text-center">
                        <div class="mb-3">
                            <i class="bi bi-heart-fill text-danger" style="font-size: 3rem;"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Karakter Islami</h5>
                        <p class="text-muted">Membentuk karakter yang kuat berdasarkan nilai-nilai Islam Ahlussunnah Wal Jamaah.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="text-center">
                        <div class="mb-3">
                            <i class="bi bi-lightbulb-fill text-info" style="font-size: 3rem;"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Kreativitas & Inovasi</h5>
                        <p class="text-muted">Mengembangkan kreativitas dan kemampuan berinovasi dalam berbagai kegiatan dan program.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="text-center">
                        <div class="mb-3">
                            <i class="bi bi-globe text-secondary" style="font-size: 3rem;"></i>
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
