@extends('admin.layout')

@section('title', 'Analytics Reports')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Analytics Reports</h1>
        <div class="btn-group">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#generateReportModal">
                <i class="bi bi-plus-circle me-1"></i>Generate Report
            </button>
            <a href="{{ route('admin.analytics.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Report Statistics -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Reports</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-file-earmark-text fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Completed</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['completed'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-check-circle fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Pending</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-clock fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Downloads</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_downloads'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-download fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reports Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">All Reports</h6>
            <div class="d-flex">
                <input type="text" class="form-control form-control-sm me-2" placeholder="Search reports..." id="searchReports">
                <select class="form-select form-select-sm me-2" id="filterType">
                    <option value="">All Types</option>
                    <option value="membership">Membership</option>
                    <option value="activity">Activity</option>
                    <option value="engagement">Engagement</option>
                    <option value="performance">Performance</option>
                </select>
                <select class="form-select form-select-sm" id="filterStatus">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="generating">Generating</option>
                    <option value="completed">Completed</option>
                    <option value="failed">Failed</option>
                </select>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="reportsTable">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Organization</th>
                            <th>Type</th>
                            <th>Format</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Downloads</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reports as $report)
                        <tr data-type="{{ $report->type }}" data-status="{{ $report->status }}">
                            <td>
                                <div class="fw-semibold">{{ $report->title }}</div>
                                @if($report->description)
                                <div class="small text-gray-500">{{ Str::limit($report->description, 50) }}</div>
                                @endif
                            </td>
                            <td>
                                @if($report->organization)
                                    <span class="badge bg-light text-dark">{{ $report->organization->name }}</span>
                                @else
                                    <span class="badge bg-info">Global</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $report->type === 'membership' ? 'primary' : ($report->type === 'activity' ? 'success' : ($report->type === 'engagement' ? 'info' : 'warning')) }}">
                                    {{ $report->formatted_type }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ strtoupper($report->format) }}</span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $report->status_color }}">
                                    {{ $report->formatted_status }}
                                </span>
                            </td>
                            <td>
                                <div>{{ $report->created_at->format('M d, Y') }}</div>
                                <div class="small text-gray-500">{{ $report->time_ago }}</div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark">{{ $report->download_count }}</span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    @if($report->canBeDownloaded())
                                        <a href="{{ $report->download_url }}" class="btn btn-outline-primary" title="Download">
                                            <i class="bi bi-download"></i>
                                        </a>
                                    @endif
                                    @if($report->canBeGenerated())
                                        <button class="btn btn-outline-success" onclick="regenerateReport({{ $report->id }})" title="Regenerate">
                                            <i class="bi bi-arrow-clockwise"></i>
                                        </button>
                                    @endif
                                    <button class="btn btn-outline-danger" onclick="deleteReport({{ $report->id }})" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted">
                    Showing {{ $reports->firstItem() }} to {{ $reports->lastItem() }} of {{ $reports->total() }} entries
                </div>
                {{ $reports->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Generate Report Modal -->
<div class="modal fade" id="generateReportModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Generate New Report</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.analytics.reports.generate') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="title" class="form-label">Report Title</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="type" class="form-label">Report Type</label>
                            <select class="form-select" id="type" name="type" required>
                                <option value="membership">Membership Report</option>
                                <option value="activity">Activity Report</option>
                                <option value="engagement">Engagement Report</option>
                                <option value="performance">Performance Report</option>
                                <option value="custom">Custom Report</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="format" class="form-label">Format</label>
                            <select class="form-select" id="format" name="format" required>
                                <option value="pdf">PDF</option>
                                <option value="excel">Excel</option>
                                <option value="csv">CSV</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="organization_id" class="form-label">Organization (Optional)</label>
                        <select class="form-select" id="organization_id" name="organization_id">
                            <option value="">All Organizations</option>
                            @foreach(\App\Models\Organization::all() as $org)
                            <option value="{{ $org->id }}">{{ $org->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Generate Report</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search functionality
    document.getElementById('searchReports').addEventListener('input', function(e) {
        filterReports();
    });
    
    // Filter functionality
    document.getElementById('filterType').addEventListener('change', filterReports);
    document.getElementById('filterStatus').addEventListener('change', filterReports);
    
    // Set default dates
    const today = new Date();
    const lastMonth = new Date(today.getFullYear(), today.getMonth() - 1, today.getDate());
    
    document.getElementById('start_date').value = lastMonth.toISOString().split('T')[0];
    document.getElementById('end_date').value = today.toISOString().split('T')[0];
});

function filterReports() {
    const searchValue = document.getElementById('searchReports').value.toLowerCase();
    const typeFilter = document.getElementById('filterType').value;
    const statusFilter = document.getElementById('filterStatus').value;
    const rows = document.querySelectorAll('#reportsTable tbody tr');
    
    rows.forEach(row => {
        const title = row.querySelector('td:first-child').textContent.toLowerCase();
        const type = row.dataset.type;
        const status = row.dataset.status;
        
        const matchesSearch = title.includes(searchValue);
        const matchesType = !typeFilter || type === typeFilter;
        const matchesStatus = !statusFilter || status === statusFilter;
        
        row.style.display = matchesSearch && matchesType && matchesStatus ? '' : 'none';
    });
}

function regenerateReport(reportId) {
    if (confirm('Are you sure you want to regenerate this report?')) {
        // Implement regeneration logic
        window.location.reload();
    }
}

function deleteReport(reportId) {
    if (confirm('Are you sure you want to delete this report?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/analytics/reports/${reportId}`;
        form.innerHTML = '<input type="hidden" name="_method" value="DELETE"><input type="hidden" name="_token" value="{{ csrf_token() }}">';
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection
