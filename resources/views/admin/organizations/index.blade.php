@extends('admin.layouts.app')

@section('title', 'Organizations Management')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Organizations Management</h1>
        <a href="{{ route('admin.organizations.create') }}" class="btn btn-primary btn-sm shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Add Organization
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Organizations List</h6>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>
            @endif
            
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th width="25%">Name</th>
                            <th width="15%">Type</th>
                            <th width="20%">Tags</th>
                            <th width="10%">Status</th>
                            <th width="10%">Order</th>
                            <th width="15%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($organizations as $org)
                        <tr>
                            <td>{{ $org->id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="bi {{ $org->icon }} text-{{ $org->color }} me-2" style="font-size: 1.2rem;"></i>
                                    <div>
                                        <div class="font-weight-bold">{{ $org->name }}</div>
                                        @if($org->tagline)
                                            <small class="text-muted">{{ $org->tagline }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge badge-info">{{ $org->type }}</span></td>
                            <td>
                                @if($org->tags)
                                    @foreach($org->tags as $tag)
                                        <span class="badge badge-light border me-1 mb-1">{{ $tag }}</span>
                                    @endforeach
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($org->is_active)
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>{{ $org->order ?? 0 }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.organizations.edit', $org) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.organizations.destroy', $org) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this organization?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-inbox fa-2x mb-2"></i>
                                    <p>No organizations found</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($organizations->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted">
                        Showing {{ $organizations->firstItem() }} to {{ $organizations->lastItem() }} of {{ $organizations->total() }} results
                    </div>
                    <div>
                        {{ $organizations->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection