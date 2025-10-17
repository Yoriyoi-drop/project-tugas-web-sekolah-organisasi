@extends('layouts.app')

@section('title', 'Tentang Kami - MA NU Nusantara')

@section('content')
    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <div class="row justify-content-center text-center">
                <div class="col-lg-8">
                    <div class="mb-4">
                        <i class="bi bi-building" style="font-size: 4rem;"></i>
                    </div>
                    <h1 class="display-3 fw-bold mb-4">Tentang Kami</h1>
                    <p class="lead mb-0">Mengenal Lebih Dekat Madrasah Aliyah NU Nusantara</p>
                    <p class="mb-0">Lembaga pendidikan Islam yang membentuk generasi santri berakhlak mulia</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="py-5">
        <div class="container">
            <!-- Introduction -->
            <div class="row align-items-center mb-5">
                <div class="col-lg-2 text-center mb-4 mb-lg-0">
                    <div class="org-logo mx-auto">
                        <i class="bi bi-mortarboard-fill"></i>
                    </div>
                </div>
                <div class="col-lg-10">
                    <p class="lead">
                        Madrasah Aliyah Nahdlatul Ulama Nusantara adalah lembaga pendidikan Islam yang berdiri sejak tahun 1985, berkomitmen untuk membentuk generasi santri yang berakhlak mulia, cerdas, dan berkarakter. Dengan menggabungkan kurikulum nasional dan nilai-nilai ke-NU-an, kami menghasilkan lulusan yang siap menghadapi tantangan zaman dengan tetap berpegang teguh pada ajaran Ahlussunnah Wal Jamaah.
                    </p>

                    <!-- Stats -->
                    <div class="row g-3 mt-4">
                        <div class="col-6 col-md-3">
                            <x-card class="text-center h-100">
                                <div class="h3 text-primary fw-bold mb-1">39</div>
                                <small class="text-muted">Tahun Berdiri</small>
                            </x-card>
                        </div>
                        <div class="col-6 col-md-3">
                            <x-card class="text-center h-100">
                                <div class="h3 text-success fw-bold mb-1">850+</div>
                                <small class="text-muted">Siswa Aktif</small>
                            </x-card>
                        </div>
                        <div class="col-6 col-md-3">
                            <x-card class="text-center h-100">
                                <div class="h3 text-warning fw-bold mb-1">65</div>
                                <small class="text-muted">Tenaga Pendidik</small>
                            </x-card>
                        </div>
                        <div class="col-6 col-md-3">
                            <x-card class="text-center h-100">
                                <div class="h3 text-info fw-bold mb-1">2500+</div>
                                <small class="text-muted">Alumni</small>
                            </x-card>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sejarah & Visi Misi -->
            <div class="row mb-5">
                <div class="col-lg-8 mx-auto text-center mb-4">
                    <h2 class="display-5 fw-bold mb-3">Sejarah & Visi Misi</h2>
                </div>
            </div>
            <div class="row g-4 mb-5">
                <div class="col-lg-6">
                    <x-card class="h-100">
                        <div class="d-flex align-items-start gap-3">
                            <i class="bi bi-journal-text text-primary" style="font-size: 2rem;"></i>
                            <div>
                                <h5 class="mb-3">Sejarah Singkat</h5>
                                <p>MA NU Nusantara didirikan pada tahun 1985 oleh para tokoh NU setempat dengan tujuan memberikan pendidikan berkualitas yang memadukan ilmu agama dan umum. Berawal dari 3 kelas dengan 45 siswa, kini telah berkembang menjadi salah satu madrasah terbaik di wilayah ini.</p>
                                <p class="mb-0">Perjalanan panjang selama hampir 4 dekade telah menghasilkan ribuan alumni yang tersebar di berbagai profesi, mulai dari ulama, akademisi, pengusaha, hingga pejabat pemerintahan.</p>
                            </div>
                        </div>
                    </x-card>
                </div>
                <div class="col-lg-6">
                    <x-card class="h-100">
                        <div class="d-flex align-items-start gap-3">
                            <i class="bi bi-bullseye text-success" style="font-size: 2rem;"></i>
                            <div>
                                <h5 class="mb-3">Visi & Misi</h5>
                                <p class="mb-2"><strong>Visi:</strong></p>
                                <p class="mb-3">"Menjadi lembaga pendidikan Islam terdepan yang menghasilkan generasi santri berakhlak mulia, cerdas, dan berkarakter Ahlussunnah Wal Jamaah."</p>
                                <p class="mb-2"><strong>Misi:</strong></p>
                                <ul class="mb-0">
                                    <li>Menyelenggarakan pendidikan berkualitas tinggi</li>
                                    <li>Menanamkan nilai-nilai ke-NU-an dalam kehidupan</li>
                                    <li>Mengembangkan potensi siswa secara optimal</li>
                                    <li>Membangun karakter islami yang kuat</li>
                                </ul>
                            </div>
                        </div>
                    </x-card>
                </div>
            </div>

            <!-- Nilai-Nilai NU -->
            <div class="row mb-5">
                <div class="col-lg-8 mx-auto text-center mb-4">
                    <h2 class="display-5 fw-bold mb-3">Nilai-Nilai Nahdlatul Ulama</h2>
                    <p class="lead text-muted">Empat pilar utama yang menjadi landasan pendidikan di MA NU Nusantara</p>
                </div>
            </div>
            <div class="row g-4 mb-5">
                @foreach(\App\Models\Value::active()->ordered()->get() as $value)
                <div class="col-lg-3 col-md-6">
                    <x-card class="text-center h-100">
                        <i class="bi {{ $value->icon }} text-{{ $value->color }} mb-3" style="font-size: 3rem;"></i>
                        <h5 class="fw-bold mb-3">{{ $value->title }}</h5>
                        <p class="text-muted mb-0">{{ $value->description }}</p>
                    </x-card>
                </div>
                @endforeach
            </div>

            <!-- Fasilitas -->
            <div class="row mb-5">
                <div class="col-lg-8 mx-auto text-center mb-4">
                    <h2 class="display-5 fw-bold mb-3">Fasilitas Sekolah</h2>
                    <p class="lead text-muted">Fasilitas modern yang mendukung proses pembelajaran dan pengembangan siswa</p>
                </div>
            </div>
            <div class="row g-4 mb-5">
                @foreach(\App\Models\Facility::active()->ordered()->get() as $facility)
                <div class="col-lg-4 col-md-6">
                    <x-card class="h-100">
                        <div class="d-flex align-items-start gap-3">
                            <i class="bi {{ $facility->icon }} text-primary" style="font-size: 2rem;"></i>
                            <div>
                                <h6 class="fw-bold mb-2">{{ $facility->title }}</h6>
                                <p class="text-muted mb-0">{{ $facility->description }}</p>
                            </div>
                        </div>
                    </x-card>
                </div>
                @endforeach
            </div>
        </div>
    </section>
