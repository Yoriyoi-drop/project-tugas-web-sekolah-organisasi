@extends('layouts.admin')

@section('title', 'Detail Pendaftaran - Admin')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0 fw-bold">
                            <i class="bi bi-person-badge text-primary me-2"></i>
                            Detail Pendaftaran Akun Siswa
                        </h4>
                        <div>
                            <a href="{{ route('student-registrations.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Kembali
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Status Badge -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="alert alert-{{ $registration->status_color }} d-flex align-items-center" role="alert">
                                <i class="bi bi-info-circle-fill me-2"></i>
                                <div>
                                    <strong>Status: {{ $registration->formatted_status }}</strong>
                                    @if($registration->approved_at)
                                        <br><small>Disetujui pada: {{ $registration->approved_at->format('d/m/Y H:i') }} oleh {{ $registration->approvedBy->name }}</small>
                                    @elseif($registration->rejected_at)
                                        <br><small>Ditolak pada: {{ $registration->rejected_at->format('d/m/Y H:i') }} oleh {{ $registration->rejectedBy->name }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    @if($registration->status === 'pending')
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card border-warning bg-warning bg-opacity-10">
                                    <div class="card-body">
                                        <h6 class="fw-bold mb-3">Aksi Persetujuan</h6>
                                        <form action="{{ route('student-registrations.approve', $registration) }}" method="POST" class="mb-3">
                                            @csrf
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <textarea name="notes" class="form-control" placeholder="Catatan (opsional)"></textarea>
                                                </div>
                                                <div class="col-md-4">
                                                    <button type="submit" class="btn btn-success w-100" onclick="return confirm('Setujui pendaftaran ini? Akun siswa akan dibuat otomatis.')">
                                                        <i class="bi bi-check-circle me-2"></i>Setujui
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                        <form action="{{ route('student-registrations.reject', $registration) }}" method="POST">
                                            @csrf
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <textarea name="notes" class="form-control" placeholder="Alasan penolakan" required></textarea>
                                                </div>
                                                <div class="col-md-4">
                                                    <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Tolak pendaftaran ini?')">
                                                        <i class="bi bi-x-circle me-2"></i>Tolak
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Data Pribadi -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0 fw-bold">
                                        <i class="bi bi-person-fill me-2"></i>Data Pribadi
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless table-sm">
                                        <tr>
                                            <td width="30%">Nama Lengkap</td>
                                            <td><strong>{{ $registration->name }}</strong></td>
                                        </tr>
                                        <tr>
                                            <td>NIK</td>
                                            <td>{{ $registration->nik }}</td>
                                        </tr>
                                        <tr>
                                            <td>Email</td>
                                            <td>{{ $registration->email }}</td>
                                        </tr>
                                        <tr>
                                            <td>Telepon</td>
                                            <td>{{ $registration->phone }}</td>
                                        </tr>
                                        <tr>
                                            <td>Tempat Lahir</td>
                                            <td>{{ $registration->birth_place }}</td>
                                        </tr>
                                        <tr>
                                            <td>Tanggal Lahir</td>
                                            <td>{{ $registration->birth_date->format('d/m/Y') }}</td>
                                        </tr>
                                        <tr>
                                            <td>Jenis Kelamin</td>
                                            <td>{{ $registration->gender === 'male' ? 'Laki-laki' : 'Perempuan' }}</td>
                                        </tr>
                                        <tr>
                                            <td>Alamat</td>
                                            <td>{{ $registration->address }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0 fw-bold">
                                        <i class="bi bi-people-fill me-2"></i>Data Orang Tua/Wali
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless table-sm">
                                        <tr>
                                            <td width="30%">Nama Orang Tua/Wali</td>
                                            <td><strong>{{ $registration->parent_name }}</strong></td>
                                        </tr>
                                        <tr>
                                            <td>Telepon Orang Tua/Wali</td>
                                            <td>{{ $registration->parent_phone }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="card mt-3">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0 fw-bold">
                                        <i class="bi bi-book-fill me-2"></i>Data Pendidikan
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless table-sm">
                                        <tr>
                                            <td width="30%">Sekolah Asal</td>
                                            <td><strong>{{ $registration->previous_school }}</strong></td>
                                        </tr>
                                        <tr>
                                            <td>Jurusan Diinginkan</td>
                                            <td>{{ $registration->desired_major ?: '-' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Notes -->
                    @if($registration->notes)
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0 fw-bold">
                                            <i class="bi bi-chat-text-fill me-2"></i>Catatan
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <p class="mb-0">{{ $registration->notes }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Log Info -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="text-muted small">
                                <i class="bi bi-clock me-1"></i>
                                Didaftarkan pada: {{ $registration->created_at->format('d/m/Y H:i:s') }}
                                @if($registration->updated_at != $registration->created_at)
                                    <br><i class="bi bi-pencil me-1"></i>
                                    Diperbarui pada: {{ $registration->updated_at->format('d/m/Y H:i:s') }}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
