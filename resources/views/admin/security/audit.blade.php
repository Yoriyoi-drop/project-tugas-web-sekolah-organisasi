<x-app-layout>
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Security Audit Dashboard</h1>
        </div>

        <!-- Summary Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-700">Total OTP Attempts</h3>
                <p class="text-3xl font-bold mt-2">{{ $stats['total_otp_attempts'] }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-700">Failed Verifications</h3>
                <p class="text-3xl font-bold mt-2 text-red-600">{{ $stats['failed_verifications'] }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-700">Locked Accounts</h3>
                <p class="text-3xl font-bold mt-2 text-yellow-600">{{ $stats['locked_accounts'] }}</p>
                
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-700">Today's Events</h3>
                <p class="text-3xl font-bold mt-2">{{ $stats['today_events'] }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-700">High Risk Events (7d)</h3>
                <p class="text-3xl font-bold mt-2 text-red-600">{{ $stats['high_risk_events'] }}</p>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <form action="{{ route('admin.security.audit') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">User ID</label>
                    <input type="text" name="user_id" value="{{ $filters['user_id'] ?? '' }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">IP Address</label>
                    <input type="text" name="ip_address" value="{{ $filters['ip_address'] ?? '' }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Event Type</label>
                    <select name="action" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">All Events</option>
                        @foreach($eventTypes as $type)
                            <option value="{{ $type }}" {{ ($filters['action'] ?? '') == $type ? 'selected' : '' }}>
                                {{ Str::title(str_replace('_', ' ', $type)) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Date From</label>
                    <input type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Date To</label>
                    <input type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                        Filter Results
                    </button>
                </div>
            </form>
        </div>

        <!-- Export Button -->
        <div class="flex justify-end mb-4">
            <a href="{{ route('admin.security.export') }}?{{ http_build_query($filters) }}"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                Export to CSV
            </a>
        </div>

        <!-- Security Logs Table -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event Type</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP Address</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Risk Level</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created At</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($logs as $log)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $log->user->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ Str::title(str_replace('_', ' ', $log->action)) }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ is_array($log->data) ? ($log->data['description'] ?? '') : '' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $log->ip_address }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $log->risk_level === 'high' ? 'bg-red-100 text-red-800' :
                                       ($log->risk_level === 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                                    {{ Str::ucfirst($log->risk_level) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $log->created_at->format('Y-m-d H:i:s') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                No security logs found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $logs->withQueryString()->links() }}
        </div>

        <!-- Recent High Risk Events -->
        <div class="mt-8">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Recent High Risk Events</h2>
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="divide-y divide-gray-200">
                    @forelse($recentHighRiskEvents as $event)
                        <div class="px-6 py-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ Str::title(str_replace('_', ' ', $event['action'])) }}
                                    </p>
                                    <p class="text-sm text-gray-500">{{ $event['data']['description'] ?? '' }}</p>
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ Carbon\Carbon::parse($event['created_at'])->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-4 text-sm text-gray-500">
                            No high risk events found.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
