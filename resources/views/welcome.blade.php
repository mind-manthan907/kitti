<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KITTI Investment Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <h1 class="text-2xl font-bold text-indigo-600">KITTI</h1>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('auth.login') }}" class="text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium">Login</a>
                    <a href="{{ route('registration.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium">Register Now</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative overflow-hidden">
        <div class="max-w-7xl mx-auto">
            <div class="relative z-10 pb-8 bg-gradient-to-br from-blue-50 to-indigo-100 sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32">
                <main class="mt-10 mx-auto max-w-7xl px-4 sm:mt-12 sm:px-6 md:mt-16 lg:mt-20 lg:px-8 xl:mt-28">
                    <div class="sm:text-center lg:text-left">
                        <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 sm:text-5xl md:text-6xl">
                            <span class="block xl:inline">Smart Investment</span>
                            <span class="block text-indigo-600 xl:inline">Made Simple</span>
                        </h1>
                        <p class="mt-3 text-base text-gray-500 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0">
                            Join thousands of investors who trust KITTI for their financial growth. 
                            Invest for 10 months and get benefits for 12 months with attractive returns.
                        </p>
                        <div class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start">
                            <div class="rounded-md shadow">
                                <a href="{{ route('registration.create') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 md:py-4 md:text-lg md:px-10">
                                    Start Investing Today
                                </a>
                            </div>
                            <div class="mt-3 sm:mt-0 sm:ml-3">
                                <a href="#features" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 md:py-4 md:text-lg md:px-10">
                                    Learn More
                                </a>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div id="features" class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="lg:text-center">
                <h2 class="text-base text-indigo-600 font-semibold tracking-wide uppercase">Features</h2>
                <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                    Why Choose KITTI?
                </p>
                <p class="mt-4 max-w-2xl text-xl text-gray-500 lg:mx-auto">
                    Our platform offers the best investment opportunities with secure, transparent, and profitable returns.
                </p>
            </div>

            <div class="mt-10">
                <div class="space-y-10 md:space-y-0 md:grid md:grid-cols-2 md:gap-x-8 md:gap-y-10">
                    <div class="relative">
                        <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
                            <i class="fas fa-chart-line text-xl"></i>
                        </div>
                        <p class="ml-16 text-lg leading-6 font-medium text-gray-900">High Returns</p>
                        <p class="mt-2 ml-16 text-base text-gray-500">
                            Earn attractive returns on your investments with our carefully managed investment plans.
                        </p>
                    </div>

                    <div class="relative">
                        <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
                            <i class="fas fa-shield-alt text-xl"></i>
                        </div>
                        <p class="ml-16 text-lg leading-6 font-medium text-gray-900">Secure Investment</p>
                        <p class="mt-2 ml-16 text-base text-gray-500">
                            Your investments are protected with industry-standard security measures and transparent processes.
                        </p>
                    </div>

                    <div class="relative">
                        <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
                            <i class="fas fa-mobile-alt text-xl"></i>
                        </div>
                        <p class="ml-16 text-lg leading-6 font-medium text-gray-900">Easy Management</p>
                        <p class="mt-2 ml-16 text-base text-gray-500">
                            Manage your investments easily through our user-friendly mobile and web platforms.
                        </p>
                    </div>

                    <div class="relative">
                        <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
                            <i class="fas fa-clock text-xl"></i>
                        </div>
                        <p class="ml-16 text-lg leading-6 font-medium text-gray-900">Flexible Duration</p>
                        <p class="mt-2 ml-16 text-base text-gray-500">
                            Choose from various investment durations that suit your financial goals and timeline.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Investment Plans -->
    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="lg:text-center">
                <h2 class="text-base text-indigo-600 font-semibold tracking-wide uppercase">Investment Plans</h2>
                <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                    Choose Your Investment Amount
                </p>
                <p class="mt-4 max-w-2xl text-xl text-gray-500 lg:mx-auto">
                    Start with as little as ₹1,000 and scale up to ₹1,00,000 based on your investment capacity.
                </p>
            </div>

            <div class="mt-10 grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-4">
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <div class="text-center">
                        <h3 class="text-2xl font-bold text-gray-900">₹1,000</h3>
                        <p class="text-gray-500">Basic Plan</p>
                        <div class="mt-4">
                            <span class="text-sm text-gray-500">Pay for 10 months</span><br>
                            <span class="text-sm text-gray-500">Get 12 months benefit</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-lg p-6">
                    <div class="text-center">
                        <h3 class="text-2xl font-bold text-gray-900">₹10,000</h3>
                        <p class="text-gray-500">Standard Plan</p>
                        <div class="mt-4">
                            <span class="text-sm text-gray-500">Pay for 10 months</span><br>
                            <span class="text-sm text-gray-500">Get 12 months benefit</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-lg p-6">
                    <div class="text-center">
                        <h3 class="text-2xl font-bold text-gray-900">₹50,000</h3>
                        <p class="text-gray-500">Premium Plan</p>
                        <div class="mt-4">
                            <span class="text-sm text-gray-500">Pay for 10 months</span><br>
                            <span class="text-sm text-gray-500">Get 12 months benefit</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-lg p-6">
                    <div class="text-center">
                        <h3 class="text-2xl font-bold text-gray-900">₹1,00,000</h3>
                        <p class="text-gray-500">Ultimate Plan</p>
                        <div class="mt-4">
                            <span class="text-sm text-gray-500">Pay for 10 months</span><br>
                            <span class="text-sm text-gray-500">Get 12 months benefit</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="bg-indigo-700">
        <div class="max-w-2xl mx-auto text-center py-16 px-4 sm:py-20 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-extrabold text-white sm:text-4xl">
                <span class="block">Ready to start investing?</span>
                <span class="block">Join KITTI today.</span>
            </h2>
            <p class="mt-4 text-lg leading-6 text-indigo-200">
                Start your investment journey with KITTI and secure your financial future.
            </p>
            <a href="{{ route('registration.create') }}" class="mt-8 w-full inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-indigo-600 bg-white hover:bg-indigo-50 sm:w-auto">
                Get Started
            </a>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-white text-lg font-semibold">KITTI</h3>
                    <p class="text-gray-300 mt-2">Smart investment platform for secure financial growth.</p>
                </div>
                <div>
                    <h4 class="text-white text-sm font-semibold uppercase tracking-wider">Company</h4>
                    <ul class="mt-4 space-y-2">
                        <li><a href="#" class="text-gray-300 hover:text-white">About</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white">Contact</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white">Careers</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white text-sm font-semibold uppercase tracking-wider">Support</h4>
                    <ul class="mt-4 space-y-2">
                        <li><a href="#" class="text-gray-300 hover:text-white">Help Center</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white">Privacy Policy</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white">Terms of Service</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white text-sm font-semibold uppercase tracking-wider">Connect</h4>
                    <div class="mt-4 flex space-x-6">
                        <a href="#" class="text-gray-300 hover:text-white">
                            <i class="fab fa-facebook text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-300 hover:text-white">
                            <i class="fab fa-twitter text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-300 hover:text-white">
                            <i class="fab fa-linkedin text-xl"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="mt-8 border-t border-gray-700 pt-8">
                <p class="text-gray-300 text-center">&copy; 2024 KITTI Investment Platform. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>
