<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - KITTI Investment Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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
                    <span class="text-gray-700">Welcome, {{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
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

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-rupee-sign text-blue-600 text-2xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Payments</dt>
                                <dd class="text-lg font-medium text-gray-900">₹{{ number_format($stats['total_amount'], 2) }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white shadow rounded-lg mb-8">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Quick Actions</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <a href="{{ route('admin.registrations.index') }}" 
                       class="flex items-center p-4 border border-gray-200 rounded-lg hover:border-indigo-300 hover:bg-indigo-50 transition-colors">
                        <i class="fas fa-list text-indigo-600 mr-3"></i>
                        <div>
                            <h4 class="font-medium text-gray-900">View Registrations</h4>
                            <p class="text-sm text-gray-500">Manage all registrations</p>
                        </div>
                    </a>
                    
                    <a href="{{ route('admin.payments.index') }}" 
                       class="flex items-center p-4 border border-gray-200 rounded-lg hover:border-indigo-300 hover:bg-indigo-50 transition-colors">
                        <i class="fas fa-credit-card text-indigo-600 mr-3"></i>
                        <div>
                            <h4 class="font-medium text-gray-900">Payment History</h4>
                            <p class="text-sm text-gray-500">View payment transactions</p>
                        </div>
                    </a>
                    
                    <a href="{{ route('admin.reports') }}" 
                       class="flex items-center p-4 border border-gray-200 rounded-lg hover:border-indigo-300 hover:bg-indigo-50 transition-colors">
                        <i class="fas fa-chart-bar text-indigo-600 mr-3"></i>
                        <div>
                            <h4 class="font-medium text-gray-900">Reports</h4>
                            <p class="text-sm text-gray-500">Generate reports</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Recent Registrations -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Recent Registrations</h3>
                    <div class="space-y-4">
                        @forelse($recentRegistrations as $registration)
                            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                                <div>
                                    <h4 class="font-medium text-gray-900">{{ $registration->full_name }}</h4>
                                    <p class="text-sm text-gray-500">{{ $registration->email }}</p>
                                    <p class="text-xs text-gray-400">₹{{ number_format($registration->plan_amount) }} • {{ $registration->status }}</p>
                                </div>
                                <div class="text-right">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        @if($registration->status === 'approved') bg-green-100 text-green-800
                                        @elseif($registration->status === 'pending') bg-yellow-100 text-yellow-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst($registration->status) }}
                                    </span>
                                    <p class="text-xs text-gray-400 mt-1">{{ $registration->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 text-center py-4">No recent registrations</p>
                        @endforelse
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('admin.registrations.index') }}" class="text-indigo-600 hover:text-indigo-500 text-sm font-medium">
                            View all registrations →
                        </a>
                    </div>
                </div>
            </div>

            <!-- Recent Payments -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Recent Payments</h3>
                    <div class="space-y-4">
                        @forelse($recentPayments as $payment)
                            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                                <div>
                                    <h4 class="font-medium text-gray-900">{{ $payment->registration->full_name }}</h4>
                                    <p class="text-sm text-gray-500">{{ $payment->getPaymentMethodDisplay() }}</p>
                                    <p class="text-xs text-gray-400">{{ $payment->transaction_reference }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-medium text-gray-900">₹{{ number_format($payment->amount, 2) }}</p>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        @if($payment->status === 'success') bg-green-100 text-green-800
                                        @elseif($payment->status === 'pending') bg-yellow-100 text-yellow-800
                                        @else bg-red-100 text-red-800 @endif">
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                    <p class="text-xs text-gray-400 mt-1">{{ $payment->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 text-center py-4">No recent payments</p>
                        @endforelse
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('admin.payments.index') }}" class="text-indigo-600 hover:text-indigo-500 text-sm font-medium">
                            View all payments →
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>





