@extends('admin.layouts.app')

@section('title', 'Detail Kategori - ' . $category->name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="bi bi-eye me-2"></i>Detail Kategori
                    </h4>
                    <div class="btn-group">
                        <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-warning">
                            <i class="bi bi-pencil me-2"></i>Edit
                        </a>
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-3"><strong>Nama Kategori:</strong></div>
                        <div class="col-sm-9">{{ $category->name }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-3"><strong>Slug:</strong></div>
                        <div class="col-sm-9"><code>{{ $category->slug }}</code></div>
                    </div>

                    @if($category->description)
                    <div class="row mb-3">
                        <div class="col-sm-3"><strong>Deskripsi:</strong></div>
                        <div class="col-sm-9">{{ $category->description }}</div>
                    </div>
                    @endif

                    <div class="row mb-3">
                        <div class="col-sm-3"><strong>Status:</strong></div>
                        <div class="col-sm-9">
                            @if($category->is_active)
                                <span class="badge bg-success fs-6">Aktif</span>
                            @else
                                <span class="badge bg-danger fs-6">Tidak Aktif</span>
                            @endif
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <h5><i class="bi bi-calendar me-2"></i>Informasi Tambahan</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <small class="text-muted">Dibuat:</small><br>
                                    <span>{{ $category->created_at->format('d M Y, H:i') }}</span>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted">Terakhir Diupdate:</small><br>
                                    <span>{{ $category->updated_at->format('d M Y, H:i') }}</span>
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
