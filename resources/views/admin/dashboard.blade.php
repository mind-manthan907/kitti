<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - KITTI Investment Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <h1 class="text-2xl font-bold text-indigo-600">KITTI Admin</h1>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.dashboard') }}" class="text-indigo-600 hover:text-indigo-500 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="text-indigo-600 hover:text-indigo-500 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-users mr-2"></i>Users
                    </a>
                    <a href="{{ route('admin.kyc.index') }}" class="text-indigo-600 hover:text-indigo-500 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-id-card mr-2"></i>KYC Documents
                    </a>
                    <a href="{{ route('admin.registrations.index') }}" class="text-indigo-600 hover:text-indigo-500 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-user-plus mr-2"></i>Registrations
                    </a>
                    <a href="{{ route('admin.payments.index') }}" class="text-indigo-600 hover:text-indigo-500 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-credit-card mr-2"></i>Payments
                    </a>
                    <a href="{{ route('admin.discontinue-requests.index') }}" class="text-indigo-600 hover:text-indigo-500 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-stop-circle mr-2"></i>Discontinue
                    </a>
                    <a href="{{ route('admin.monthly-dues') }}" class="text-indigo-600 hover:text-indigo-500 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-calendar-alt mr-2"></i>Monthly Dues
                    </a>
                    <a href="{{ route('admin.reports') }}" class="text-indigo-600 hover:text-indigo-500 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-chart-bar mr-2"></i>Reports
                    </a>
                    <span class="text-gray-700">Welcome, {{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('auth.logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-users text-indigo-600 text-2xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Users</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $stats['total_users'] ?? 0 }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-user-plus text-green-600 text-2xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Registrations</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $stats['total_registrations'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-clock text-yellow-600 text-2xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Pending Registrations</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $stats['pending_registrations'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Approved Registrations</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $stats['approved_registrations'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Second Row Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-credit-card text-blue-600 text-2xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Payments</dt>
                                <dd class="text-lg font-medium text-gray-900">₹{{ number_format($stats['total_amount'] ?? 0, 2) }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Overdue Payments</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $stats['overdue_payments'] ?? 0 }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-stop-circle text-orange-600 text-2xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Discontinue Requests</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $stats['pending_discontinue_requests'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-user-slash text-gray-600 text-2xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Blocked Users</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $stats['blocked_users'] ?? 0 }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-id-card text-purple-600 text-2xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Pending KYC</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $stats['pending_kyc'] ?? 0 }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Monthly Registrations Chart -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Monthly Registrations</h3>
                <canvas id="monthlyRegistrationsChart" width="400" height="200"></canvas>
            </div>

            <!-- Payment Methods Chart -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Payment Methods Distribution</h3>
                <canvas id="paymentMethodsChart" width="400" height="200"></canvas>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Recent Registrations -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Recent Registrations</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Plan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($recentRegistrations as $registration)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $registration->full_name }}</div>
                                    <div class="text-sm text-gray-500">{{ $registration->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">₹{{ number_format($registration->plan_amount) }}</div>
                                    <div class="text-sm text-gray-500">{{ $registration->duration_months }} months</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $registration->status === 'approved' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ ucfirst($registration->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $registration->payment_status_badge_class }}">
                                        {{ ucfirst($registration->payment_status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $registration->created_at->format('M d, Y') }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">No recent registrations</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Recent Investment Plans (Payment Status) -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Recent Investment Plans - Payment Status</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Plan Details</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($recentRegistrations as $registration)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $registration->full_name }}</div>
                                    <div class="text-sm text-gray-500">{{ $registration->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">₹{{ number_format($registration->plan_amount) }}</div>
                                    <div class="text-sm text-gray-500">
                                        {{ $registration->duration_months }} months | 
                                        Maturity: {{ $registration->maturity_date->format('M d, Y') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $registration->payment_status_badge_class }}">
                                        {{ ucfirst($registration->payment_status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $registration->formatted_payment_date }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    @if($registration->payment_status === 'pending')
                                        <button class="text-green-600 hover:text-green-900 mr-3" 
                                                onclick="updatePaymentStatus({{ $registration->id }}, 'success')">
                                            Mark Paid
                                        </button>
                                        <button class="text-red-600 hover:text-red-900" 
                                                onclick="updatePaymentStatus({{ $registration->id }}, 'failed')">
                                            Mark Failed
                                        </button>
                                    @elseif($registration->payment_status === 'failed')
                                        <button class="text-green-600 hover:text-green-900" 
                                                onclick="updatePaymentStatus({{ $registration->id }}, 'success')">
                                            Mark Paid
                                        </button>
                                    @else
                                        <span class="text-green-600">Payment Complete</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">No investment plans found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Quick Actions</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Common administrative tasks</p>
            </div>
            <div class="border-t border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 p-6">
                    <a href="{{ route('admin.users.index') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                        <i class="fas fa-users text-2xl text-indigo-600 mr-3"></i>
                        <div>
                            <h4 class="font-medium text-gray-900">Manage Users</h4>
                            <p class="text-sm text-gray-500">Enable/disable users</p>
                        </div>
                    </a>
                    
                    <a href="{{ route('admin.monthly-dues') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                        <i class="fas fa-calendar-alt text-2xl text-blue-600 mr-3"></i>
                        <div>
                            <h4 class="font-medium text-gray-900">Monthly Dues</h4>
                            <p class="text-sm text-gray-500">Track overdue payments</p>
                        </div>
                    </a>
                    
                    <a href="{{ route('admin.system-config') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                        <i class="fas fa-cog text-2xl text-green-600 mr-3"></i>
                        <div>
                            <h4 class="font-medium text-gray-900">System Config</h4>
                            <p class="text-sm text-gray-500">Platform settings</p>
                        </div>
                    </a>

                    <a href="{{ route('admin.reports') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                        <i class="fas fa-chart-bar text-2xl text-purple-600 mr-3"></i>
                        <div>
                            <h4 class="font-medium text-gray-900">Reports</h4>
                            <p class="text-sm text-gray-500">Analytics & insights</p>
                        </div>
                    </a>

                    <a href="{{ route('admin.registrations.index') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                        <i class="fas fa-user-plus text-2xl text-blue-600 mr-3"></i>
                        <div>
                            <h4 class="font-medium text-gray-900">Manage Registrations</h4>
                            <p class="text-sm text-gray-500">Review and approve</p>
                        </div>
                    </a>
                    
                    <a href="{{ route('admin.investment-plans.index') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                        <i class="fas fa-chart-line text-2xl text-green-600 mr-3"></i>
                        <div>
                            <h4 class="font-medium text-gray-900">Investment Plans</h4>
                            <p class="text-sm text-gray-500">Create and manage</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Monthly Registrations Chart
        const monthlyCtx = document.getElementById('monthlyRegistrationsChart').getContext('2d');
        new Chart(monthlyCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Registrations',
                    data: [12, 19, 3, 5, 2, 3],
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Payment Methods Chart
        const paymentCtx = document.getElementById('paymentMethodsChart').getContext('2d');
        new Chart(paymentCtx, {
            type: 'doughnut',
            data: {
                labels: ['Gateway', 'UPI', 'QR'],
                datasets: [{
                    data: [65, 25, 10],
                    backgroundColor: [
                        'rgb(59, 130, 246)',
                        'rgb(16, 185, 129)',
                        'rgb(245, 158, 11)'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Payment Status Update Function
        function updatePaymentStatus(registrationId, status) {
            if (!confirm(`Are you sure you want to mark this payment as ${status}?`)) {
                return;
            }

            fetch(`/admin/registrations/${registrationId}/update-payment-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ payment_status: status })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error updating payment status: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error updating payment status. Please try again.');
            });
        }
    </script>
</body>
</html>





