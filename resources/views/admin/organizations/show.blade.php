@extends('admin.layouts.app')

@section('title', 'Detail Organization - ' . $organization->name)

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Organization Detail - {{ $organization->name }}</h1>
        <div>
            <a href="{{ route('admin.organizations.index') }}" class="btn btn-secondary btn-sm shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back
            </a>
            <a href="{{ route('admin.organizations.edit', $organization) }}" class="btn btn-primary btn-sm shadow-sm">
                <i class="fas fa-edit fa-sm text-white-50"></i> Edit
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Main Information -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Organization Information</h6>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th width="200">Name</th>
                            <td>{{ $organization->name }}</td>
                        </tr>
                        <tr>
                            <th>Slug</th>
                            <td><code>{{ $organization->slug }}</code></td>
                        </tr>
                        <tr>
                            <th>Type</th>
                            <td>{{ ucfirst($organization->type) }}</td>
                        </tr>
                        <tr>
                            <th>Tagline</th>
                            <td>{{ $organization->tagline ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Description</th>
                            <td>{!! $organization->description !!}</td>
                        </tr>
                        <tr>
                            <th>Icon</th>
                            <td><i class="{{ $organization->icon ?? 'fas fa-users' }} fa-2x"></i></td>
                        </tr>
                        <tr>
                            <th>Color</th>
                            <td>
                                <span class="badge" style="background-color: {{ $organization->color ?? 'primary' }};">
                                    {{ $organization->color ?? 'primary' }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $organization->email ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Phone</th>
                            <td>{{ $organization->phone ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Location</th>
                            <td>{{ $organization->location ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                <span class="badge badge-{{ $organization->is_active ? 'success' : 'secondary' }}">
                                    {{ $organization->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Order</th>
                            <td>{{ $organization->order ?? 0 }}</td>
                        </tr>
                        @if($organization->tags && is_array($organization->tags))
                        <tr>
                            <th>Tags</th>
                            <td>
                                @foreach($organization->tags as $tag)
                                    <span class="badge badge-info">{{ $tag }}</span>
                                @endforeach
                            </td>
                        </tr>
                        @endif
                        @if($organization->programs && is_array($organization->programs))
                        <tr>
                            <th>Programs</th>
                            <td>
                                <ul class="mb-0">
                                    @foreach($organization->programs as $program)
                                        <li>{{ $program }}</li>
                                    @endforeach
                                </ul>
                            </td>
                        </tr>
                        @endif
                        @if($organization->leadership && is_array($organization->leadership))
                        <tr>
                            <th>Leadership</th>
                            <td>
                                <ul class="mb-0">
                                    @foreach($organization->leadership as $leader)
                                        <li><strong>{{ $leader['name'] }}</strong> - {{ $leader['position'] }}</li>
                                    @endforeach
                                </ul>
                            </td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>

        <!-- Member Statistics -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Member Statistics</h6>
                </div>
                <div class="card-body">
                    @if(isset($memberStats))
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span>Total Members:</span>
                                <strong>{{ array_sum($memberStats) }}</strong>
                            </div>
                        </div>
                        @foreach($memberStats as $status => $count)
                        <div class="mb-2">
                            <div class="d-flex justify-content-between">
                                <span>{{ ucfirst($status) }}:</span>
                                <span class="badge badge-{{ $status === 'active' ? 'success' : 'secondary' }}">{{ $count }}</span>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <p class="text-muted">No statistics available</p>
                    @endif
                </div>
            </div>

            <!-- Leadership Members -->
            @if(isset($leadershipMembers) && count($leadershipMembers) > 0)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-warning">Leadership Team</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        @foreach($leadershipMembers as $leader)
                        <li class="mb-2">
                            <strong>{{ $leader->name ?? 'N/A' }}</strong><br>
                            <small class="text-muted">{{ $leader->position ?? 'N/A' }}</small>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            <!-- Quick Actions -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <a href="{{ route('admin.organizations.members.index', $organization) }}" class="btn btn-info btn-block mb-2">
                        <i class="fas fa-users"></i> Manage Members
                    </a>
                    <form action="{{ route('admin.organizations.destroy', $organization) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-block" onclick="return confirm('Are you sure you want to delete this organization?')">
                            <i class="fas fa-trash"></i> Delete Organization
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
