@extends('admin.layouts.app')

@section('title', 'Member Detail - ' . $organization->name)

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Member Detail - {{ $organization->name }}</h1>
        <div>
            <a href="{{ route('admin.organizations.members.index', $organization) }}" class="btn btn-secondary btn-sm shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Members
            </a>
            <a href="{{ route('admin.organizations.members.edit', [$organization, $member]) }}" class="btn btn-primary btn-sm shadow-sm">
                <i class="fas fa-edit fa-sm text-white-50"></i> Edit Member
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Member Information</h6>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th width="200">Name</th>
                            <td>
                                @if($member->student)
                                    {{ $member->student->name }}
                                @elseif($member->teacher)
                                    {{ $member->teacher->name }}
                                @else
                                    N/A
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Member Type</th>
                            <td>
                                <span class="badge badge-{{ $member->member_type === 'student' ? 'info' : 'warning' }}">
                                    {{ ucfirst($member->member_type) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Role</th>
                            <td>{{ ucfirst(str_replace('_', ' ', $member->role)) }}</td>
                        </tr>
                        <tr>
                            <th>Position</th>
                            <td>{{ $member->position ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                <span class="badge badge-{{ $member->status === 'active' ? 'success' : ($member->status === 'inactive' ? 'secondary' : 'warning') }}">
                                    {{ ucfirst($member->status) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Period</th>
                            <td>{{ $member->period }}</td>
                        </tr>
                        <tr>
                            <th>Join Date</th>
                            <td>{{ $member->join_date ? $member->join_date->format('d M Y') : '-' }}</td>
                        </tr>
                        <tr>
                            <th>End Date</th>
                            <td>{{ $member->end_date ? $member->end_date->format('d M Y') : 'Present' }}</td>
                        </tr>
                        <tr>
                            <th>Notes</th>
                            <td>{{ $member->notes ?? '-' }}</td>
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
                    <form action="{{ route('admin.organizations.members.destroy', [$organization, $member]) }}" method="POST" class="d-inline-block w-100">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-block" onclick="return confirm('Are you sure you want to remove this member?')">
                            <i class="fas fa-trash fa-sm"></i> Remove Member
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
