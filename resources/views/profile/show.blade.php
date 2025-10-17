@extends('layouts.app')

@section('title', 'Profile - MA NU Nusantara')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-4">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Profile Card -->
            <div class="card shadow mb-4">
                <div class="card-body text-center">
                    <div class="position-relative d-inline-block mb-3">
                        @if($user->avatar)
                            <img src="{{ Storage::url($user->avatar) }}" alt="Avatar" class="rounded-circle" style="width: 120px; height: 120px; object-fit: cover;">
                        @else
                            <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 120px; height: 120px;">
                                <i class="bi bi-person-fill text-white" style="font-size: 3rem;"></i>
                            </div>
                        @endif
                        <button class="btn btn-sm btn-primary position-absolute bottom-0 end-0 rounded-circle" 
                                data-bs-toggle="modal" data-bs-target="#avatarModal" style="width: 30px; height: 30px;">
                            <i class="bi bi-camera-fill" style="font-size: 0.8rem;"></i>
                        </button>
                    </div>
                    <h4 class="mb-1">{{ $user->name }}</h4>
                    <p class="text-muted mb-2">{{ $user->position ?? 'Member' }}</p>
                    <div class="mb-3">
                        @if($user->isAdmin())
                            <span class="badge bg-success">Administrator</span>
                        @else
                            <span class="badge bg-primary">{{ ucfirst($user->role ?? 'User') }}</span>
                        @endif
                        @if($user->is_active)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-secondary">Inactive</span>
                        @endif
                    </div>
                    <div class="d-grid gap-2">
                        <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                            <i class="bi bi-pencil me-1"></i>Edit Profile
                        </a>
                        <a href="{{ route('2fa.show') }}" class="btn btn-warning">
                            <i class="bi bi-shield-lock me-1"></i>
                            {{ $user->two_factor_enabled ? 'Kelola 2FA' : 'Aktifkan 2FA' }}
                        </a>
                        @if($user->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-success">
                                <i class="bi bi-speedometer2 me-1"></i>Dashboard Admin
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Contact Info -->
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-telephone me-2"></i>Informasi Kontak</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted">Email</small>
                        <div>{{ $user->email }}</div>
                    </div>
                    @if($user->phone)
                        <div class="mb-3">
                            <small class="text-muted">Telepon</small>
                            <div>{{ $user->phone }}</div>
                        </div>
                    @endif
                    @if($user->address)
                        <div class="mb-3">
                            <small class="text-muted">Alamat</small>
                            <div>{{ $user->address }}</div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Social Links -->
            @if($user->social_links && count($user->social_links) > 0)
                <div class="card shadow">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="bi bi-share me-2"></i>Media Sosial</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex gap-2">
                            @foreach($user->social_links as $platform => $url)
                                <a href="{{ $url }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-{{ $platform }}"></i>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-lg-8">
            <!-- Personal Information -->
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-person-lines-fill me-2"></i>Informasi Personal</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <small class="text-muted">Nama Lengkap</small>
                            <div class="fw-bold">{{ $user->name }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <small class="text-muted">Email</small>
                            <div>{{ $user->email }}</div>
                        </div>
                        @if($user->birth_date)
                            <div class="col-md-6 mb-3">
                                <small class="text-muted">Tanggal Lahir</small>
                                <div>{{ $user->birth_date->format('d M Y') }} ({{ $user->birth_date->age }} tahun)</div>
                            </div>
                        @endif
                        @if($user->gender)
                            <div class="col-md-6 mb-3">
                                <small class="text-muted">Jenis Kelamin</small>
                                <div>{{ $user->gender == 'male' ? 'Laki-laki' : 'Perempuan' }}</div>
                            </div>
                        @endif
                        @if($user->department)
                            <div class="col-md-6 mb-3">
                                <small class="text-muted">Departemen</small>
                                <div>{{ $user->department }}</div>
                            </div>
                        @endif
                        @if($user->position)
                            <div class="col-md-6 mb-3">
                                <small class="text-muted">Posisi</small>
                                <div>{{ $user->position }}</div>
                            </div>
                        @endif
                    </div>
                    @if($user->bio)
                        <div class="mt-3">
                            <small class="text-muted">Bio</small>
                            <div class="mt-1">{{ $user->bio }}</div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Skills -->
            @if($user->skills && count($user->skills) > 0)
                <div class="card shadow mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-star me-2"></i>Keahlian</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($user->skills as $skill)
                                <span class="badge bg-light text-dark border">{{ trim($skill) }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Account Statistics -->
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-graph-up me-2"></i>Statistik Akun</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-4 mb-3">
                            <div class="border rounded p-3">
                                <h4 class="text-primary mb-1">{{ $user->created_at->diffInDays() }}</h4>
                                <small class="text-muted">Hari Bergabung</small>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="border rounded p-3">
                                <h4 class="text-success mb-1">{{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Belum pernah' }}</h4>
                                <small class="text-muted">Login Terakhir</small>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="border rounded p-3">
                                <h4 class="text-info mb-1">{{ $user->created_at->format('M Y') }}</h4>
                                <small class="text-muted">Bergabung</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Security Dashboard -->
            <div class="card shadow mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-shield-check me-2"></i>Keamanan Akun</h5>
                    <span class="badge bg-success">Aman</span>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                <span>Password Terenkripsi</span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                <span>Data Sensitif Terenkripsi</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                <span>Verifikasi Email Aktif</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                <span>Audit Log Aktif</span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                <span>Rate Limiting Aktif</span>
                            </div>
                            <div class="d-flex align-items-center">
                                @if($user->two_factor_enabled)
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                    <span>2FA Aktif</span>
                                @else
                                    <i class="bi bi-exclamation-circle-fill text-warning me-2"></i>
                                    <span>2FA Tidak Aktif</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    @if($user->phone)
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Data Terproteksi:</strong> Nomor telepon Anda disimpan dalam bentuk terenkripsi: {{ $user->secure_phone }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recent Security Activity -->
            <div class="card shadow">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-activity me-2"></i>Aktivitas Keamanan Terbaru</h5>
                </div>
                <div class="card-body">
                    @if($recentLogs->count() > 0)
                        <div class="timeline">
                            @foreach($recentLogs as $log)
                                <div class="d-flex mb-3">
                                    <div class="flex-shrink-0">
                                        @switch($log->risk_level)
                                            @case('high')
                                                <i class="bi bi-exclamation-triangle-fill text-danger"></i>
                                                @break
                                            @case('medium')
                                                <i class="bi bi-exclamation-circle-fill text-warning"></i>
                                                @break
                                            @default
                                                <i class="bi bi-info-circle-fill text-primary"></i>
                                        @endswitch
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <div class="fw-bold">{{ ucwords(str_replace('_', ' ', $log->action)) }}</div>
                                        <small class="text-muted">
                                            {{ $log->created_at->diffForHumans() }} • IP: {{ $log->ip_address }}
                                        </small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center">Belum ada aktivitas keamanan yang tercatat.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Avatar Upload Modal -->
<div class="modal fade" id="avatarModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ubah Avatar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('avatar.upload') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <input type="file" class="form-control" name="avatar" accept="image/*" required>
                        <div class="form-text">Max 2MB, format: JPG, PNG</div>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-upload me-2"></i>Upload
                        </button>
                        @if($user->avatar)
                            <a href="{{ route('avatar.delete') }}" class="btn btn-outline-danger" 
                               onclick="return confirm('Hapus avatar?')">
                                <i class="bi bi-trash me-2"></i>Hapus Avatar
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    max-height: 300px;
    overflow-y: auto;
}
</style>
@endsection