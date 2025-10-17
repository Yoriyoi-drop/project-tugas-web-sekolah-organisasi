{{-- About Page --}}
@extends('layouts.app')

@section('title', 'Tentang Kami')

@section('css')
    {{-- Keep CSS minimal here; site.css contains theme styles --}}
@endsection

@section('content')

    </nav>

    <section class="page-header">
        <div class="page-header-content">
            <h1 class="page-title">Tentang Kami</h1>
            <p class="page-subtitle">Mengenal lebih dekat {{ site_name() }} dan perjalanan kami</p>
        </div>
    </section>

    <section class="main-content">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-8">
                    <h2 class="fw-bold">Visi</h2>
                    <p>Menjadi lembaga pendidikan yang unggul dalam akademik dan karakter Islami.</p>

                    <h2 class="fw-bold mt-4">Misi</h2>
                    <ol>
                        <li>Mendidik siswa dengan metode terpadu antara ilmu umum dan agama.</li>
                        <li>Mengembangkan karakter kepemimpinan dan kemandirian.</li>
                        <li>Mendorong inovasi pembelajaran berbasis teknologi.</li>
                    </ol>

                    <h2 class="fw-bold mt-4">Nilai-Nilai</h2>
                    <ul>
                        <li>Akhlakul Karimah</li>
                        <li>Disiplin</li>
                        <li>Kerja Sama</li>
                    </ul>
                </div>
                <aside class="col-lg-4">
                    <div class="p-4 bg-light rounded-4 shadow-sm h-100">
                        <h5 class="fw-semibold">Kedisiplinan</h5>
                        <p>Membentuk karakter disiplin dan tangguh menghadapi tantangan.</p>
                    </div>
                </aside>
            </div>

            <!-- Fasilitas -->
            <section class="py-5">
                <div class="container">
                    <h2 class="fw-bold text-center mb-4">Fasilitas Sekolah</h2>
                    <div class="row g-4">
                        <div class="col-md-4">
                            <div class="card border-0 shadow-sm h-100">
                                <img src="{{ asset('images/lab-komputer.jpg') }}" class="card-img-top" alt="Lab Komputer">
                                <div class="card-body">
                                    <h5 class="card-title">Laboratorium Komputer</h5>
                                    <p class="card-text">Fasilitas komputer modern untuk menunjang pembelajaran digital.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-0 shadow-sm h-100">
                                <img src="{{ asset('images/perpustakaan.jpg') }}" class="card-img-top" alt="Perpustakaan">
                                <div class="card-body">
                                    <h5 class="card-title">Perpustakaan</h5>
                                    <p class="card-text">Ruang baca nyaman dengan koleksi buku keislaman dan umum.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-0 shadow-sm h-100">
                                <img src="{{ asset('images/masjid.jpg') }}" class="card-img-top" alt="Masjid">
                                <div class="card-body">
                                    <h5 class="card-title">Masjid</h5>
                                    <p class="card-text">Tempat ibadah utama bagi seluruh siswa dan guru madrasah.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>|
        </div>
    </section>
@endsection
