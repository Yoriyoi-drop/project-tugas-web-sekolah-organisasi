@extends('admin.layouts.app')

@section('title', 'Detail Pendaftaran')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Pendaftaran</h1>
        <a href="{{ route('admin.registrations.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Pendaftar</h6>
                    <div class="d-flex align-items-center">
                        <i class="bi {{ $registration->organization->icon }} text-{{ $registration->organization->color }} me-2" style="font-size: 1.5rem;"></i>
                        <span class="font-weight-bold">{{ $registration->organization->name }}</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Nama Lengkap:</strong>
                            <p>{{ $registration->name }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Email:</strong>
                            <p>{{ $registration->email }}</p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>No. Telepon:</strong>
                            <p>{{ $registration->phone }}</p>
                        </div>
                        <div class="col-md-4">
                            <strong>Kelas:</strong>
                            <p>{{ $registration->class }}</p>
                        </div>
                        <div class="col-md-4">
                            <strong>NIS:</strong>
                            <p>{{ $registration->nis }}</p>
                        </div>
                    </div>
                    <div class="mb-3">
                        <strong>Alamat:</strong>
                        <p>{{ $registration->address }}</p>
                    </div>
                    <div class="mb-3">
                        <strong>Motivasi Bergabung:</strong>
                        <p class="text-justify">{{ $registration->motivation }}</p>
                    </div>
                    @if($registration->skills)
                    <div class="mb-3">
                        <strong>Keahlian/Skills:</strong>
                        <div class="mt-2">
                            @foreach($registration->skills as $skill)
                                <span class="badge badge-info me-1 mb-1">{{ $skill }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    @if($registration->experiences)
                    <div class="mb-3">
                        <strong>Pengalaman Organisasi:</strong>
                        <div class="mt-2">
                            @foreach($registration->experiences as $exp)
                                <span class="badge badge-secondary me-1 mb-1">{{ $exp }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    <div class="mb-3">
                        <strong>Tanggal Pendaftaran:</strong>
                        <p>{{ $registration->created_at->format('d F Y, H:i') }} WIB</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Status Pendaftaran</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.registrations.update-status', $registration) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        
                        <div class="mb-3">
                            <label class="form-label">Status Saat Ini:</label>
                            <div class="mb-2">
                                @if($registration->status == 'pending')
                                    <span class="badge badge-warning badge-lg">Menunggu Review</span>
                                @elseif($registration->status == 'approved')
                                    <span class="badge badge-success badge-lg">Diterima</span>
                                @else
                                    <span class="badge badge-danger badge-lg">Ditolak</span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Ubah Status:</label>
                            <select name="status" class="form-control" required>
                                <option value="pending" {{ $registration->status == 'pending' ? 'selected' : '' }}>Menunggu</option>
                                <option value="approved" {{ $registration->status == 'approved' ? 'selected' : '' }}>Terima</option>
                                <option value="rejected" {{ $registration->status == 'rejected' ? 'selected' : '' }}>Tolak</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-save"></i> Update Status
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Organisasi</h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <i class="bi {{ $registration->organization->icon }} text-{{ $registration->organization->color }}" style="font-size: 3rem;"></i>
                        <h5 class="mt-2">{{ $registration->organization->name }}</h5>
                        <p class="text-muted">{{ $registration->organization->type }}</p>
                    </div>
                    <p class="small">{{ $registration->organization->description }}</p>
                    @if($registration->organization->email)
                    <div class="small mb-1">
                        <i class="bi bi-envelope"></i> {{ $registration->organization->email }}
                    </div>
                    @endif
                    @if($registration->organization->phone)
                    <div class="small">
                        <i class="bi bi-phone"></i> {{ $registration->organization->phone }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection