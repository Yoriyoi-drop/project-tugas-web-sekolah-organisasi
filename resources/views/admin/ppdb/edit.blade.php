@extends('admin.layouts.app')

@section('title', 'Edit Pendaftar PPDB')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Edit Pendaftar: {{ $ppdb->name }}</h1>
    <a href="{{ route('admin.ppdb.index') }}" class="btn btn-secondary shadow-sm">
        <i class="bi bi-arrow-left me-2"></i>Kembali
    </a>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Form Perubahan Data & Status</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.ppdb.update', $ppdb) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row mb-4">
                <div class="col-md-6">
                    <label for="status" class="form-label fw-bold">Status Pendaftaran</label>
                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                        <option value="pending" {{ old('status', $ppdb->status) == 'pending' ? 'selected' : '' }}>Pending (Menunggu Verifikasi)</option>
                        <option value="approved" {{ old('status', $ppdb->status) == 'approved' ? 'selected' : '' }}>Approved (Diterima)</option>
                        <option value="rejected" {{ old('status', $ppdb->status) == 'rejected' ? 'selected' : '' }}>Rejected (Ditolak)</option>
                    </select>
                    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <hr class="my-4">
            <h6 class="fw-bold mb-3">Informasi Utama</h6>
            
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label for="name" class="form-label text-muted small">Nama Lengkap</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $ppdb->name) }}" required>
                </div>
                <div class="col-md-6">
                    <label for="email" class="form-label text-muted small">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $ppdb->email) }}" required>
                </div>
                <div class="col-md-6">
                    <label for="nik" class="form-label text-muted small">NIK</label>
                    <input type="text" class="form-control" id="nik" name="nik" value="{{ old('nik', $ppdb->nik) }}" required>
                </div>
                <div class="col-md-6">
                    <label for="phone" class="form-label text-muted small">Nomor WhatsApp</label>
                    <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $ppdb->phone) }}" required>
                </div>
                <div class="col-md-4">
                    <label for="birth_place" class="form-label text-muted small">Tempat Lahir</label>
                    <input type="text" class="form-control" id="birth_place" name="birth_place" value="{{ old('birth_place', $ppdb->birth_place) }}" required>
                </div>
                <div class="col-md-4">
                    <label for="birth_date" class="form-label text-muted small">Tanggal Lahir</label>
                    <input type="date" class="form-control" id="birth_date" name="birth_date" value="{{ old('birth_date', $ppdb->birth_date) }}" required>
                </div>
                <div class="col-md-4">
                    <label for="gender" class="form-label text-muted small">Jenis Kelamin</label>
                    <select class="form-select" name="gender">
                        <option value="male" {{ old('gender', $ppdb->gender) == 'male' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="female" {{ old('gender', $ppdb->gender) == 'female' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
                <div class="col-12">
                    <label for="address" class="form-label text-muted small">Alamat</label>
                    <textarea class="form-control" id="address" name="address" rows="3">{{ old('address', $ppdb->address) }}</textarea>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.ppdb.show', $ppdb) }}" class="btn btn-light border">Batal</a>
                <button type="submit" class="btn btn-primary px-4">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection
