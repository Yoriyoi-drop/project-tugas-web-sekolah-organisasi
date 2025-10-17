@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Posts</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['posts'] ?? 0 }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-file-text fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Organizations</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['organizations'] ?? 0 }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-people fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Activities</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['activities'] ?? 0 }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-calendar-event fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Students</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['students'] ?? 0 }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-mortarboard fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.posts.create') }}" class="btn btn-primary btn-block">
                            <i class="bi bi-plus-circle me-2"></i>New Post
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.organizations.create') }}" class="btn btn-success btn-block">
                            <i class="bi bi-plus-circle me-2"></i>New Organization
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.activities.create') }}" class="btn btn-warning btn-block">
                            <i class="bi bi-plus-circle me-2"></i>New Activity
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.statistics.create') }}" class="btn btn-info btn-block">
                            <i class="bi bi-plus-circle me-2"></i>New Statistic
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Content Overview -->
<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="card shadow">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Recent Posts</h6>
                <a href="{{ route('admin.posts.index') }}" class="btn btn-sm btn-primary">View All</a>
            </div>
            <div class="card-body">
                @forelse($recentPosts ?? [] as $post)
                    <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                        <div class="me-3">
                            <div class="icon-circle bg-primary">
                                <i class="bi bi-file-text text-white"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="small text-gray-500">{{ $post->created_at->format('M d, Y') }}</div>
                            <div class="font-weight-bold">{{ Str::limit($post->title, 40) }}</div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4">
                        <i class="bi bi-file-text text-gray-300" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-2">No posts yet</p>
                        <a href="{{ route('admin.posts.create') }}" class="btn btn-primary btn-sm">Create First Post</a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
    
    <div class="col-lg-6 mb-4">
        <div class="card shadow">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-success">Recent Activities</h6>
                <a href="{{ route('admin.activities.index') }}" class="btn btn-sm btn-success">View All</a>
            </div>
            <div class="card-body">
                @forelse($recentActivities ?? [] as $activity)
                    <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                        <div class="me-3">
                            <div class="icon-circle bg-success">
                                <i class="bi bi-calendar-event text-white"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="small text-gray-500">{{ $activity->created_at->format('M d, Y') }}</div>
                            <div class="font-weight-bold">{{ Str::limit($activity->title, 40) }}</div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4">
                        <i class="bi bi-calendar-event text-gray-300" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-2">No activities yet</p>
                        <a href="{{ route('admin.activities.create') }}" class="btn btn-success btn-sm">Create First Activity</a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- System Info -->
<div class="row">
    <div class="col-lg-8 mb-4">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Content Statistics</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 text-center">
                        <div class="mb-3">
                            <div class="h4 text-primary">{{ $stats['published_posts'] ?? 0 }}</div>
                            <div class="small text-gray-500">Published Posts</div>
                        </div>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="mb-3">
                            <div class="h4 text-success">{{ $stats['active_organizations'] ?? 0 }}</div>
                            <div class="small text-gray-500">Active Organizations</div>
                        </div>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="mb-3">
                            <div class="h4 text-warning">{{ $stats['upcoming_activities'] ?? 0 }}</div>
                            <div class="small text-gray-500">Upcoming Activities</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 mb-4">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">System Status</h6>
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
                    <div class="small text-gray-500">Last Login</div>
                    <div class="font-weight-bold">{{ auth()->user()->updated_at->diffForHumans() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection