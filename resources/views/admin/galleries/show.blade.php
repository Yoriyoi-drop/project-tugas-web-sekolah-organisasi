@extends('admin.layouts.app')

@section('title', 'Detail Galeri - ' . $gallery->title)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="bi bi-eye me-2"></i>Detail Galeri
                    </h4>
                    <div class="btn-group">
                        <a href="{{ route('admin.galleries.edit', $gallery) }}" class="btn btn-warning">
                            <i class="bi bi-pencil me-2"></i>Edit
                        </a>
                        <a href="{{ route('admin.galleries.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($gallery->image_path)
                    <div class="text-center mb-4">
                        <img src="{{ Storage::url($gallery->image_path) }}"
                             alt="{{ $gallery->title }}"
                             class="img-fluid rounded shadow"
                             style="max-height: 400px;">
                    </div>
                    @endif

                    <div class="row mb-3">
                        <div class="col-sm-3"><strong>Judul:</strong></div>
                        <div class="col-sm-9">{{ $gallery->title }}</div>
                    </div>

                    @if($gallery->description)
                    <div class="row mb-3">
                        <div class="col-sm-3"><strong>Deskripsi:</strong></div>
                        <div class="col-sm-9">{{ $gallery->description }}</div>
                    </div>
                    @endif

                    <div class="row mb-3">
                        <div class="col-sm-3"><strong>Status:</strong></div>
                        <div class="col-sm-9">
                            @if($gallery->is_public)
                                <span class="badge bg-success fs-6">Publik</span>
                            @else
                                <span class="badge bg-secondary fs-6">Privat</span>
                            @endif
                        </div>
                    </div>

                    @if($gallery->organization)
                    <div class="row mb-3">
                        <div class="col-sm-3"><strong>Organisasi:</strong></div>
                        <div class="col-sm-9">
                            <span class="badge bg-info">{{ $gallery->organization->name }}</span>
                        </div>
                    </div>
                    @endif

                    @if($gallery->tags && count($gallery->tags) > 0)
                    <div class="row mb-3">
                        <div class="col-sm-3"><strong>Tags:</strong></div>
                        <div class="col-sm-9">
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($gallery->tags as $tag)
                                    <span class="badge bg-secondary">{{ $tag }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($gallery->uploader)
                    <div class="row mb-3">
                        <div class="col-sm-3"><strong>Diupload oleh:</strong></div>
                        <div class="col-sm-9">{{ $gallery->uploader->name }}</div>
                    </div>
                    @endif

                    <div class="row mt-4">
                        <div class="col-12">
                            <h5><i class="bi bi-calendar me-2"></i>Informasi Tambahan</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <small class="text-muted">Dibuat:</small><br>
                                    <span>{{ $gallery->created_at->format('d M Y, H:i') }}</span>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted">Terakhir Diupdate:</small><br>
                                    <span>{{ $gallery->updated_at->format('d M Y, H:i') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
