@extends('layouts.app')

@section('title', 'Pendaftaran Akun Siswa - MA NU Nusantara')

@section('content')
    <!-- Page Header -->
    <section class="page-header bg-primary text-white py-5">
        <div class="container text-center">
            <h1 class="display-4 fw-bold">Pendaftaran Akun Siswa</h1>
            <p class="lead">Daftarkan diri Anda untuk mendapatkan akses ke sistem pembelajaran</p>
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

                            <form action="{{ route('student-registration.store') }}" method="POST">
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
                                            <input type="text" class="form-control @error('nik') is-invalid @enderror" id="nik" name="nik" value="{{ old('nik') }}" required placeholder="Masukkan 16 digit NIK" maxlength="16">
                                            @error('nik') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required placeholder="nama@gmail.com">
                                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="phone" class="form-label">Nomor Telepon <span class="text-danger">*</span></label>
                                            <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" required placeholder="08123456789">
                                            @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="birth_place" class="form-label">Tempat Lahir <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('birth_place') is-invalid @enderror" id="birth_place" name="birth_place" value="{{ old('birth_place') }}" required placeholder="Kota/Kabupaten">
                                            @error('birth_place') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="birth_date" class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control @error('birth_date') is-invalid @enderror" id="birth_date" name="birth_date" value="{{ old('birth_date') }}" required>
                                            @error('birth_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                                            <div class="d-flex gap-3">
                                                <div class="form-check">
                                                    <input class="form-check-input @error('gender') is-invalid @enderror" type="radio" name="gender" id="gender_male" value="male" {{ old('gender') == 'male' ? 'checked' : '' }} required>
                                                    <label class="form-check-label" for="gender_male">
                                                        Laki-laki
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input @error('gender') is-invalid @enderror" type="radio" name="gender" id="gender_female" value="female" {{ old('gender') == 'female' ? 'checked' : '' }} required>
                                                    <label class="form-check-label" for="gender_female">
                                                        Perempuan
                                                    </label>
                                                </div>
                                            </div>
                                            @error('gender') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                        <div class="col-12">
                                            <label for="address" class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                                            <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3" required placeholder="Masukkan alamat lengkap">{{ old('address') }}</textarea>
                                            @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Data Orang Tua -->
                                <div class="mb-5">
                                    <h5 class="fw-bold text-primary mb-4 pb-2 border-bottom">2. Data Orang Tua/Wali</h5>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="parent_name" class="form-label">Nama Orang Tua/Wali <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('parent_name') is-invalid @enderror" id="parent_name" name="parent_name" value="{{ old('parent_name') }}" required placeholder="Nama lengkap orang tua/wali">
                                            @error('parent_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="parent_phone" class="form-label">Nomor Telepon Orang Tua/Wali <span class="text-danger">*</span></label>
                                            <input type="tel" class="form-control @error('parent_phone') is-invalid @enderror" id="parent_phone" name="parent_phone" value="{{ old('parent_phone') }}" required placeholder="08123456789">
                                            @error('parent_phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Data Pendidikan -->
                                <div class="mb-5">
                                    <h5 class="fw-bold text-primary mb-4 pb-2 border-bottom">3. Data Pendidikan</h5>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="previous_school" class="form-label">Sekolah Asal <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('previous_school') is-invalid @enderror" id="previous_school" name="previous_school" value="{{ old('previous_school') }}" required placeholder="Nama sekolah asal">
                                            @error('previous_school') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="desired_major" class="form-label">Jurusan yang Diinginkan</label>
                                            <input type="text" class="form-control @error('desired_major') is-invalid @enderror" id="desired_major" name="desired_major" value="{{ old('desired_major') }}" placeholder="IPA/IPS/Bahasa, dll">
                                            @error('desired_major') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary btn-lg px-5 py-3 fw-bold">
                                        <i class="bi bi-send me-2"></i>Daftar Sekarang
                                    </button>
                                    <a href="{{ route('student-registration.index') }}" class="btn btn-outline-secondary btn-lg px-5 py-3 fw-bold ms-2">
                                        <i class="bi bi-arrow-left me-2"></i>Kembali
                                    </a>
                                </div>
                            </form>
                        </div>
                    </x-card>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    // Format NIK input
    document.getElementById('nik').addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    // Format phone inputs
    document.getElementById('phone').addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    document.getElementById('parent_phone').addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    // Set max date for birth_date (today)
    document.getElementById('birth_date').max = new Date().toISOString().split('T')[0];
</script>
@endpush
