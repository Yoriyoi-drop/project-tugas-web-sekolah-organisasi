@extends('admin.layouts.app')

@section('title', 'View Message')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">View Message</h1>
    <a href="{{ route('admin.messages.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-2"></i>Back to Messages
    </a>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Message Details</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <p><strong>Name:</strong> {{ $message->name }}</p>
                <p><strong>Email:</strong> {{ $message->email }}</p>
                <p><strong>Phone:</strong> {{ $message->phone ?? 'Not provided' }}</p>
            </div>
            <div class="col-md-6">
                <p><strong>Subject:</strong> {{ $message->subject }}</p>
                <p><strong>Date:</strong> {{ $message->created_at->format('F j, Y g:i A') }}</p>
                <p><strong>Status:</strong> 
                    @if($message->is_read)
                        <span class="badge bg-success">Read</span>
                    @else
                        <span class="badge bg-warning">Unread</span>
                    @endif
                </p>
            </div>
        </div>
        <hr>
        <div class="mb-3">
            <strong>Message:</strong>
            <div class="mt-2 p-3 bg-light rounded">
                {{ $message->message }}
            </div>
        </div>
    </div>
</div>
@endsection