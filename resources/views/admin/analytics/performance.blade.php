@extends('admin.layout')

@section('title', 'Performance Metrics')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Performance Metrics</h1>
        <a href="{{ route('admin.analytics.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Back to Dashboard
        </a>
    </div>

    <!-- Grade Distribution -->
    <div class="row mb-4">
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Performance Grade Distribution</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="gradeDistributionChart"></canvas>
                    </div>
                    <div class="mt-3">
                        @foreach(['A', 'B', 'C', 'D', 'F'] as $grade)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="d-flex align-items-center">
                                <span class="badge bg-{{ $grade === 'A' ? 'success' : ($grade === 'B' ? 'info' : ($grade === 'C' ? 'warning' : ($grade === 'D' ? 'danger' : 'dark'))) }} me-2">{{ $grade }}</span>
                                <span class="small">{{ $grade === 'A' ? 'Excellent' : ($grade === 'B' ? 'Good' : ($grade === 'C' ? 'Average' : ($grade === 'D' ? 'Below Average' : 'Poor'))) }}</span>
                            </div>
                            <span class="badge bg-light text-dark">{{ $gradeDistribution[$grade] ?? 0 }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Performance Summary -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Performance Summary</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3 mb-3">
                            <div class="h4 mb-1 text-success">{{ $metrics->where('performance_grade', 'A')->count() }}</div>
                            <div class="small text-gray-500">Excellent (A)</div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="h4 mb-1 text-info">{{ $metrics->where('performance_grade', 'B')->count() }}</div>
                            <div class="small text-gray-500">Good (B)</div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="h4 mb-1 text-warning">{{ $metrics->where('performance_grade', 'C')->count() }}</div>
                            <div class="small text-gray-500">Average (C)</div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="h4 mb-1 text-danger">{{ $metrics->whereIn('performance_grade', ['D', 'F'])->count() }}</div>
                            <div class="small text-gray-500">Needs Improvement</div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="small text-gray-500 mb-1">Average Performance Score</div>
                            <div class="h5">{{ number_format($metrics->avg('overall_performance_score'), 1) }}/100</div>
                        </div>
                        <div class="col-md-6">
                            <div class="small text-gray-500 mb-1">Total Organizations Evaluated</div>
                            <div class="h5">{{ $metrics->count() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top and Low Performers -->
    <div class="row">
        <!-- Top Performers -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-success">Top Performers</h6>
                    <span class="badge bg-success">{{ $topPerformers->count() }} Organizations</span>
                </div>
                <div class="card-body">
                    @if($topPerformers->count() > 0)
                        @foreach($topPerformers as $metric)
                        <div class="d-flex align-items-center mb-3 p-3 bg-light rounded">
                            <div class="flex-grow-1">
                                <div class="fw-semibold">{{ $metric->organization->name }}</div>
                                <div class="small text-gray-500">{{ $metric->organization->type }}</div>
                                <div class="mt-2">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="small">Overall Score</span>
                                        <span class="small fw-bold">{{ number_format($metric->overall_performance_score, 1) }}/100</span>
                                    </div>
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar bg-success" role="progressbar" 
                                             style="width: {{ $metric->overall_performance_score }}%"
                                             aria-valuenow="{{ $metric->overall_performance_score }}" 
                                             aria-valuemin="0" aria-valuemax="100">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="ms-3">
                                <span class="badge bg-success fs-6">{{ $metric->performance_grade }}</span>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center text-muted py-3">
                            <i class="bi bi-trophy fs-1 mb-2 d-block"></i>
                            <p>No top performers found.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Low Performers -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-danger">Needs Improvement</h6>
                    <span class="badge bg-danger">{{ $lowPerformers->count() }} Organizations</span>
                </div>
                <div class="card-body">
                    @if($lowPerformers->count() > 0)
                        @foreach($lowPerformers as $metric)
                        <div class="d-flex align-items-center mb-3 p-3 bg-light rounded">
                            <div class="flex-grow-1">
                                <div class="fw-semibold">{{ $metric->organization->name }}</div>
                                <div class="small text-gray-500">{{ $metric->organization->type }}</div>
                                <div class="mt-2">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="small">Overall Score</span>
                                        <span class="small fw-bold">{{ number_format($metric->overall_performance_score, 1) }}/100</span>
                                    </div>
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar bg-danger" role="progressbar" 
                                             style="width: {{ $metric->overall_performance_score }}%"
                                             aria-valuenow="{{ $metric->overall_performance_score }}" 
                                             aria-valuemin="0" aria-valuemax="100">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="ms-3">
                                <span class="badge bg-{{ $metric->grade_color }} fs-6">{{ $metric->performance_grade }}</span>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center text-muted py-3">
                            <i class="bi bi-exclamation-triangle fs-1 mb-2 d-block"></i>
                            <p>No organizations need improvement.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Metrics Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Detailed Performance Metrics</h6>
            <div class="d-flex">
                <input type="text" class="form-control form-control-sm me-2" placeholder="Search organizations..." id="searchMetrics">
                <select class="form-select form-select-sm" id="filterGrade">
                    <option value="">All Grades</option>
                    <option value="A">A - Excellent</option>
                    <option value="B">B - Good</option>
                    <option value="C">C - Average</option>
                    <option value="D">D - Below Average</option>
                    <option value="F">F - Poor</option>
                </select>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="metricsTable">
                    <thead>
                        <tr>
                            <th>Organization</th>
                            <th>Grade</th>
                            <th>Overall Score</th>
                            <th>Member Metrics</th>
                            <th>Activity Metrics</th>
                            <th>Engagement</th>
                            <th>Growth Rate</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($metrics as $metric)
                        <tr data-grade="{{ $metric->performance_grade }}">
                            <td>
                                <div class="fw-semibold">{{ $metric->organization->name }}</div>
                                <div class="small text-gray-500">{{ $metric->organization->type }}</div>
                            </td>
                            <td>
                                <span class="badge bg-{{ $metric->grade_color }} fs-6">{{ $metric->performance_grade }}</span>
                            </td>
                            <td>
                                <div class="fw-bold">{{ number_format($metric->overall_performance_score, 1) }}</div>
                                <div class="small text-gray-500">/ 100</div>
                            </td>
                            <td>
                                <div class="small">
                                    <div>Retention: {{ number_format($metric->member_retention_rate, 1) }}%</div>
                                    <div>Satisfaction: {{ number_format($metric->member_satisfaction_score, 1) }}/5</div>
                                </div>
                            </td>
                            <td>
                                <div class="small">
                                    <div>Completion: {{ number_format($metric->activity_completion_rate, 1) }}%</div>
                                    <div>Participation: {{ number_format($metric->average_participation_rate, 1) }}%</div>
                                </div>
                            </td>
                            <td>
                                <div class="small">
                                    <div>Discussions: {{ number_format($metric->discussion_engagement_rate, 1) }}%</div>
                                    <div>Read Rate: {{ number_format($metric->announcement_read_rate, 1) }}%</div>
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold {{ $metric->growth_rate >= 0 ? 'text-success' : 'text-danger' }}">
                                    {{ $metric->growth_rate >= 0 ? '+' : '' }}{{ number_format($metric->growth_rate, 1) }}%
                                </div>
                            </td>
                            <td>
                                <div>{{ $metric->formatted_date }}</div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted">
                    Showing {{ $metrics->firstItem() }} to {{ $metrics->lastItem() }} of {{ $metrics->total() }} entries
                </div>
                {{ $metrics->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Grade Distribution Chart
    const ctx = document.getElementById('gradeDistributionChart').getContext('2d');
    const gradeData = @json($gradeDistribution);
    
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['A - Excellent', 'B - Good', 'C - Average', 'D - Below Average', 'F - Poor'],
            datasets: [{
                data: [
                    gradeData['A'] || 0,
                    gradeData['B'] || 0,
                    gradeData['C'] || 0,
                    gradeData['D'] || 0,
                    gradeData['F'] || 0
                ],
                backgroundColor: [
                    '#28a745',
                    '#17a2b8',
                    '#ffc107',
                    '#dc3545',
                    '#6c757d'
                ],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                },
                title: {
                    display: false
                }
            }
        }
    });
    
    // Search functionality
    document.getElementById('searchMetrics').addEventListener('input', function(e) {
        filterMetrics();
    });
    
    // Filter functionality
    document.getElementById('filterGrade').addEventListener('change', filterMetrics);
});

function filterMetrics() {
    const searchValue = document.getElementById('searchMetrics').value.toLowerCase();
    const gradeFilter = document.getElementById('filterGrade').value;
    const rows = document.querySelectorAll('#metricsTable tbody tr');
    
    rows.forEach(row => {
        const orgName = row.querySelector('td:first-child').textContent.toLowerCase();
        const grade = row.dataset.grade;
        
        const matchesSearch = orgName.includes(searchValue);
        const matchesGrade = !gradeFilter || grade === gradeFilter;
        
        row.style.display = matchesSearch && matchesGrade ? '' : 'none';
    });
}
</script>
@endsection
