@extends('admin.layout')

@section('title', 'Analytics Dashboard')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Analytics Dashboard</h1>
        <div class="btn-group">
            <a href="{{ route('admin.analytics.reports') }}" class="btn btn-outline-primary">
                <i class="bi bi-file-earmark-text me-1"></i>Reports
            </a>
            <a href="{{ route('admin.analytics.performance') }}" class="btn btn-outline-success">
                <i class="bi bi-graph-up me-1"></i>Performance
            </a>
            <a href="{{ route('admin.analytics.compare') }}" class="btn btn-outline-info">
                <i class="bi bi-bar-chart me-1"></i>Compare
            </a>
        </div>
    </div>

    <!-- Overview Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Organizations</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_organizations'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-building fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Members</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['total_members']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-people fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Activities</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_activities'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-calendar-event fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Active Organizations</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['active_organizations'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Growth Trends Chart -->
    <div class="row mb-4">
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Growth Trends (Last 30 Days)</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="bi bi-three-dots-vertical"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in">
                            <a class="dropdown-item" href="#">Export Data</a>
                            <a class="dropdown-item" href="#">View Details</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#">Refresh</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="growthTrendsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Organizations -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Top Performing Organizations</h6>
                </div>
                <div class="card-body">
                    @foreach($topOrganizations as $org)
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-grow-1">
                            <div class="small text-gray-500">{{ $org->name }}</div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-primary" role="progressbar" 
                                     style="width: {{ min(100, ($org->activeMembers()->count() / 50) * 100) }}%"
                                     aria-valuenow="{{ $org->activeMembers()->count() }}" 
                                     aria-valuemin="0" aria-valuemax="100">
                                </div>
                            </div>
                        </div>
                        <div class="ms-3">
                            <span class="badge bg-primary">{{ $org->activeMembers()->count() }} members</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity & Organization List -->
    <div class="row">
        <!-- Recent Activity -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Activity</h6>
                </div>
                <div class="card-body">
                    @if($recentActivity->count() > 0)
                        @foreach($recentActivity as $activity)
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0">
                                @if($activity instanceof \App\Models\OrganizationDiscussion)
                                    <i class="bi bi-chat-dots text-primary"></i>
                                @elseif($activity instanceof \App\Models\OrganizationActivity)
                                    <i class="bi bi-calendar-event text-success"></i>
                                @elseif($activity instanceof \App\Models\OrganizationAnnouncement)
                                    <i class="bi bi-megaphone text-warning"></i>
                                @endif
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <div class="small text-gray-500">{{ $activity->organization->name }}</div>
                                <div class="text-sm">{{ Str::limit($activity->title ?? $activity->name, 50) }}</div>
                            </div>
                            <div class="flex-shrink-0">
                                <small class="text-gray-400">{{ $activity->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center text-muted py-3">
                            <i class="bi bi-activity fs-1 mb-2 d-block"></i>
                            <p>No recent activity found.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Organizations Overview -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Organizations Overview</h6>
                    <a href="{{ route('admin.organizations.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Organization</th>
                                    <th>Members</th>
                                    <th>Activities</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($organizations->take(5) as $org)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="me-2">
                                                <i class="bi bi-building" style="color: {{ $org->color ?? '#6c757d' }}"></i>
                                            </div>
                                            <div>{{ $org->name }}</div>
                                        </div>
                                    </td>
                                    <td>{{ $org->activeMembers()->count() }}</td>
                                    <td>{{ $org->activities()->count() }}</td>
                                    <td>
                                        @if($org->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Growth Trends Chart
    const ctx = document.getElementById('growthTrendsChart').getContext('2d');
    const growthTrends = @json($growthTrends);
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: growthTrends.map(item => item.date),
            datasets: [
                {
                    label: 'New Members',
                    data: growthTrends.map(item => item.members),
                    borderColor: 'rgb(75, 192, 192)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    tension: 0.1
                },
                {
                    label: 'Activities',
                    data: growthTrends.map(item => item.activities),
                    borderColor: 'rgb(255, 99, 132)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    tension: 0.1
                },
                {
                    label: 'Discussions',
                    data: growthTrends.map(item => item.discussions),
                    borderColor: 'rgb(54, 162, 235)',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    tension: 0.1
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
</script>
@endsection
