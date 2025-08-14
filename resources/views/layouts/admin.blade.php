<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KITTI Admin - @yield('title', 'Dashboard')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-indigo-600 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <a href="{{ route('admin.dashboard') }}" class="text-xl font-bold text-white">KITTI Admin</a>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    @auth
                        <span class="text-white">Welcome, {{ Auth::user()->name }}</span>
                        <a href="{{ route('admin.dashboard') }}" class="text-indigo-100 hover:text-white px-3 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                        </a>
                        <form method="POST" action="{{ route('auth.logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-indigo-100 hover:text-white px-3 py-2 rounded-md text-sm font-medium">
                                <i class="fas fa-sign-out-alt mr-2"></i>Logout
                            </button>
                        </form>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Sidebar and Main Content -->
    <div class="flex">
        <!-- Sidebar -->
        <div class="w-64 bg-white shadow-lg min-h-screen">
            <div class="p-4">
                <nav class="space-y-2">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center px-4 py-2 text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 rounded-md {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-50 text-indigo-700' : '' }}">
                        <i class="fas fa-tachometer-alt mr-3"></i>
                        Dashboard
                    </a>
                    
                    <a href="{{ route('admin.users.index') }}" 
                       class="flex items-center px-4 py-2 text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 rounded-md {{ request()->routeIs('admin.users.*') ? 'bg-indigo-50 text-indigo-700' : '' }}">
                        <i class="fas fa-users mr-3"></i>
                        Users
                    </a>
                    
                    <a href="{{ route('admin.kyc.index') }}" 
                       class="flex items-center px-4 py-2 text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 rounded-md {{ request()->routeIs('admin.kyc.*') ? 'bg-indigo-50 text-indigo-700' : '' }}">
                        <i class="fas fa-id-card mr-3"></i>
                        KYC Documents
                    </a>
                    
                    <a href="{{ route('admin.registrations.index') }}" 
                       class="flex items-center px-4 py-2 text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 rounded-md {{ request()->routeIs('admin.registrations.*') ? 'bg-indigo-50 text-indigo-700' : '' }}">
                        <i class="fas fa-clipboard-list mr-3"></i>
                        Registrations
                    </a>
                    
                    <a href="{{ route('admin.investment-plans.index') }}" 
                       class="flex items-center px-4 py-2 text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 rounded-md {{ request()->routeIs('admin.investment-plans.*') ? 'bg-indigo-50 text-indigo-700' : '' }}">
                        <i class="fas fa-chart-line mr-3"></i>
                        Investment Plans
                    </a>
                    
                    <a href="{{ route('admin.payments.index') }}" 
                       class="flex items-center px-4 py-2 text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 rounded-md {{ request()->routeIs('admin.payments.*') ? 'bg-indigo-50 text-indigo-700' : '' }}">
                        <i class="fas fa-credit-card mr-3"></i>
                        Payments
                    </a>
                    
                    <a href="{{ route('admin.monthly-dues') }}" 
                       class="flex items-center px-4 py-2 text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 rounded-md {{ request()->routeIs('admin.monthly-dues*') ? 'bg-indigo-50 text-indigo-700' : '' }}">
                        <i class="fas fa-calendar-alt mr-3"></i>
                        Monthly Dues
                    </a>
                    
                    <a href="{{ route('admin.discontinue-requests.index') }}" 
                       class="flex items-center px-4 py-2 text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 rounded-md {{ request()->routeIs('admin.discontinue-requests.*') ? 'bg-indigo-50 text-indigo-700' : '' }}">
                        <i class="fas fa-stop-circle mr-3"></i>
                        Discontinue Requests
                    </a>
                    
                    <a href="{{ route('admin.reports') }}" 
                       class="flex items-center px-4 py-2 text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 rounded-md {{ request()->routeIs('admin.reports') ? 'bg-indigo-50 text-indigo-700' : '' }}">
                        <i class="fas fa-chart-bar mr-3"></i>
                        Reports
                    </a>
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1">
            <main>
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-12">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="text-center text-gray-500 text-sm">
                <p>&copy; {{ date('Y') }} KITTI Admin Panel. All rights reserved.</p>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
