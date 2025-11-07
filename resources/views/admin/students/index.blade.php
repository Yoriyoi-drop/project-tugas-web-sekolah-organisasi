@extends('admin.layouts.app')

@section('title', 'Students Management')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Students Management</h1>
    <a href="{{ route('admin.students.create') }}" class="btn btn-primary shadow-sm">
        <i class="bi bi-plus-circle me-2"></i>Add New Student
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Students List</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>NIS</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Class</th>
                        <th>Registered</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                        <tr>
                            <td class="font-weight-bold">{{ $student->name }}</td>
                            <td>{{ $student->nis }}</td>
                            <td>{{ $student->email }}</td>
                            <td>{{ $student->phone }}</td>
                            <td><span class="badge bg-primary">{{ $student->class }}</span></td>
                            <td>{{ $student->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.students.show', $student) }}" class="btn btn-info btn-sm" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.students.edit', $student) }}" class="btn btn-warning btn-sm" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.students.destroy', $student) }}" method="POST" class="d-inline">
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
                                <img src="/images/students/default.svg" alt="Students" style="width: 48px; height: 48px; object-fit: contain; opacity: 0.5;">
                                <p class="text-muted mt-2">No students found</p>
                                <a href="{{ route('admin.students.create') }}" class="btn btn-primary btn-sm">Add First Student</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($students->hasPages())
            <div class="d-flex justify-content-center">
                {{ $students->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
