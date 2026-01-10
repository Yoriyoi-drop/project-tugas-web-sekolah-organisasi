@extends('layouts.app')

@section('title', 'Pendaftaran PPDB - MA NU Nusantara')

@section('content')
    <!-- Page Header -->
    <section class="page-header bg-primary text-white py-5">
        <div class="container text-center">
            <h1 class="display-4 fw-bold">Pendaftaran Siswa Baru</h1>
            <p class="lead">Bergabunglah bersama kami untuk masa depan yang lebih cerah</p>
        </div>
    </section>

    <!-- Registration Form -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <x-card class="shadow-lg border-0">
                        <div class="card-body p-4 p-md-5">
                            @if(session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            <form action="{{ route('ppdb.store') }}" method="POST">
                                @csrf
                                
                                <!-- Data Pribadi -->
                                <div class="mb-5">
                                    <h5 class="fw-bold text-primary mb-4 pb-2 border-bottom">1. Data Pribadi Calon Siswa</h5>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required placeholder="Masukkan nama lengkap">
                                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="nik" class="form-label">NIK (16 Digit) <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('nik') is-invalid @enderror" id="nik" name="nik" value="{{ old('nik') }}" required placeholder="Masukkan 16 digit NIK">
                                            @error('nik') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required placeholder="nama@gmail.com">
                                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="phone" class="form-label">Nomor WhatsApp <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" required placeholder="08xxxxxxxxx">
                                            @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                        <div class="col-md-4">
                                            <label for="birth_place" class="form-label">Tempat Lahir <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('birth_place') is-invalid @enderror" id="birth_place" name="birth_place" value="{{ old('birth_place') }}" required placeholder="Contoh: Jakarta">
                                            @error('birth_place') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                        <div class="col-md-4">
                                            <label for="birth_date" class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control @error('birth_date') is-invalid @enderror" id="birth_date" name="birth_date" value="{{ old('birth_date') }}" required>
                                            @error('birth_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label d-block">Jenis Kelamin <span class="text-danger">*</span></label>
                                            <div class="form-check form-check-inline mt-2">
                                                <input class="form-check-input" type="radio" name="gender" id="gender_l" value="L" {{ old('gender') == 'L' ? 'checked' : '' }} required>
                                                <label class="form-check-label" for="gender_l">Laki-laki</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="gender" id="gender_p" value="P" {{ old('gender') == 'P' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="gender_p">Perempuan</label>
                                            </div>
                                            @error('gender') <div class="text-danger small">{{ $message }}</div> @enderror
                                        </div>
                                        <div class="col-12">
                                            <label for="address" class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                                            <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3" required placeholder="Masukkan alamat lengkap">{{ old('address') }}</textarea>
                                            @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Data Wali -->
                                <div class="mb-5">
                                    <h5 class="fw-bold text-primary mb-4 pb-2 border-bottom">2. Data Orang Tua / Wali</h5>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="parent_name" class="form-label">Nama Orang Tua/Wali <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('parent_name') is-invalid @enderror" id="parent_name" name="parent_name" value="{{ old('parent_name') }}" required placeholder="Nama lengkap orang tua/wali">
                                            @error('parent_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="parent_phone" class="form-label">Nomor WhatsApp Wali <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('parent_phone') is-invalid @enderror" id="parent_phone" name="parent_phone" value="{{ old('parent_phone') }}" required placeholder="08xxxxxxxxx">
                                            @error('parent_phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Data Sekolah & Pilihan -->
                                <div class="mb-4">
                                    <h5 class="fw-bold text-primary mb-4 pb-2 border-bottom">3. Data Pendidikan & Pilihan Jurusan</h5>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="previous_school" class="form-label">Asal Sekolah (SMP/MTs) <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('previous_school') is-invalid @enderror" id="previous_school" name="previous_school" value="{{ old('previous_school') }}" required placeholder="Masukkan asal sekolah">
                                            @error('previous_school') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="desired_major" class="form-label">Pilihan Jurusan <span class="text-danger">*</span></label>
                                            <select class="form-select @error('desired_major') is-invalid @enderror" id="desired_major" name="desired_major" required>
                                                <option value="" selected disabled>Pilih Jurusan</option>
                                                <option value="IPA" {{ old('desired_major') == 'IPA' ? 'selected' : '' }}>MIPA (Matematika & Ilmu Pengetahuan Alam)</option>
                                                <option value="IPS" {{ old('desired_major') == 'IPS' ? 'selected' : '' }}>IPS (Ilmu Pengetahuan Sosial)</option>
                                                <option value="AGAMA" {{ old('desired_major') == 'AGAMA' ? 'selected' : '' }}>Keagamaan</option>
                                            </select>
                                            @error('desired_major') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="alert alert-info border-0 shadow-sm mt-4">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-info-circle-fill fs-3 me-3"></i>
                                        <div>
                                            <p class="mb-0">Pastikan semua data yang diisi benar dan valid. Setelah mengirimkan formulir, Anda dapat mengunduh bukti pendaftaran.</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-grid gap-2 mt-5">
                                    <button type="submit" class="btn btn-primary btn-lg py-3 fw-bold">
                                        <i class="bi bi-send-fill me-2"></i>Kirim Pendaftaran
                                    </button>
                                </div>
                            </form>
                        </div>
                    </x-card>
                </div>
            </div>
        </div>
    </section>
@endsection