@endsectionility)
                <div class="col-lg-4 col-md-6">
                    <x-card class="h-100">
                        <div class="d-flex align-items-start gap-3">
                            <i class="bi {{ $facility['icon'] }} text-primary" style="font-size: 2rem;"></i>
                            <div>
                                <h6 class="fw-bold mb-2">{{ $facility['title'] }}</h6>
                                <p class="text-muted mb-0">{{ $facility['desc'] }}</p>
                            </div>
                        </div>
                    </x-card>
                </div>
                @endforeach
            </div>

            <!-- Prestasi -->
            <div class="row mb-5">
                <div class="col-lg-8 mx-auto text-center mb-4">
                    <h2 class="display-5 fw-bold mb-3">Prestasi Sekolah</h2>
                    <p class="lead text-muted">Pencapaian membanggakan yang diraih siswa-siswi MA NU Nusantara</p>
                </div>
            </div>
            <div class="row g-4">
                @php
                    $achievements = [
                        ['icon' => 'bi-trophy-fill', 'title' => 'Juara 1 Lomba Karya Tulis Ilmiah Tingkat Provinsi', 'year' => '2024'],
                        ['icon' => 'bi-award-fill', 'title' => 'Juara 1 Kompetisi Sains Madrasah Nasional', 'year' => '2023'],
                        ['icon' => 'bi-book-fill', 'title' => 'Juara 2 Musabaqah Tilawatil Qur\'an Tingkat Nasional', 'year' => '2023'],
                        ['icon' => 'bi-palette-fill', 'title' => 'Juara 1 Festival Seni Budaya Islam Tingkat Provinsi', 'year' => '2024'],
                        ['icon' => 'bi-laptop-fill', 'title' => 'Juara 3 Olimpiade Teknologi Informasi', 'year' => '2023'],
                        ['icon' => 'bi-star-fill', 'title' => 'Madrasah Berprestasi Tingkat Kabupaten', 'year' => '2022-2024']
                    ];
                @endphp
                @foreach($achievements as $achievement)
                <div class="col-lg-4 col-md-6">
                    <x-card class="h-100">
                        <div class="d-flex align-items-start gap-3">
                            <i class="bi {{ $achievement['icon'] }} text-warning" style="font-size: 2rem;"></i>
                            <div>
                                <h6 class="fw-bold mb-2">{{ $achievement['title'] }}</h6>
                                <span class="badge bg-light text-dark">{{ $achievement['year'] }}</span>
                            </div>
                        </div>
                    </x-card>
                </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection