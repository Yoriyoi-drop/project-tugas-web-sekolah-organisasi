@extends('admin.layouts.app')

@section('title', 'Activity Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Activity Details</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.activities.index') }}" class="btn btn-sm btn-secondary">Back to Activities</a>
                        <a href="{{ route('admin.activities.edit', $activity) }}" class="btn btn-sm btn-primary">Edit</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Title:</label>
                                <p>{{ $activity->title }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Date:</label>
                                <p>{{ $activity->date }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Location:</label>
                                <p>{{ $activity->location }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Category:</label>
                                <p>{{ $activity->category }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="font-weight-bold">Description:</label>
                        <p>{{ $activity->description }}</p>
                    </div>
                </div>
                
                <div class="card-footer">
                    <form action="{{ route('admin.activities.destroy', $activity) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this activity?')">Delete</button>
                    </form>
                    <a href="{{ route('admin.activities.edit', $activity) }}" class="btn btn-primary btn-sm">Edit</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection