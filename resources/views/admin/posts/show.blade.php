@extends('admin.layouts.app')

@section('title', 'Detail Post - ' . $post->title)

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Post Detail - {{ $post->title }}</h1>
        <div>
            <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary btn-sm shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back
            </a>
            <a href="{{ route('admin.posts.edit', $post) }}" class="btn btn-primary btn-sm shadow-sm">
                <i class="fas fa-edit fa-sm text-white-50"></i> Edit
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Post Information</h6>
                </div>
                <div class="card-body">
                    @if($post->image)
                    <div class="text-center mb-4">
                        <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}" class="img-fluid rounded" style="max-height: 400px;">
                    </div>
                    @endif

                    <table class="table table-bordered">
                        <tr>
                            <th width="200">Title</th>
                            <td>{{ $post->title }}</td>
                        </tr>
                        <tr>
                            <th>Slug</th>
                            <td><code>{{ $post->slug }}</code></td>
                        </tr>
                        <tr>
                            <th>Category</th>
                            <td>
                                <span class="badge badge-info">{{ ucfirst($post->category ?? 'General') }}</span>
                            </td>
                        </tr>
                        <tr>
                            <th>Author</th>
                            <td>{{ $post->author }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                <span class="badge badge-{{ $post->is_published ? 'success' : 'secondary' }}">
                                    {{ $post->is_published ? 'Published' : 'Draft' }}
                                </span>
                            </td>
                        </tr>
                        @if($post->excerpt)
                        <tr>
                            <th>Excerpt</th>
                            <td>{{ $post->excerpt }}</td>
                        </tr>
                        @endif
                        @if($post->content)
                        <tr>
                            <th>Content</th>
                            <td>{!! $post->content !!}</td>
                        </tr>
                        @endif
                        <tr>
                            <th>Created At</th>
                            <td>{{ $post->created_at->format('d M Y, H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Updated At</th>
                            <td>{{ $post->updated_at->format('d M Y, H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <a href="{{ route('admin.posts.edit', $post) }}" class="btn btn-warning btn-block mb-2">
                        <i class="fas fa-edit"></i> Edit Post
                    </a>
                    <form action="{{ route('admin.posts.destroy', $post) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-block" onclick="return confirm('Are you sure you want to delete this post?')">
                            <i class="fas fa-trash"></i> Delete Post
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
