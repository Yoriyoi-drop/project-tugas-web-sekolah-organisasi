@extends('admin.layouts.app')

@section('title', 'Create Organization')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Create New Organization</h3>
                </div>
                <form action="{{ route('admin.organizations.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}">
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Type</label>
                            <select name="type" class="form-control @error('type') is-invalid @enderror">
                                <option value="">Select Type</option>
                                <option value="OSIS" {{ old('type') == 'OSIS' ? 'selected' : '' }}>OSIS</option>
                                <option value="Pramuka" {{ old('type') == 'Pramuka' ? 'selected' : '' }}>Pramuka</option>
                                <option value="PMR" {{ old('type') == 'PMR' ? 'selected' : '' }}>PMR</option>
                                <option value="Rohis" {{ old('type') == 'Rohis' ? 'selected' : '' }}>Rohis</option>
                            </select>
                            @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Icon (Bootstrap Icon)</label>
                            <input type="text" name="icon" class="form-control @error('icon') is-invalid @enderror" value="{{ old('icon') }}" placeholder="bi-people-fill">
                            @error('icon')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Color</label>
                            <select name="color" class="form-control">
                                <option value="primary" {{ old('color') == 'primary' ? 'selected' : '' }}>Primary</option>
                                <option value="success" {{ old('color') == 'success' ? 'selected' : '' }}>Success</option>
                                <option value="warning" {{ old('color') == 'warning' ? 'selected' : '' }}>Warning</option>
                                <option value="danger" {{ old('color') == 'danger' ? 'selected' : '' }}>Danger</option>
                                <option value="info" {{ old('color') == 'info' ? 'selected' : '' }}>Info</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tags (comma separated)</label>
                            <input type="text" name="tags" class="form-control" value="{{ old('tags') }}" placeholder="Kepemimpinan, Event Organizer, Dakwah">
                            <small class="form-text text-muted">Separate tags with commas</small>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Order</label>
                                    <input type="number" name="order" class="form-control" value="{{ old('order', 0) }}" min="0">
                                    <small class="form-text text-muted">Display order (0 = first)</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <select name="is_active" class="form-control">
                                        <option value="1" {{ old('is_active', 1) == 1 ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ old('is_active') == 0 ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tagline</label>
                            <input type="text" name="tagline" class="form-control" value="{{ old('tagline') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" rows="5" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Programs (one per line)</label>
                            <textarea name="programs" rows="6" class="form-control" placeholder="Program Kerja 1&#10;Program Kerja 2&#10;Program Kerja 3">{{ old('programs') }}</textarea>
                            <small class="form-text text-muted">Enter each program on a new line</small>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Leadership Structure</label>
                            <div id="leadership-container">
                                <div class="row leadership-row mb-2">
                                    <div class="col-md-5">
                                        <input type="text" name="leadership_names[]" class="form-control" placeholder="Name">
                                    </div>
                                    <div class="col-md-5">
                                        <input type="text" name="leadership_positions[]" class="form-control" placeholder="Position">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-success btn-sm add-leadership">+</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Phone</label>
                                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Location</label>
                                    <input type="text" name="location" class="form-control" value="{{ old('location') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Create Organization</button>
                        <a href="{{ route('admin.organizations.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('leadership-container');
    
    container.addEventListener('click', function(e) {
        if (e.target.classList.contains('add-leadership')) {
            const newRow = document.createElement('div');
            newRow.className = 'row leadership-row mb-2';
            newRow.innerHTML = `
                <div class="col-md-5">
                    <input type="text" name="leadership_names[]" class="form-control" placeholder="Name">
                </div>
                <div class="col-md-5">
                    <input type="text" name="leadership_positions[]" class="form-control" placeholder="Position">
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger btn-sm remove-leadership">-</button>
                </div>
            `;
            container.appendChild(newRow);
        }
        
        if (e.target.classList.contains('remove-leadership')) {
            e.target.closest('.leadership-row').remove();
        }
    });
});
</script>
@endsection