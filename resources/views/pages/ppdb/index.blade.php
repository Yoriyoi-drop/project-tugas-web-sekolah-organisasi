@extends('layouts.app')

@section('title', 'PPDB 2024/2025 - MA NU Nusantara')

@section('content')
    <!-- Hero Section -->
    <section class="py-5 position-relative overflow-hidden" style="background: linear-gradient(135deg, #0f766e 0%, #14b8a6 100%);">
        <div class="container py-lg-5 position-relative z-1">
            <div class="row align-items-center">
                <div class="col-lg-7 text-white">
                    <span class="badge bg-warning text-dark fw-bold mb-3 px-3 py-2 shadow-sm">PENDAFTARAN DIBUKA</span>
                    <h1 class="display-3 fw-bold mb-4">Penerimaan Peserta Didik Baru (PPDB)<br>Tahun Ajaran 2024/2025</h1>
                    <p class="lead mb-5 opacity-90">Jadilah bagian dari Madrasah Aliyah NU Nusantara. Kami membentuk generasi santri yang unggul secara intelektual dan kokoh dalam aqidah Ahlussunnah Wal Jamaah.</p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="{{ route('ppdb.create') }}" class="btn btn-warning btn-lg px-4 py-3 fw-bold shadow">
                            <i class="bi bi-pencil-square me-2"></i>Daftar Sekarang
                        </a>
                        <a href="#jadwal" class="btn btn-outline-light btn-lg px-4 py-3 fw-bold">
                            <i class="bi bi-calendar-event me-2"></i>Lihat Jadwal
                        </a>
                    </div>
                </div>
                <div class="col-lg-5 d-none d-lg-block">
                    <div class="position-relative">
                        <div class="glass-morphism p-3 rounded-4 shadow-lg rotate-3">
                             <img src="https://images.unsplash.com/photo-1546410531-bb4caa6b424d?q=80&w=800" class="img-fluid rounded-3 shadow-sm" alt="School Life">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Decorative blobs -->
        <div class="position-absolute top-0 start-0 w-100 h-100" style="z-index: 0; opacity: 0.05;">
            <div class="position-absolute translate-middle-y top-50 start-0 translate-middle-x bg-white rounded-circle" style="width: 500px; height: 500px;"></div>
        </div>
    </section>

    <!-- Info Section -->
    <section class="py-5">
        <div class="container py-lg-5">
            <div class="row g-4 text-center">
                <div class="col-md-4">
                    <div class="p-4 card border-0 shadow-sm h-100 hover-up">
                        <div class="bg-success bg-opacity-10 p-4 rounded-circle d-inline-block mb-4">
                            <i class="bi bi-mortarboard-fill text-success fs-1"></i>
                        </div>
                        <h4 class="fw-bold">Akreditasi A</h4>
                        <p class="text-muted">Kualitas pendidikan terjamin dengan standar nasional tertinggi (Akreditasi A).</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-4">
                        <div class="bg-success bg-opacity-10 p-4 rounded-circle d-inline-block mb-4">
                            <i class="bi bi-shop text-success fs-1"></i>
                        </div>
                        <h4 class="fw-bold">Fasilitas Modern</h4>
                        <p class="text-muted">Gedung milik sendiri, Lab Komputer, Perpustakaan Digital, dan sarana olahraga lengkap.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-4 card border-0 shadow-sm h-100 hover-up">
                        <div class="bg-info bg-opacity-10 p-4 rounded-circle d-inline-block mb-4">
                            <i class="bi bi-briefcase-fill text-info fs-1"></i>
                        </div>
                        <h4 class="fw-bold">Prospek Cerah</h4>
                        <p class="text-muted">Alumni tersebar di berbagai PTN ternama dan pondok pesantren besar di Indonesia.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Requirements & Schedule -->
    <section id="jadwal" class="py-5 bg-light">
        <div class="container py-lg-5">
            <div class="row g-5">
                <div class="col-lg-6">
                    <h2 class="fw-bold mb-4">Persyaratan Pendaftaran</h2>
                    <ul class="list-group list-group-flush bg-transparent">
                        <li class="list-group-item bg-transparent px-0 py-3 border-bottom d-flex">
                            <i class="bi bi-check-circle-fill text-success me-3 fs-5"></i>
                            Lulus SMP/MTs atau Sederajat (Memiliki Ijazah/SKL)
                        </li>
                        <li class="list-group-item bg-transparent px-0 py-3 border-bottom d-flex">
                            <i class="bi bi-check-circle-fill text-success me-3 fs-5"></i>
                            Fotokopi Akta Kelahiran & Kartu Keluarga
                        </li>
                        <li class="list-group-item bg-transparent px-0 py-3 border-bottom d-flex">
                            <i class="bi bi-check-circle-fill text-success me-3 fs-5"></i>
                            Fotokopi Rapor Semester 1-5
                        </li>
                        <li class="list-group-item bg-transparent px-0 py-3 border-bottom d-flex">
                            <i class="bi bi-check-circle-fill text-success me-3 fs-5"></i>
                            Pas Foto Berwarna Ukuran 3x4 (4 Lembar)
                        </li>
                        <li class="list-group-item bg-transparent px-0 py-3 border-bottom d-flex">
                            <i class="bi bi-check-circle-fill text-success me-3 fs-5"></i>
                            Sertifikat Prestasi (Jika ada, sebagai nilai tambah)
                        </li>
                    </ul>
                </div>
                <div class="col-lg-6">
                    <h2 class="fw-bold mb-4">Jadwal Pendaftaran</h2>
                    <div class="timeline">
                        <div class="d-flex mb-4">
                            <div class="flex-shrink-0">
                                <span class="badge bg-success rounded-circle p-3">01</span>
                            </div>
                            <div class="ms-4">
                                <h5 class="fw-bold mb-1">Gelombang I (Reguler)</h5>
                                <p class="text-muted mb-0">01 Februari - 30 April 2024</p>
                            </div>
                        </div>
                        <div class="d-flex mb-4">
                            <div class="flex-shrink-0">
                                <span class="badge bg-secondary rounded-circle p-3">02</span>
                            </div>
                            <div class="ms-4">
                                <h5 class="fw-bold mb-1">Tes Seleksi & Wawancara</h5>
                                <p class="text-muted mb-0">12 Mei 2024</p>
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <span class="badge bg-success rounded-circle p-3">03</span>
                            </div>
                            <div class="ms-4">
                                <h5 class="fw-bold mb-1">Pengumuman & Daftar Ulang</h5>
                                <p class="text-muted mb-0">20 Mei - 05 Juni 2024</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-5">
        <div class="container py-lg-5">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Pertanyaan Umum (FAQ)</h2>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="accordion border-0 shadow-sm" id="accordionPPDB">
                        <div class="accordion-item border-bottom">
                            <h2 class="accordion-header">
                                <button class="accordion-button fw-bold py-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne">
                                    Berapa biaya pendaftaran PPDB?
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionPPDB">
                                <div class="accordion-body text-muted">
                                    Biaya pendaftaran untuk gelombang I sebesar Rp 150.000,- yang digunakan untuk biaya administrasi dan tes seleksi.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item border-bottom">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed fw-bold py-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo">
                                    Apakah ada beasiswa?
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionPPDB">
                                <div class="accordion-body text-muted">
                                    Tentu! Kami menyediakan beasiswa prestasi bagi siswa dengan peringkat 1-3 di sekolah asal, serta beasiswa Tahfidz bagi penghafal Al-Qur'an minimal 5 juz.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item border-0">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed fw-bold py-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree">
                                    Kapan Masa Pengenalan Lingkungan Sekolah (MPLS)?
                                </button>
                            </h2>
                            <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionPPDB">
                                <div class="accordion-body text-muted">
                                    MPLS dijadwalkan akan dilaksanakan pada minggu ketiga bulan Juli 2024 sebelum dimulainya kegiatan belajar mengajar aktif.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-5 bg-warning">
        <div class="container text-center">
            <h2 class="fw-bold mb-4 text-dark">Tunggu Apa Lagi? Daftar Sekarang!</h2>
            <a href="{{ route('ppdb.create') }}" class="btn btn-dark btn-lg px-5 py-3 fw-bold">
                Mulai Pendaftaran Online
            </a>
        </div>
    </section>
@endsection
