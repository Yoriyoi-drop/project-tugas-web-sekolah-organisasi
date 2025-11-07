@extends('admin.layouts.app')

@section('title', 'Add New Student')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Add New Student</h1>
    <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-2"></i>Back to Students
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Student Information</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.students.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Full Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                       id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nis" class="form-label">NIS (Student ID)</label>
                                <input type="text" class="form-control @error('nis') is-invalid @enderror"
                                       id="nis" name="nis" value="{{ old('nis') }}" required>
                                @error('nis')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                       id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                       id="phone" name="phone" value="{{ old('phone') }}" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="class" class="form-label">Class</label>
                                <select class="form-control @error('class') is-invalid @enderror" id="class" name="class" required>
                                    <option value="">Select Class</option>
                                    <option value="X IPA 1" {{ old('class') == 'X IPA 1' ? 'selected' : '' }}>X IPA 1</option>
                                    <option value="X IPA 2" {{ old('class') == 'X IPA 2' ? 'selected' : '' }}>X IPA 2</option>
                                    <option value="XI IPA 1" {{ old('class') == 'XI IPA 1' ? 'selected' : '' }}>XI IPA 1</option>
                                    <option value="XI IPA 2" {{ old('class') == 'XI IPA 2' ? 'selected' : '' }}>XI IPA 2</option>
                                    <option value="XII IPA 1" {{ old('class') == 'XII IPA 1' ? 'selected' : '' }}>XII IPA 1</option>
                                    <option value="XII IPA 2" {{ old('class') == 'XII IPA 2' ? 'selected' : '' }}>XII IPA 2</option>
                                </select>
                                @error('class')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control @error('address') is-invalid @enderror"
                                  id="address" name="address" rows="3" required>{{ old('address') }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-2"></i>Create Student
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
