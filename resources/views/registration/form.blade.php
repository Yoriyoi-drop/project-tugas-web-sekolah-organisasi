@extends('layouts.app')

@section('title', 'Daftar ' . $organization->name . ' - ' . site_name())

@section('content')
<section class="page-header">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <div class="mb-3">
                    <i class="bi {{ $organization->icon }} text-{{ $organization->color }}" style="font-size: 3rem;"></i>
                </div>
                <h1 class="display-5 fw-bold mb-3">Daftar {{ $organization->name }}</h1>
                <p class="lead mb-0">{{ $organization->description }}</p>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow border-0">
                    <div class="card-header bg-{{ $organization->color }} text-white">
                        <h4 class="mb-0"><i class="bi bi-person-plus me-2"></i>Formulir Pendaftaran</h4>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('registration.store', $organization) }}" method="POST">
                            @csrf
                            
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="text-{{ $organization->color }} mb-3">
                                        <i class="bi bi-person-circle me-2"></i>Data Pribadi
                                    </h5>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nama Lengkap *</label>
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email *</label>
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">No. Telepon/WhatsApp *</label>
                                    <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" required>
                                    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Kelas *</label>
                                    <select name="class" class="form-control @error('class') is-invalid @enderror" required>
                                        <option value="">Pilih Kelas</option>
                                        <option value="X" {{ old('class') == 'X' ? 'selected' : '' }}>X</option>
                                        <option value="XI" {{ old('class') == 'XI' ? 'selected' : '' }}>XI</option>
                                        <option value="XII" {{ old('class') == 'XII' ? 'selected' : '' }}>XII</option>
                                    </select>
                                    @error('class')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">NIS *</label>
                                    <input type="text" name="nis" class="form-control @error('nis') is-invalid @enderror" value="{{ old('nis') }}" required>
                                    @error('nis')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label">Alamat Lengkap *</label>
                                    <textarea name="address" rows="3" class="form-control @error('address') is-invalid @enderror" required>{{ old('address') }}</textarea>
                                    @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="text-{{ $organization->color }} mb-3">
                                        <i class="bi bi-heart me-2"></i>Motivasi & Kemampuan
                                    </h5>
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label">Motivasi Bergabung *</label>
                                    <textarea name="motivation" rows="4" class="form-control @error('motivation') is-invalid @enderror" placeholder="Jelaskan alasan dan motivasi Anda ingin bergabung dengan {{ $organization->name }} (minimal 50 karakter)" required>{{ old('motivation') }}</textarea>
                                    @error('motivation')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    <small class="text-muted">Minimal 50 karakter</small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Keahlian/Skill</label>
                                    <input type="text" name="skills" class="form-control @error('skills') is-invalid @enderror" value="{{ old('skills') }}" placeholder="Contoh: Public Speaking, Desain Grafis, Menulis">
                                    @error('skills')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    <small class="text-muted">Pisahkan dengan koma</small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Pengalaman Organisasi</label>
                                    <input type="text" name="experiences" class="form-control @error('experiences') is-invalid @enderror" value="{{ old('experiences') }}" placeholder="Contoh: Ketua Kelas, Anggota PMR">
                                    @error('experiences')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    <small class="text-muted">Pisahkan dengan koma</small>
                                </div>
                            </div>

                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                <strong>Informasi:</strong> Setelah mendaftar, pengurus {{ $organization->name }} akan menghubungi Anda untuk proses selanjutnya. Pastikan data yang diisi sudah benar.
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('organisasi') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left me-2"></i>Kembali
                                </a>
                                <button type="submit" class="btn btn-{{ $organization->color }}">
                                    <i class="bi bi-send me-2"></i>Kirim Pendaftaran
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection