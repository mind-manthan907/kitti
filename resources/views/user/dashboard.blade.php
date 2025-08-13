<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - KITTI Investment Platform</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <h1 class="text-xl font-bold text-indigo-600">KITTI</h1>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-gray-700">Welcome, {{ auth()->user()->name }}</span>
                    <a href="{{ route('user.profile') }}" class="text-indigo-600 hover:text-indigo-500">
                        <i class="fas fa-user-circle text-xl"></i>
                    </a>
                    <form method="POST" action="{{ route('auth.logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-red-600 hover:text-red-500">
                            <i class="fas fa-sign-out-alt text-xl"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="px-4 py-6 sm:px-0">
            <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
            <p class="mt-2 text-gray-600">Manage your investments and account</p>
        </div>

        <!-- Dashboard Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 px-4 sm:px-0">
            <!-- Investment Status Card -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-chart-line text-2xl text-indigo-600"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Investment Status</dt>
                                <dd class="text-lg font-medium text-gray-900">
                                    @if($activeRegistration)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            No Active Investment
                                        </span>
                                    @endif
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
                @if(!$activeRegistration)
                <div class="bg-gray-50 px-5 py-3">
                    <div class="text-sm">
                        <a href="{{ route('registration.create') }}" class="font-medium text-indigo-700 hover:text-indigo-500">
                            Join Investment Plan
                        </a>
                    </div>
                </div>
                @endif
            </div>

            <!-- Total Investment Card -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-rupee-sign text-2xl text-green-600"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Investment</dt>
                                <dd class="text-lg font-medium text-gray-900">
                                    ₹{{ number_format($totalInvestment) }}
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Next Payment Card -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-calendar-alt text-2xl text-blue-600"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Next Payment</dt>
                                <dd class="text-lg font-medium text-gray-900">
                                    @if($nextPaymentDate)
                                        {{ \Carbon\Carbon::parse($nextPaymentDate)->format('M d, Y') }}
                                    @else
                                        N/A
                                    @endif
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Investment Details -->
        @if($activeRegistration)
        <div class="mt-8 px-4 sm:px-0">
            <div class="bg-white shadow overflow-hidden sm:rounded-md">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Active Investment Details</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">Your current investment plan information</p>
                </div>
                <div class="border-t border-gray-200">
                    <dl>
                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Plan Amount</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">₹{{ number_format($activeRegistration->plan_amount) }}</dd>
                        </div>
                        <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Duration</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $activeRegistration->duration_months }} months</dd>
                        </div>
                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Start Date</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ \Carbon\Carbon::parse($activeRegistration->start_date)->format('M d, Y') }}</dd>
                        </div>
                        <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Maturity Date</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ \Carbon\Carbon::parse($activeRegistration->maturity_date)->format('M d, Y') }}</dd>
                        </div>
                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    @if($activeRegistration->status === 'approved') bg-green-100 text-green-800
                                    @elseif($activeRegistration->status === 'pending') bg-yellow-100 text-yellow-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($activeRegistration->status) }}
                                </span>
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
        @endif

        <!-- Quick Actions -->
        <div class="mt-8 px-4 sm:px-0">
            <div class="bg-white shadow overflow-hidden sm:rounded-md">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Quick Actions</h3>
                </div>
                <div class="border-t border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 p-4">
                        <a href="{{ route('user.payment-history') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                            <i class="fas fa-history text-2xl text-blue-600 mr-3"></i>
                            <div>
                                <h4 class="font-medium text-gray-900">Payment History</h4>
                                <p class="text-sm text-gray-500">View your payment records</p>
                            </div>
                        </a>
                        
                        <a href="{{ route('user.discontinue-requests') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                            <i class="fas fa-stop-circle text-2xl text-red-600 mr-3"></i>
                            <div>
                                <h4 class="font-medium text-gray-900">Discontinue Requests</h4>
                                <p class="text-sm text-gray-500">Manage your requests</p>
                            </div>
                        </a>
                        
                        <a href="{{ route('user.security') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                            <i class="fas fa-shield-alt text-2xl text-green-600 mr-3"></i>
                            <div>
                                <h4 class="font-medium text-gray-900">Security Settings</h4>
                                <p class="text-sm text-gray-500">2FA and password</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
