@extends('admin.layouts.app')

@section('title', 'Edit Galeri')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="bi bi-pencil me-2"></i>Edit Galeri: {{ $gallery->title }}
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.galleries.update', $gallery) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="title" class="form-label">Judul *</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror"
                                   id="title" name="title" value="{{ old('title', $gallery->title) }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description" name="description" rows="3">{{ old('description', $gallery->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Gambar</label>
                            @if($gallery->image_path)
                                <div class="mb-2">
                                    <img src="{{ Storage::url($gallery->image_path) }}" alt="{{ $gallery->title }}"
                                         class="img-thumbnail" style="max-width: 200px;">
                                    <div class="form-text">Gambar saat ini</div>
                                </div>
                            @endif
                            <input type="file" class="form-control @error('image') is-invalid @enderror"
                                   id="image" name="image" accept="image/*">
                            <div class="form-text">Format: JPG, PNG, GIF, WEBP. Maksimal 5MB. Kosongkan jika tidak ingin mengubah gambar.</div>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="organization_id" class="form-label">Organisasi</label>
                                    <select class="form-select @error('organization_id') is-invalid @enderror"
                                            id="organization_id" name="organization_id">
                                        <option value="">-- Pilih Organisasi (Opsional) --</option>
                                        @foreach($organizations as $org)
                                            <option value="{{ $org->id }}" {{ old('organization_id', $gallery->organization_id) == $org->id ? 'selected' : '' }}>
                                                {{ $org->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('organization_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tags" class="form-label">Tags</label>
                                    <input type="text" class="form-control @error('tags') is-invalid @enderror"
                                           id="tags" name="tags" value="{{ old('tags', $gallery->tags ? implode(', ', $gallery->tags) : '') }}"
                                           placeholder="kegiatan, sekolah, event">
                                    <div class="form-text">Pisahkan setiap tag dengan koma</div>
                                    @error('tags')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="is_public" name="is_public"
                                   value="1" {{ old('is_public', $gallery->is_public) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_public">Tampilkan ke publik</label>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>Update Galeri
                            </button>
                            <a href="{{ route('admin.galleries.show', $gallery) }}" class="btn btn-info">
                                <i class="bi bi-eye me-2"></i>Lihat Detail
                            </a>
                            <a href="{{ route('admin.galleries.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
