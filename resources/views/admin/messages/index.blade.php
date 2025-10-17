@extends('admin.layouts.app')

@section('title', 'Messages')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Messages</h1>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Contact Messages</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Subject</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($messages as $message)
                        <tr class="{{ $message->is_read ? '' : 'table-warning' }}">
                            <td class="font-weight-bold">{{ $message->name }}</td>
                            <td>{{ $message->email }}</td>
                            <td>{{ Str::limit($message->subject, 30) }}</td>
                            <td>{{ $message->created_at->format('M d, Y') }}</td>
                            <td>
                                @if($message->is_read)
                                    <span class="badge bg-success">Read</span>
                                @else
                                    <span class="badge bg-warning">Unread</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.messages.show', $message) }}" class="btn btn-info btn-sm" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <form action="{{ route('admin.messages.destroy', $message) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <i class="bi bi-envelope text-gray-300" style="font-size: 3rem;"></i>
                                <p class="text-muted mt-2">No messages found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($messages->hasPages())
            <div class="d-flex justify-content-center">
                {{ $messages->links() }}
            </div>
        @endif
    </div>
</div>
@endsection