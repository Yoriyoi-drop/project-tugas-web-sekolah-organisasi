@extends('admin.layouts.app')

@section('title', 'Edit Post')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Post</h3>
                </div>
                <form action="{{ route('admin.posts.update', $post) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Title</label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $post->title) }}">
                            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Excerpt</label>
                            <textarea name="excerpt" rows="3" class="form-control @error('excerpt') is-invalid @enderror">{{ old('excerpt', $post->excerpt) }}</textarea>
                            @error('excerpt')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select name="category" class="form-control @error('category') is-invalid @enderror">
                                <option value="">Select Category</option>
                                <option value="Berita" {{ old('category', $post->category) == 'Berita' ? 'selected' : '' }}>Berita</option>
                                <option value="Pengumuman" {{ old('category', $post->category) == 'Pengumuman' ? 'selected' : '' }}>Pengumuman</option>
                                <option value="Kegiatan" {{ old('category', $post->category) == 'Kegiatan' ? 'selected' : '' }}>Kegiatan</option>
                                <option value="Prestasi" {{ old('category', $post->category) == 'Prestasi' ? 'selected' : '' }}>Prestasi</option>
                            </select>
                            @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Content</label>
                            <textarea name="content" rows="10" class="form-control @error('content') is-invalid @enderror">{{ old('content', $post->content) }}</textarea>
                            @error('content')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-control @error('status') is-invalid @enderror">
                                <option value="draft" {{ old('status', $post->is_published ? 'published' : 'draft') == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ old('status', $post->is_published ? 'published' : 'draft') == 'published' ? 'selected' : '' }}>Published</option>
                            </select>
                            @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Update Post</button>
                        <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection