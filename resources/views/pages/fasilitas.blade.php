@extends('layouts.app')

@section('title', 'Fasilitas - Madrasah Aliyah Nusantara')

@section('content')
<!-- Hero Section -->
<section class="hero-section bg-primary text-white">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <div class="mb-4">
                    <i class="bi bi-building" style="font-size: 4rem; opacity: 0.9;"></i>
                </div>
                <h1 class="display-4 fw-bold mb-4">Fasilitas Sekolah</h1>
                <p class="lead mb-0">Fasilitas lengkap dan modern untuk mendukung kegiatan belajar mengajar yang berkualitas</p>
            </div>
        </div>
    </div>
</section>

<!-- Filter & Search Section -->
<section class="py-4 bg-light">
    <div class="container">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label for="search" class="form-label">Cari Fasilitas</label>
                <input type="text" class="form-control" id="search" name="search" 
                       value="{{ request('search') }}" placeholder="Nama fasilitas...">
            </div>
            <div class="col-md-4">
                <label for="category" class="form-label">Kategori</label>
                <select class="form-select" id="category" name="category">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>
                            {{ $cat }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search me-2"></i>Cari
                    </button>
                    <a href="{{ route('fasilitas') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-clockwise me-2"></i>Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
</section>

<!-- Facilities Grid -->
<section class="py-5">
    <div class="container">
        @if($facilities->count() > 0)
            <div class="row g-4">
                @foreach($facilities as $facility)
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100 border-0 shadow-sm">
                        @if($facility->image)
                            <img src="{{ Storage::url($facility->image) }}" 
                                 class="card-img-top" 
                                 style="height: 200px; object-fit: cover;"
                                 alt="{{ $facility->name }}">
                        @else
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                                 style="height: 200px;">
                                <i class="bi bi-building text-muted" style="font-size: 3rem;"></i>
                            </div>
                        @endif
                        
                        <div class="card-body d-flex flex-column">
                            <div class="mb-2">
                                <span class="badge bg-primary">{{ $facility->category }}</span>
                                @if($facility->capacity)
                                    <span class="badge bg-info ms-1">
                                        <i class="bi bi-people me-1"></i>{{ $facility->capacity }}
                                    </span>
                                @endif
                            </div>
                            
                            <h5 class="card-title">{{ $facility->name }}</h5>
                            <p class="card-text text-muted flex-grow-1">
                                {{ Str::limit($facility->description, 100) }}
                            </p>
                            
                            @if($facility->location)
                                <div class="mb-2">
                                    <small class="text-muted">
                                        <i class="bi bi-geo-alt me-1"></i>{{ $facility->location }}
                                    </small>
                                </div>
                            @endif
                            
                            @if($facility->features && count($facility->features) > 0)
                                <div class="mb-3">
                                    @foreach(array_slice($facility->features, 0, 3) as $feature)
                                        <span class="badge bg-light text-dark border me-1 mb-1">{{ $feature }}</span>
                                    @endforeach
                                    @if(count($facility->features) > 3)
                                        <span class="badge bg-secondary">+{{ count($facility->features) - 3 }} lainnya</span>
                                    @endif
                                </div>
                            @endif
                            
                            <a href="{{ route('fasilitas.show', $facility) }}" class="btn btn-outline-primary mt-auto">
                                <i class="bi bi-eye me-2"></i>Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            @if($facilities->hasPages())
                <div class="d-flex justify-content-center mt-5">
                    {{ $facilities->appends(request()->query())->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-5">
                <i class="bi bi-search text-muted" style="font-size: 4rem;"></i>
                <h4 class="mt-3">Fasilitas Tidak Ditemukan</h4>
                <p class="text-muted">Coba ubah kata kunci pencarian atau filter kategori.</p>
                <a href="{{ route('fasilitas') }}" class="btn btn-primary">
                    <i class="bi bi-arrow-left me-2"></i>Lihat Semua Fasilitas
                </a>
            </div>
        @endif
    </div>
</section>

<!-- Statistics Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-3 col-6 mb-3">
                <div class="h2 fw-bold text-primary">{{ \App\Models\Facility::where('status', 'active')->count() }}</div>
                <p class="mb-0 text-muted">Total Fasilitas</p>
            </div>
            <div class="col-md-3 col-6 mb-3">
                <div class="h2 fw-bold text-success">{{ \App\Models\Facility::where('status', 'active')->distinct('category')->count('category') }}</div>
                <p class="mb-0 text-muted">Kategori</p>
            </div>
            <div class="col-md-3 col-6 mb-3">
                <div class="h2 fw-bold text-info">{{ \App\Models\Facility::where('status', 'active')->sum('capacity') ?: 'N/A' }}</div>
                <p class="mb-0 text-muted">Total Kapasitas</p>
            </div>
            <div class="col-md-3 col-6 mb-3">
                <div class="h2 fw-bold text-warning">24/7</div>
                <p class="mb-0 text-muted">Akses Fasilitas</p>
            </div>
        </div>
    </div>
</section>
@endsection