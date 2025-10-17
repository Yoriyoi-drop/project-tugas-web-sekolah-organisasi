@extends('admin.layouts.app')

@section('title', 'Create Statistic')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Create New Statistic</h3>
                </div>
                <form action="{{ route('admin.statistics.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Label</label>
                            <input type="text" name="label" class="form-control @error('label') is-invalid @enderror" value="{{ old('label') }}">
                            @error('label')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Value</label>
                            <input type="text" name="value" class="form-control @error('value') is-invalid @enderror" value="{{ old('value') }}">
                            @error('value')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <input type="text" name="description" class="form-control @error('description') is-invalid @enderror" value="{{ old('description') }}">
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Order</label>
                            <input type="number" name="order" class="form-control" value="{{ old('order', 0) }}">
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Create Statistic</button>
                        <a href="{{ route('admin.statistics.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection