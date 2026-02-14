@extends('layouts.admin')

@section('title', 'Pendaftaran Akun Siswa - Admin')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0 fw-bold">
                                <i class="bi bi-person-plus-fill text-primary me-2"></i>
                                Pendaftaran Akun Siswa
                            </h4>
                            <p class="text-muted mb-0 mt-1">Kelola pendaftaran akun siswa baru</p>
                        </div>
                        <div class="d-flex gap-2">
                            <!-- Advanced Filter Button -->
                            <button class="btn btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#advancedFilter" aria-expanded="{{ (request('search') || request('status') || request('date_from') || request('date_to')) ? 'true' : 'false' }}">
                                <i class="bi bi-funnel me-1"></i>Filter
                            </button>
                            
                            <div class="dropdown">
                                <button class="btn btn-outline-success dropdown-toggle" type="button" id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-download me-1"></i>Ekspor
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="exportDropdown">
                                    <li><a class="dropdown-item" href="{{ route('student-registrations.export', ['format' => 'csv']) }}"><i class="bi bi-filetype-csv me-2"></i>Ekspor ke CSV</a></li>
                                    <li><a class="dropdown-item" href="{{ route('student-registrations.export', ['format' => 'excel']) }}"><i class="bi bi-file-earmark-spreadsheet me-2"></i>Ekspor ke Excel</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Advanced Filter Form -->
                    <div class="collapse {{ (request('search') || request('status') || request('date_from') || request('date_to')) ? 'show' : '' }}" id="advancedFilter">
                        <div class="mt-3 p-3 bg-light rounded">
                            <form method="GET" action="{{ route('student-registrations.index') }}">
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <input type="text" name="search" class="form-control" placeholder="Cari nama, email, NIK, dll..." value="{{ request('search') }}">
                                    </div>
                                    <div class="col-md-2">
                                        <select name="status" class="form-select">
                                            <option value="">Semua Status</option>
                                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu</option>
                                            <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Disetujui</option>
                                            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="date" name="date_from" class="form-control" placeholder="Tanggal Awal" value="{{ request('date_from') }}">
                                    </div>
                                    <div class="col-md-2">
                                        <input type="date" name="date_to" class="form-control" placeholder="Tanggal Akhir" value="{{ request('date_to') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <div class="d-flex gap-2">
                                            <button type="submit" class="btn btn-primary flex-grow-1">
                                                <i class="bi bi-search me-1"></i>Cari
                                            </button>
                                            <a href="{{ route('student-registrations.index') }}" class="btn btn-outline-secondary flex-grow-1">
                                                <i class="bi bi-arrow-repeat me-1"></i>Reset
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card border-0 bg-warning bg-opacity-10">
                                <div class="card-body text-center">
                                    <h3 class="fw-bold text-warning">{{ StudentRegistration::pending()->count() }}</h3>
                                    <p class="mb-0 text-muted">Menunggu Persetujuan</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-0 bg-success bg-opacity-10">
                                <div class="card-body text-center">
                                    <h3 class="fw-bold text-success">{{ StudentRegistration::approved()->count() }}</h3>
                                    <p class="mb-0 text-muted">Disetujui</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-0 bg-danger bg-opacity-10">
                                <div class="card-body text-center">
                                    <h3 class="fw-bold text-danger">{{ StudentRegistration::rejected()->count() }}</h3>
                                    <p class="mb-0 text-muted">Ditolak</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filter Tabs -->
                    <ul class="nav nav-tabs mb-4" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link {{ !request('status') ? 'active' : '' }}" href="{{ route('student-registrations.index') }}">
                                Semua ({{ $registrations->total() }})
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request('status') == 'pending' ? 'active' : '' }}" href="{{ route('student-registrations.index', ['status' => 'pending']) }}">
                                Menunggu ({{ StudentRegistration::pending()->count() }})
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request('status') == 'approved' ? 'active' : '' }}" href="{{ route('student-registrations.index', ['status' => 'approved']) }}">
                                Disetujui ({{ StudentRegistration::approved()->count() }})
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request('status') == 'rejected' ? 'active' : '' }}" href="{{ route('student-registrations.index', ['status' => 'rejected']) }}">
                                Ditolak ({{ StudentRegistration::rejected()->count() }})
                            </a>
                        </li>
                    </ul>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Telepon</th>
                                    <th>Sekolah Asal</th>
                                    <th>Status</th>
                                    <th>Tanggal Daftar</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($registrations as $registration)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-2">
                                                    <i class="bi bi-person-fill text-primary"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-semibold">{{ $registration->name }}</div>
                                                    <small class="text-muted">NIK: {{ $registration->nik }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $registration->email }}</td>
                                        <td>{{ $registration->phone }}</td>
                                        <td>{{ $registration->previous_school }}</td>
                                        <td>
                                            <span class="badge bg-{{ $registration->status_color }}">
                                                {{ $registration->formatted_status }}
                                            </span>
                                        </td>
                                        <td>{{ $registration->created_at->format('d/m/Y') }}</td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('student-registrations.show', $registration) }}" class="btn btn-outline-primary">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                @if($registration->status === 'pending')
                                                    <form action="{{ route('student-registrations.approve', $registration) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-success" onclick="return confirm('Setujui pendaftaran ini?')">
                                                            <i class="bi bi-check-circle"></i>
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('student-registrations.reject', $registration) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Tolak pendaftaran ini?')">
                                                            <i class="bi bi-x-circle"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                                            <p class="text-muted mt-2 mb-0">Tidak ada data pendaftaran</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div class="text-muted">
                            Menampilkan {{ $registrations->firstItem() }}-{{ $registrations->lastItem() }} dari {{ $registrations->total() }} data
                        </div>
                        {{ $registrations->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
