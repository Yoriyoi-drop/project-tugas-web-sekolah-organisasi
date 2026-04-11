@extends('admin.layouts.app')

@section('title', 'Kelola Galeri')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="bi bi-images me-2"></i>Kelola Galeri
                    </h4>
                    <a href="{{ route('admin.galleries.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Tambah Galeri
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="row">
                        @forelse($galleries as $gallery)
                        <div class="col-md-4 col-lg-3 mb-4">
                            <div class="card h-100">
                                @if($gallery->image_path)
                                    <img src="{{ Storage::url($gallery->image_path) }}"
                                         alt="{{ $gallery->title }}"
                                         class="card-img-top"
                                         style="height: 180px; object-fit: cover;">
                                @else
                                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                                         style="height: 180px;">
                                        <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                                    </div>
                                @endif
                                <div class="card-body">
                                    <h6 class="card-title text-truncate">{{ $gallery->title }}</h6>
                                    <p class="card-text small text-muted">{{ Str::limit($gallery->description, 50) }}</p>
                                    @if($gallery->organization)
                                        <span class="badge bg-info">{{ $gallery->organization->name }}</span>
                                    @endif
                                    <span class="badge bg-{{ $gallery->is_public ? 'success' : 'secondary' }}">
                                        {{ $gallery->is_public ? 'Publik' : 'Privat' }}
                                    </span>
                                </div>
                                <div class="card-footer bg-transparent">
                                    <div class="btn-group w-100" role="group">
                                        <a href="{{ route('admin.galleries.show', $gallery) }}"
                                           class="btn btn-sm btn-outline-info" title="Lihat Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.galleries.edit', $gallery) }}"
                                           class="btn btn-sm btn-outline-warning" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.galleries.destroy', $gallery) }}"
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Yakin ingin menghapus galeri ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12 text-center py-5">
                            <i class="bi bi-images text-muted" style="font-size: 4rem;"></i>
                            <p class="text-muted mt-3">Belum ada galeri yang ditambahkan.</p>
                            <a href="{{ route('admin.galleries.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-2"></i>Tambah Galeri Pertama
                            </a>
                        </div>
                        @endforelse
                    </div>

                    @if($galleries->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $galleries->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
