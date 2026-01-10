@extends('layouts.app')

@section('title', 'Pendaftaran Berhasil - MA NU Nusantara')

@section('content')
    <div class="container py-5 mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-7 text-center">
                <div class="mb-4">
                    <i class="bi bi-check-circle-fill text-success" style="font-size: 6rem;"></i>
                </div>
                <h1 class="display-4 fw-bold mb-3">Pendaftaran Berhasil!</h1>
                <p class="lead mb-5 text-muted">
                    Terima kasih, <strong>{{ session('registration_name') }}</strong>! Data pendaftaran Anda telah berhasil kami simpan dalam sistem PPDB MA NU Nusantara.
                </p>

                <x-card class="border-0 shadow-sm bg-light mb-5">
                    <div class="card-body p-4 text-start">
                        <h5 class="fw-bold mb-3"><i class="bi bi-info-circle-fill text-primary me-2"></i>Langkah Selanjutnya:</h5>
                        <ol class="mb-0">
                            <li class="mb-2">Tim admin kami akan memverifikasi berkas Anda dalam 1-3 hari kerja.</li>
                            <li class="mb-2">Anda akan menerima notifikasi melalui WhatsApp (Nomor yang didaftarkan) terkait jadwal seleksi/wawancara.</li>
                            <li class="mb-2">Simpan bukti pendaftaran ini sebagai referensi di masa mendatang.</li>
                            <li>Pastikan nomor WhatsApp Anda selalu aktif.</li>
                        </ol>
                    </div>
                </x-card>

                <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                    <a href="{{ route('beranda') }}" class="btn btn-primary btn-lg px-5">
                        <i class="bi bi-house-fill me-2"></i>Kembali ke Beranda
                    </a>
                    <button onclick="window.print()" class="btn btn-outline-secondary btn-lg px-5">
                        <i class="bi bi-printer-fill me-2"></i>Cetak Bukti
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
