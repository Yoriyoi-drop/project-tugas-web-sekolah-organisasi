@extends('admin.layouts.app')

@section('title', 'Statistics Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h3 class="card-title">Statistics Management</h3>
                    <a href="{{ route('admin.statistics.create') }}" class="btn btn-primary">Add New Statistic</a>
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
                                    <th>Label</th>
                                    <th>Value</th>
                                    <th>Description</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($statistics as $stat)
                                <tr>
                                    <td>{{ $stat->id }}</td>
                                    <td>{{ $stat->label }}</td>
                                    <td><span class="badge bg-primary">{{ $stat->value }}</span></td>
                                    <td>{{ $stat->description }}</td>
                                    <td>
                                        <a href="{{ route('admin.statistics.edit', $stat) }}" class="btn btn-sm btn-warning">Edit</a>
                                        <form action="{{ route('admin.statistics.destroy', $stat) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">No statistics found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $statistics->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection