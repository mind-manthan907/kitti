<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Security Settings - KITTI Investment Platform</title>
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
                    <a href="{{ route('user.dashboard') }}" class="text-indigo-600 hover:text-indigo-500 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-home mr-2"></i>Dashboard
                    </a>
                    <a href="{{ route('user.profile') }}" class="text-indigo-600 hover:text-indigo-500 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-user-circle mr-2"></i>Profile
                    </a>
                    <form method="POST" action="{{ route('auth.logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-red-600 hover:text-red-500 px-3 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-sign-out-alt mr-2"></i>Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="px-4 py-6 sm:px-0">
            <h1 class="text-3xl font-bold text-gray-900">Security Settings</h1>
            <p class="mt-2 text-gray-600">Manage your account security and authentication</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 px-4 sm:px-0">
            <!-- Two-Factor Authentication -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                        <i class="fas fa-shield-alt mr-2 text-indigo-600"></i>
                        Two-Factor Authentication
                    </h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600">Add an extra layer of security to your account</p>
                                <p class="text-xs text-gray-500 mt-1">Currently: 
                                    <span class="font-medium {{ $user->two_factor_enabled ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $user->two_factor_enabled ? 'Enabled' : 'Disabled' }}
                                    </span>
                                </p>
                            </div>
                            <button id="toggle2fa" 
                                    class="px-4 py-2 rounded-md text-sm font-medium transition-colors
                                    {{ $user->two_factor_enabled 
                                        ? 'bg-red-100 text-red-700 hover:bg-red-200' 
                                        : 'bg-green-100 text-green-700 hover:bg-green-200' }}">
                                {{ $user->two_factor_enabled ? 'Disable' : 'Enable' }} 2FA
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Password Reset -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                        <i class="fas fa-key mr-2 text-indigo-600"></i>
                        Password Reset
                    </h3>
                    <div class="space-y-4">
                        <p class="text-sm text-gray-600">Reset your password using email OTP verification</p>
                        
                        <form id="passwordResetForm" class="space-y-4">
                            <div>
                                <label for="resetEmail" class="block text-sm font-medium text-gray-700">Email Address</label>
                                <input type="email" id="resetEmail" name="email" value="{{ $user->email }}" readonly
                                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-50 text-gray-500">
                            </div>
                            
                            <button type="submit" id="sendOtpBtn"
                                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <i class="fas fa-paper-plane mr-2"></i>
                                Send Reset OTP
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle 2FA
            document.getElementById('toggle2fa').addEventListener('click', function() {
                const isEnabled = {{ $user->two_factor_enabled ? 'true' : 'false' }};
                const newStatus = !isEnabled;
                
                fetch('{{ route("user.toggle-2fa") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        two_factor_enabled: newStatus
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while updating 2FA settings');
                });
            });
        });
    </script>
</body>
</html>
