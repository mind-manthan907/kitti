@extends('layouts.admin')

@section('content')

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="px-4 py-6 sm:px-0">
            <h1 class="text-3xl font-bold text-gray-900">Audit Logs</h1>
            <p class="mt-2 text-gray-600">Track all system activities and user actions</p>
        </div>

        <!-- Filters -->
        <div class="px-4 sm:px-0 mb-6">
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Filters</h3>
                    <form method="GET" class="grid grid-cols-1 gap-4 sm:grid-cols-4">
                        <div>
                            <label for="action" class="block text-sm font-medium text-gray-700">Action</label>
                            <select id="action" name="action" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">All Actions</option>
                                <option value="login" {{ request('action') === 'login' ? 'selected' : '' }}>Login</option>
                                <option value="logout" {{ request('action') === 'logout' ? 'selected' : '' }}>Logout</option>
                                <option value="approve_registration" {{ request('action') === 'approve_registration' ? 'selected' : '' }}>Approve Registration</option>
                                <option value="reject_registration" {{ request('action') === 'reject_registration' ? 'selected' : '' }}>Reject Registration</option>
                                <option value="process_payment" {{ request('action') === 'process_payment' ? 'selected' : '' }}>Process Payment</option>
                                <option value="approve_discontinue" {{ request('action') === 'approve_discontinue' ? 'selected' : '' }}>Approve Discontinue</option>
                                <option value="reject_discontinue" {{ request('action') === 'reject_discontinue' ? 'selected' : '' }}>Reject Discontinue</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="user_id" class="block text-sm font-medium text-gray-700">User</label>
                            <select id="user_id" name="user_id" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">All Users</option>
                                @foreach($logs->pluck('user')->unique() as $user)
                                    @if($user)
                                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label for="date_from" class="block text-sm font-medium text-gray-700">Date From</label>
                            <input type="date" id="date_from" name="date_from" value="{{ request('date_from') }}"
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        
                        <div>
                            <label for="date_to" class="block text-sm font-medium text-gray-700">Date To</label>
                            <input type="date" id="date_to" name="date_to" value="{{ request('date_to') }}"
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        
                        <div class="sm:col-span-4 flex justify-end space-x-3">
                            <a href="{{ route('admin.audit-logs') }}" 
                               class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                                Clear Filters
                            </a>
                            <button type="submit" 
                                    class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Apply Filters
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Audit Logs Table -->
        <div class="px-4 sm:px-0">
            <div class="bg-white shadow overflow-hidden sm:rounded-md">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">System Activity Logs</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">Detailed log of all system activities and user actions</p>
                </div>
                
                @if($logs->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Timestamp</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP Address</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User Agent</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($logs as $log)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $log->created_at->format('M d, Y \a\t g:i A') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @if($log->user)
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-8 w-8">
                                                <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center">
                                                    <span class="text-sm font-medium text-indigo-600">
                                                        {{ strtoupper(substr($log->user->name, 0, 1)) }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="ml-3">
                                                <div class="text-sm font-medium text-gray-900">{{ $log->user->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $log->user->email }}</div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-gray-500">System</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        @if(in_array($log->action, ['approve_registration', 'process_payment', 'approve_discontinue'])) bg-green-100 text-green-800
                                        @elseif(in_array($log->action, ['reject_registration', 'reject_discontinue'])) bg-red-100 text-red-800
                                        @else bg-blue-100 text-blue-800 @endif">
                                        {{ ucwords(str_replace('_', ' ', $log->action)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    <div class="max-w-xs">
                                        {{ $log->description }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $log->ip_address }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    <div class="max-w-xs truncate" title="{{ $log->user_agent }}">
                                        {{ $log->user_agent }}
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                    {{ $logs->links() }}
                </div>
                @else
                <div class="text-center py-12">
                    <i class="fas fa-clipboard-list text-4xl text-gray-400 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No audit logs found</h3>
                    <p class="text-gray-500">No system activities have been logged yet.</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Export Options -->
        <div class="mt-8 px-4 sm:px-0">
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Export Options</h3>
                    <div class="flex space-x-3">
                        <button type="button" id="exportCsvBtn"
                                class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                            <i class="fas fa-download mr-2"></i>
                            Export as CSV
                        </button>
                        <button type="button" id="exportPdfBtn"
                                class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                            <i class="fas fa-file-pdf mr-2"></i>
                            Export as PDF
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const exportCsvBtn = document.getElementById('exportCsvBtn');
            const exportPdfBtn = document.getElementById('exportPdfBtn');
            
            if (exportCsvBtn) {
                exportCsvBtn.addEventListener('click', function() {
                    // TODO: Implement CSV export
                    alert('CSV export functionality will be implemented soon');
                });
            }
            
            if (exportPdfBtn) {
                exportPdfBtn.addEventListener('click', function() {
                    // TODO: Implement PDF export
                    alert('PDF export functionality will be implemented soon');
                });
            }
        });
    </script>
@endsection

