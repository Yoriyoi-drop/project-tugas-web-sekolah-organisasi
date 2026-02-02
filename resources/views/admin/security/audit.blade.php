@extends('admin.layouts.app')

@section('title', 'Security Audit Dashboard')

@section('content')
<div class="container-fluid px-4">
    <div class="mb-4">
        <h1 class="h2 mb-0 text-gray-800">Security Audit Dashboard</h1>
    </div>

        <!-- Summary Statistics -->
        <div class="row mb-4">
            <div class="col-xl-2 col-md-4 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total OTP Attempts</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_otp_attempts'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-shield-lock fs-2 text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-4 mb-4">
                <div class="card border-left-danger shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Failed Verifications</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['failed_verifications'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-x-circle fs-2 text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-4 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Locked Accounts</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['locked_accounts'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-lock fs-2 text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-4 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Today's Events</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['today_events'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-calendar-day fs-2 text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-4 mb-4">
                <div class="card border-left-danger shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">High Risk Events (7d)</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['high_risk_events'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-exclamation-triangle fs-2 text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Filter Logs</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.security.audit') }}" method="GET" class="row g-3">
                    <div class="col-md-2">
                        <label class="form-label">User ID</label>
                        <input type="text" name="user_id" value="{{ $filters['user_id'] ?? '' }}"
                            class="form-control">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">IP Address</label>
                        <input type="text" name="ip_address" value="{{ $filters['ip_address'] ?? '' }}"
                            class="form-control">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Event Type</label>
                        <select name="action" class="form-select">
                            <option value="">All Events</option>
                            @foreach($eventTypes as $type)
                                <option value="{{ $type }}" {{ ($filters['action'] ?? '') == $type ? 'selected' : '' }}>
                                    {{ Str::title(str_replace('_', ' ', $type)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Date From</label>
                        <input type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}"
                            class="form-control">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Date To</label>
                        <input type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}"
                            class="form-control">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            Filter Results
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Export Button -->
        <div class="d-flex justify-content-end mb-4">
            <a href="{{ route('admin.security.export') }}?{{ http_build_query($filters) }}"
                class="btn btn-success">
                <i class="bi bi-download me-1"></i>Export to CSV
            </a>
        </div>

        <!-- Security Logs Table -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Security Logs</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Event Type</th>
                                <th>Description</th>
                                <th>IP Address</th>
                                <th>Risk Level</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $log)
                                <tr>
                                    <td>{{ $log->user->name ?? 'N/A' }}</td>
                                    <td>{{ Str::title(str_replace('_', ' ', $log->action)) }}</td>
                                    <td>{{ is_array($log->data) ? ($log->data['description'] ?? '') : '' }}</td>
                                    <td>{{ $log->ip_address }}</td>
                                    <td>
                                        <span class="badge
                                            {{ $log->risk_level === 'high' ? 'bg-danger' :
                                               ($log->risk_level === 'medium' ? 'bg-warning text-dark' : 'bg-success') }}">
                                            {{ Str::ucfirst($log->risk_level) }}
                                        </span>
                                    </td>
                                    <td>{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">No security logs found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $logs->withQueryString()->links() }}
        </div>

        <!-- Recent High Risk Events -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-danger">Recent High Risk Events</h6>
            </div>
            <div class="card-body">
                @forelse($recentHighRiskEvents as $event)
                    <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-3">
                        <div>
                            <h6 class="font-weight-bold">{{ Str::title(str_replace('_', ' ', $event['action'])) }}</h6>
                            <p class="mb-0">{{ $event['data']['description'] ?? '' }}</p>
                        </div>
                        <small class="text-muted">{{ Carbon\Carbon::parse($event['created_at'])->diffForHumans() }}</small>
                    </div>
                @empty
                    <p class="text-muted">No high risk events found.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
