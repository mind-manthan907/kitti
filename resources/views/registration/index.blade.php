<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KITTI Registration - Investment Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div>
                <div class="mx-auto h-12 w-12 flex items-center justify-center rounded-full bg-indigo-100">
                    <i class="fas fa-chart-line text-indigo-600 text-xl"></i>
                </div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    Welcome to KITTI
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Start your investment journey with our secure platform
                </p>
            </div>

            <div class="bg-white shadow-lg rounded-lg p-6">
                <div class="text-center">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Choose Your Investment Plan</h3>
                    
                    <div class="space-y-4">
                        <div class="border border-gray-200 rounded-lg p-4 hover:border-indigo-300 transition-colors">
                            <h4 class="font-semibold text-gray-900">₹1,000 - Basic Plan</h4>
                            <p class="text-sm text-gray-600">Pay for 10 months, get 12 months benefit</p>
                        </div>
                        
                        <div class="border border-gray-200 rounded-lg p-4 hover:border-indigo-300 transition-colors">
                            <h4 class="font-semibold text-gray-900">₹10,000 - Standard Plan</h4>
                            <p class="text-sm text-gray-600">Pay for 10 months, get 12 months benefit</p>
                        </div>
                        
                        <div class="border border-gray-200 rounded-lg p-4 hover:border-indigo-300 transition-colors">
                            <h4 class="font-semibold text-gray-900">₹50,000 - Premium Plan</h4>
                            <p class="text-sm text-gray-600">Pay for 10 months, get 12 months benefit</p>
                        </div>
                        
                        <div class="border border-gray-200 rounded-lg p-4 hover:border-indigo-300 transition-colors">
                            <h4 class="font-semibold text-gray-900">₹1,00,000 - Ultimate Plan</h4>
                            <p class="text-sm text-gray-600">Pay for 10 months, get 12 months benefit</p>
                        </div>
                    </div>

                    <div class="mt-6">
                        <a href="{{ route('registration.create') }}" 
                           class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Start Registration
                        </a>
                    </div>
                </div>
            </div>

            <div class="text-center">
                <p class="text-sm text-gray-600">
                    Already have an account? 
                    <a href="{{ route('auth.login') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
                        Sign in
                    </a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>




