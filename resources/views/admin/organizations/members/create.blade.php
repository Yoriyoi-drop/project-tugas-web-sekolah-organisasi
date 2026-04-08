@extends('admin.layouts.app')

@section('title', 'Add Member - ' . $organization->name)

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Add Member - {{ $organization->name }}</h1>
        <a href="{{ route('admin.organizations.members.index', $organization) }}" class="btn btn-secondary btn-sm shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Members
        </a>
    </div>

    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Member Information</h6>
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

            <form action="{{ route('admin.organizations.members.store', $organization) }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="member_type">Member Type *</label>
                    <select name="member_type" id="member_type" class="form-control @error('member_type') is-invalid @enderror" required>
                        <option value="">Select type</option>
                        <option value="student" {{ old('member_type') === 'student' ? 'selected' : '' }}>Student</option>
                        <option value="teacher" {{ old('member_type') === 'teacher' ? 'selected' : '' }}>Teacher</option>
                    </select>
                    @error('member_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group" id="student_select" style="display: none;">
                    <label for="student_id">Student</label>
                    <select name="student_id" id="student_id" class="form-control @error('student_id') is-invalid @enderror">
                        <option value="">Select student</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                {{ $student->name }} - {{ $student->nis ?? 'N/A' }}
                            </option>
                        @endforeach
                    </select>
                    @error('student_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group" id="teacher_select" style="display: none;">
                    <label for="teacher_id">Teacher</label>
                    <select name="teacher_id" id="teacher_id" class="form-control @error('teacher_id') is-invalid @enderror">
                        <option value="">Select teacher</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('teacher_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="role">Role *</label>
                    <select name="role" id="role" class="form-control @error('role') is-invalid @enderror" required>
                        <option value="">Select role</option>
                        <option value="member" {{ old('role') === 'member' ? 'selected' : '' }}>Member</option>
                        <option value="secretary" {{ old('role') === 'secretary' ? 'selected' : '' }}>Secretary</option>
                        <option value="treasurer" {{ old('role') === 'treasurer' ? 'selected' : '' }}>Treasurer</option>
                        <option value="vice_leader" {{ old('role') === 'vice_leader' ? 'selected' : '' }}>Vice Leader</option>
                        <option value="leader" {{ old('role') === 'leader' ? 'selected' : '' }}>Leader</option>
                    </select>
                    @error('role')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="position">Position</label>
                    <input type="text" name="position" id="position" class="form-control @error('position') is-invalid @enderror" value="{{ old('position') }}" placeholder="e.g., Coordinator, Secretary">
                    @error('position')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="period">Period *</label>
                    <select name="period" id="period" class="form-control @error('period') is-invalid @enderror" required>
                        <option value="">Select period</option>
                        @foreach($periods as $period)
                            <option value="{{ $period->name }}" {{ old('period') === $period->name ? 'selected' : '' }}>
                                {{ $period->name }} ({{ $period->start_date->format('d M Y') }} - {{ $period->end_date ? $period->end_date->format('d M Y') : 'Present' }})
                            </option>
                        @endforeach
                    </select>
                    @error('period')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="notes">Notes</label>
                    <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" rows="3" placeholder="Additional notes...">{{ old('notes') }}</textarea>
                    @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save fa-sm"></i> Add Member
                    </button>
                    <a href="{{ route('admin.organizations.members.index', $organization) }}" class="btn btn-secondary">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const memberTypeSelect = document.getElementById('member_type');
    const studentSelect = document.getElementById('student_select');
    const teacherSelect = document.getElementById('teacher_select');

    memberTypeSelect.addEventListener('change', function() {
        if (this.value === 'student') {
            studentSelect.style.display = 'block';
            teacherSelect.style.display = 'none';
        } else if (this.value === 'teacher') {
            studentSelect.style.display = 'none';
            teacherSelect.style.display = 'block';
        } else {
            studentSelect.style.display = 'none';
            teacherSelect.style.display = 'none';
        }
    });

    // Trigger on page load if value exists
    memberTypeSelect.dispatchEvent(new Event('change'));
});
</script>
@endpush
@endsection
