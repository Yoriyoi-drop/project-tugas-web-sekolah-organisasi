@extends('admin.layouts.app')

@section('title', 'Detail Pendaftar PPDB')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Detail Pendaftar: {{ $ppdb->name }}</h1>
    <a href="{{ route('admin.ppdb.index') }}" class="btn btn-secondary shadow-sm">
        <i class="bi bi-arrow-left me-2"></i>Kembali
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Informasi Pendaftar</h6>
                <span class="badge @php 
                    echo [
                        'pending' => 'bg-warning text-dark',
                        'approved' => 'bg-success',
                        'rejected' => 'bg-danger',
                    ][$ppdb->status ?? 'pending'] ?? 'bg-secondary';
                @endphp p-2">
                    {{ strtoupper($ppdb->status ?? 'pending') }}
                </span>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-sm-4 fw-bold text-muted">Nama Lengkap</div>
                    <div class="col-sm-8">{{ $ppdb->name }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 fw-bold text-muted">NIK</div>
                    <div class="col-sm-8">{{ $ppdb->nik }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 fw-bold text-muted">Email</div>
                    <div class="col-sm-8">{{ $ppdb->email }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 fw-bold text-muted">Nomor WhatsApp</div>
                    <div class="col-sm-8">{{ $ppdb->phone }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 fw-bold text-muted">Tempat, Tanggal Lahir</div>
                    <div class="col-sm-8">{{ $ppdb->birth_place }}, {{ \Carbon\Carbon::parse($ppdb->birth_date)->format('d M Y') }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 fw-bold text-muted">Jenis Kelamin</div>
                    <div class="col-sm-8">{{ $ppdb->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 fw-bold text-muted">Alamat</div>
                    <div class="col-sm-8">{{ $ppdb->address }}</div>
                </div>
                
                <hr class="my-4">
                
                <h6 class="fw-bold text-primary mb-3">Data Orang Tua & Pendidikan</h6>
                <div class="row mb-3">
                    <div class="col-sm-4 fw-bold text-muted">Nama Orang Tua/Wali</div>
                    <div class="col-sm-8">{{ $ppdb->parent_name }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 fw-bold text-muted">Telepon Orang Tua</div>
                    <div class="col-sm-8">{{ $ppdb->parent_phone }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 fw-bold text-muted">Asal Sekolah</div>
                    <div class="col-sm-8">{{ $ppdb->previous_school }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 fw-bold text-muted">Pilihan Jurusan</div>
                    <div class="col-sm-8"><span class="badge bg-info text-dark">{{ $ppdb->desired_major }}</span></div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Tindakan</h6>
            </div>
            <div class="card-body">
                <a href="{{ route('admin.ppdb.edit', $ppdb) }}" class="btn btn-warning btn-block mb-3 w-100">
                    <i class="bi bi-pencil me-2"></i>Ubah Status / Data
                </a>
                
                <form action="{{ route('admin.ppdb.destroy', $ppdb) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-block w-100" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                        <i class="bi bi-trash me-2"></i>Hapus Data
                    </button>
                </form>
            </div>
        </div>
        
        <div class="card shadow mb-4">
            <div class="card-header py-3 text-white bg-info">
                <h6 class="m-0 font-weight-bold">Log Pendaftaran</h6>
            </div>
            <div class="card-body">
                <small class="text-muted d-block mb-1">Terdaftar pada:</small>
                <p class="mb-0">{{ $ppdb->created_at->format('d F Y, H:i') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
