@extends('admin.layouts.app')

@section('title', 'Settings')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Settings</h1>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">General Settings</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.settings.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="site_name" class="form-label">Site Name</label>
                        <input type="text" class="form-control @error('site_name') is-invalid @enderror" id="site_name" name="site_name" value="{{ old('site_name', $settings['site_name']) }}">
                        @error('site_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="site_description" class="form-label">Site Description</label>
                        <textarea class="form-control @error('site_description') is-invalid @enderror" id="site_description" name="site_description" rows="3">{{ old('site_description', $settings['site_description']) }}</textarea>
                        @error('site_description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="contact_email" class="form-label">Contact Email</label>
                                <input type="email" class="form-control @error('contact_email') is-invalid @enderror" id="contact_email" name="contact_email" value="{{ old('contact_email', $settings['contact_email']) }}">
                                @error('contact_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="contact_phone" class="form-label">Contact Phone</label>
                                <input type="text" class="form-control @error('contact_phone') is-invalid @enderror" id="contact_phone" name="contact_phone" value="{{ old('contact_phone', $settings['contact_phone']) }}">
                                @error('contact_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="address" class="form-label">School Address</label>
                        <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="2">{{ old('address', $settings['address']) }}</textarea>
                        @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-2"></i>Save Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">System Information</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="small text-gray-500">Laravel Version</div>
                    <div class="font-weight-bold">{{ app()->version() }}</div>
                </div>
                <div class="mb-3">
                    <div class="small text-gray-500">PHP Version</div>
                    <div class="font-weight-bold">{{ PHP_VERSION }}</div>
                </div>
                <div class="mb-3">
                    <div class="small text-gray-500">Database</div>
                    <div class="font-weight-bold">{{ config('database.default') }}</div>
                </div>
                <div class="mb-3">
                    <div class="small text-gray-500">Environment</div>
                    <div class="font-weight-bold">{{ app()->environment() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection