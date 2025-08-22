<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - KITTI Investment Platform</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/golden.css') }}">
</head>

<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <a href="{{ route('user.dashboard') }}">
                            <img src="{{ asset('logo.png') }}" alt="Kitti" class="h-10 w-auto">
                        </a>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-gray-700">Welcome, {{ auth()->user()->name }}</span>
                    <a href="{{ route('user.profile') }}" class="text-golden-600 hover:text-golden-700 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-user-circle mr-2"></i>Profile
                    </a>
                    <a href="{{ route('user.payment-history') }}" class="text-golden-600 hover:text-golden-700 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-history mr-2"></i>Payment History
                    </a>
                    <form method="POST" action="{{ route('auth.logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-red-600 hover:text-red-700 px-3 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-sign-out-alt mr-2"></i>Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-12">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="text-center text-gray-500 text-sm">
                <p>&copy; {{ date('Y') }} KITTI. All rights reserved.</p>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>

</html>