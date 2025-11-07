{{-- Admin Teachers Edit --}}
@extends('admin.layouts.app')
@section('content')
<div class="container py-4">
	<div class="card">
		<div class="card-header">
			<h5 class="mb-0">Edit Teacher</h5>
		</div>
		<div class="card-body">
			<form method="POST" action="{{ route('admin.teachers.update', $teacher) }}">
				@csrf
				@method('PUT')

				<div class="mb-3">
					<label for="name" class="form-label">Name</label>
					<input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $teacher->name) }}" required>
					@error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
				</div>

				<div class="row g-3">
					<div class="col-md-6">
						<label for="email" class="form-label">Email</label>
						<input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $teacher->email) }}" required>
						@error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
					</div>
					<div class="col-md-6">
						<label for="phone" class="form-label">Phone</label>
						<input type="text" id="phone" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $teacher->phone) }}" required>
						@error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
					</div>
				</div>

				<div class="row g-3 mt-1">
					<div class="col-md-6">
						<label for="subject" class="form-label">Subject</label>
						<input type="text" id="subject" name="subject" class="form-control @error('subject') is-invalid @enderror" value="{{ old('subject', $teacher->subject) }}" required>
						@error('subject')<div class="invalid-feedback">{{ $message }}</div>@enderror
					</div>
					<div class="col-md-6">
						<label for="qualification" class="form-label">Qualification</label>
						<input type="text" id="qualification" name="qualification" class="form-control @error('qualification') is-invalid @enderror" value="{{ old('qualification', $teacher->qualification) }}" required>
						@error('qualification')<div class="invalid-feedback">{{ $message }}</div>@enderror
					</div>
				</div>

				<div class="d-flex justify-content-end mt-4">
					<a href="{{ route('admin.teachers.index') }}" class="btn btn-secondary me-2">Cancel</a>
					<button type="submit" class="btn btn-primary">Update</button>
				</div>
			</form>
		</div>
	</div>
	</div>
@endsection
