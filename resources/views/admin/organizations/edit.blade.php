@extends('admin.layouts.app')

@section('title', 'Edit Organization')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Organization</h3>
                </div>
                <form action="{{ route('admin.organizations.update', $organization) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $organization->name) }}">
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Type</label>
                            <select name="type" class="form-control @error('type') is-invalid @enderror">
                                <option value="">Select Type</option>
                                <option value="OSIS" {{ old('type', $organization->type) == 'OSIS' ? 'selected' : '' }}>OSIS</option>
                                <option value="Pramuka" {{ old('type', $organization->type) == 'Pramuka' ? 'selected' : '' }}>Pramuka</option>
                                <option value="PMR" {{ old('type', $organization->type) == 'PMR' ? 'selected' : '' }}>PMR</option>
                                <option value="Rohis" {{ old('type', $organization->type) == 'Rohis' ? 'selected' : '' }}>Rohis</option>
                            </select>
                            @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Icon (Bootstrap Icon)</label>
                            <input type="text" name="icon" class="form-control @error('icon') is-invalid @enderror" value="{{ old('icon', $organization->icon) }}" placeholder="bi-people-fill">
                            @error('icon')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Color</label>
                            <select name="color" class="form-control">
                                <option value="primary" {{ old('color', $organization->color) == 'primary' ? 'selected' : '' }}>Primary</option>
                                <option value="success" {{ old('color', $organization->color) == 'success' ? 'selected' : '' }}>Success</option>
                                <option value="warning" {{ old('color', $organization->color) == 'warning' ? 'selected' : '' }}>Warning</option>
                                <option value="danger" {{ old('color', $organization->color) == 'danger' ? 'selected' : '' }}>Danger</option>
                                <option value="info" {{ old('color', $organization->color) == 'info' ? 'selected' : '' }}>Info</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tags (comma separated)</label>
                            <input type="text" name="tags" class="form-control" value="{{ old('tags', $organization->tags ? implode(', ', $organization->tags) : '') }}" placeholder="Kepemimpinan, Event Organizer, Dakwah">
                            <small class="form-text text-muted">Separate tags with commas</small>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Order</label>
                                    <input type="number" name="order" class="form-control" value="{{ old('order', $organization->order) }}" min="0">
                                    <small class="form-text text-muted">Display order (0 = first)</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <select name="is_active" class="form-control">
                                        <option value="1" {{ old('is_active', $organization->is_active) == 1 ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ old('is_active', $organization->is_active) == 0 ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tagline</label>
                            <input type="text" name="tagline" class="form-control" value="{{ old('tagline', $organization->tagline) }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" rows="5" class="form-control @error('description') is-invalid @enderror">{{ old('description', $organization->description) }}</textarea>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Programs (one per line)</label>
                            <textarea name="programs" rows="6" class="form-control" placeholder="Program Kerja 1&#10;Program Kerja 2&#10;Program Kerja 3">{{ old('programs', $organization->programs ? implode("\n", $organization->programs) : '') }}</textarea>
                            <small class="form-text text-muted">Enter each program on a new line</small>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Leadership Structure</label>
                            <div id="leadership-container">
                                @if($organization->leadership && count($organization->leadership) > 0)
                                    @foreach($organization->leadership as $leader)
                                    <div class="row leadership-row mb-2">
                                        <div class="col-md-5">
                                            <input type="text" name="leadership_names[]" class="form-control" placeholder="Name" value="{{ $leader['name'] ?? '' }}">
                                        </div>
                                        <div class="col-md-5">
                                            <input type="text" name="leadership_positions[]" class="form-control" placeholder="Position" value="{{ $leader['position'] ?? '' }}">
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-danger btn-sm remove-leadership">-</button>
                                        </div>
                                    </div>
                                    @endforeach
                                @else
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
                                @endif
                            </div>
                            <button type="button" class="btn btn-success btn-sm mt-2 add-leadership">Add Leadership</button>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control" value="{{ old('email', $organization->email) }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Phone</label>
                                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $organization->phone) }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Location</label>
                                    <input type="text" name="location" class="form-control" value="{{ old('location', $organization->location) }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Update Organization</button>
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
    
    document.addEventListener('click', function(e) {
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