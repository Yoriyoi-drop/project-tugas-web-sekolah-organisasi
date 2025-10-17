@extends('admin.layouts.app')

@section('title', 'Posts Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h3 class="card-title">Posts Management</h3>
                    <a href="{{ route('admin.posts.create') }}" class="btn btn-primary">Add New Post</a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Category</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($posts as $post)
                                <tr>
                                    <td>{{ $post->id }}</td>
                                    <td>{{ $post->title }}</td>
                                    <td><span class="badge bg-info">{{ $post->category }}</span></td>
                                    <td><span class="badge bg-{{ $post->is_published ? 'success' : 'warning' }}">{{ $post->is_published ? 'Published' : 'Draft' }}</span></td>
                                    <td>{{ $post->created_at ? $post->created_at->format('d/m/Y') : 'N/A' }}</td>
                                    <td>
                                        <a href="{{ route('admin.posts.edit', $post) }}" class="btn btn-sm btn-warning">Edit</a>
                                        <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">No posts found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $posts->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection