@extends('admin.layouts.app')

@section('title', 'Edit Member - ' . $organization->name)

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Member - {{ $organization->name }}</h1>
        <a href="{{ route('admin.organizations.members.index', $organization) }}" class="btn btn-secondary btn-sm shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Members
        </a>
    </div>

    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Edit Member Information</h6>
        </div>
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.organizations.members.update', [$organization, $member]) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>Member Name</label>
                    <input type="text" class="form-control" readonly value="@if($member->student){{ $member->student->name }}@elseif($member->teacher){{ $member->teacher->name }}@endif">
                    <small class="form-text text-muted">Member name cannot be changed</small>
                </div>

                <div class="form-group">
                    <label for="role">Role *</label>
                    <select name="role" id="role" class="form-control @error('role') is-invalid @enderror" required>
                        <option value="">Select role</option>
                        <option value="member" {{ old('role', $member->role) === 'member' ? 'selected' : '' }}>Member</option>
                        <option value="secretary" {{ old('role', $member->role) === 'secretary' ? 'selected' : '' }}>Secretary</option>
                        <option value="treasurer" {{ old('role', $member->role) === 'treasurer' ? 'selected' : '' }}>Treasurer</option>
                        <option value="vice_leader" {{ old('role', $member->role) === 'vice_leader' ? 'selected' : '' }}>Vice Leader</option>
                        <option value="leader" {{ old('role', $member->role) === 'leader' ? 'selected' : '' }}>Leader</option>
                    </select>
                    @error('role')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="position">Position</label>
                    <input type="text" name="position" id="position" class="form-control @error('position') is-invalid @enderror" value="{{ old('position', $member->position) }}" placeholder="e.g., Coordinator, Secretary">
                    @error('position')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="status">Status *</label>
                    <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                        <option value="">Select status</option>
                        <option value="active" {{ old('status', $member->status) === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $member->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="alumni" {{ old('status', $member->status) === 'alumni' ? 'selected' : '' }}>Alumni</option>
                        <option value="suspended" {{ old('status', $member->status) === 'suspended' ? 'selected' : '' }}>Suspended</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="period">Period *</label>
                    <select name="period" id="period" class="form-control @error('period') is-invalid @enderror" required>
                        <option value="">Select period</option>
                        @foreach($periods as $period)
                            <option value="{{ $period->name }}" {{ old('period', $member->period) === $period->name ? 'selected' : '' }}>
                                {{ $period->name }} ({{ $period->start_date->format('d M Y') }} - {{ $period->end_date ? $period->end_date->format('d M Y') : 'Present' }})
                            </option>
                        @endforeach
                    </select>
                    @error('period')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="end_date">End Date</label>
                    <input type="date" name="end_date" id="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date', $member->end_date ? $member->end_date->format('Y-m-d') : '') }}">
                    @error('end_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="notes">Notes</label>
                    <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" rows="3" placeholder="Additional notes...">{{ old('notes', $member->notes) }}</textarea>
                    @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save fa-sm"></i> Update Member
                    </button>
                    <a href="{{ route('admin.organizations.members.index', $organization) }}" class="btn btn-secondary">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
