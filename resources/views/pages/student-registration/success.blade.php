@extends('layouts.app')

@section('title', 'Pendaftaran Akun Berhasil - MA NU Nusantara')

@section('content')
    <div class="container py-5 mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-7 text-center">
                <div class="mb-4">
                    <i class="bi bi-check-circle-fill text-success" style="font-size: 6rem;"></i>
                </div>
                <h1 class="display-4 fw-bold mb-3">Pendaftaran Akun Berhasil!</h1>
                <p class="lead mb-5 text-muted">
                    Terima kasih, <strong>{{ session('registration_name') }}</strong>! Data pendaftaran akun Anda telah berhasil kami simpan dalam sistem MA NU Nusantara.
                </p>

                <x-card class="border-0 shadow-sm bg-light mb-5">
                    <div class="card-body p-4 text-start">
                        <h5 class="fw-bold mb-3"><i class="bi bi-info-circle-fill text-primary me-2"></i>Langkah Selanjutnya:</h5>
                        <ol class="mb-0">
                            <li class="mb-2">Tim admin kami akan memverifikasi data Anda dalam 1-3 hari kerja.</li>
                            <li class="mb-2">Jika disetujui, akun siswa akan dibuat dan password akan dikirimkan melalui email.</li>
                            <li class="mb-2">Anda dapat menggunakan email dan password untuk login ke sistem pembelajaran.</li>
                            <li class="mb-2">Periksa email Anda secara berkala untuk notifikasi persetujuan.</li>
                            <li>Jika ada kendala, hubungi admin sekolah untuk bantuan.</li>
                        </ol>
                    </div>
                </x-card>

                <x-card class="border-0 shadow-sm mb-5">
                    <div class="card-body p-4 text-start">
                        <h5 class="fw-bold mb-3"><i class="bi bi-shield-check text-success me-2"></i>Keuntungan Akun Siswa:</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li class="mb-2">
                                        <i class="bi bi-check-circle text-success me-2"></i>
                                        Akses materi pembelajaran
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-check-circle text-success me-2"></i>
                                        Pengumpulan tugas online
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li class="mb-2">
                                        <i class="bi bi-check-circle text-success me-2"></i>
                                        Pantau nilai dan raport
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-check-circle text-success me-2"></i>
                                        Komunikasi dengan guru
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </x-card>

                <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                    <a href="{{ route('login') }}" class="btn btn-primary btn-lg px-5">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Login ke Sistem
                    </a>
                    <a href="{{ route('beranda') }}" class="btn btn-outline-secondary btn-lg px-5">
                        <i class="bi bi-house-fill me-2"></i>Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
