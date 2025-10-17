@extends('admin.layouts.app')

@section('title', 'Activities Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h3 class="card-title">Activities Management</h3>
                    <a href="{{ route('admin.activities.create') }}" class="btn btn-primary">Add New Activity</a>
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
                                    <th>Date</th>
                                    <th>Location</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($activities as $activity)
                                <tr>
                                    <td>{{ $activity->id }}</td>
                                    <td>{{ $activity->title }}</td>
                                    <td>{{ $activity->date ? \Carbon\Carbon::parse($activity->date)->format('d/m/Y') : 'N/A' }}</td>
                                    <td>{{ $activity->location ?? 'N/A' }}</td>
                                    <td>
                                        <a href="{{ route('admin.activities.edit', $activity) }}" class="btn btn-sm btn-warning">Edit</a>
                                        <form action="{{ route('admin.activities.destroy', $activity) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">No activities found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $activities->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection