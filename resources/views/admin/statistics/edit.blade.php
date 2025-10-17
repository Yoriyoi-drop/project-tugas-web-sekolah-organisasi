
@extends('admin.layouts.app')

@section('title', 'Edit Statistic')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Statistic</h3>
                </div>
                <form action="{{ route('admin.statistics.update', $statistic) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Label</label>
                            <input type="text" name="label" class="form-control @error('label') is-invalid @enderror" value="{{ old('label', $statistic->label) }}">
                            @error('label')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Value</label>
                            <input type="text" name="value" class="form-control @error('value') is-invalid @enderror" value="{{ old('value', $statistic->value) }}">
                            @error('value')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <input type="text" name="description" class="form-control @error('description') is-invalid @enderror" value="{{ old('description', $statistic->description) }}">
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Order</label>
                            <input type="number" name="order" class="form-control" value="{{ old('order', $statistic->order) }}">
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Update Statistic</button>
                        <a href="{{ route('admin.statistics.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
