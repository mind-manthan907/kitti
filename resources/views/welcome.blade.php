<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $company_name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/golden.css') }}">
</head>

<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <img src="{{ asset('logo.png') }}" alt="{{ $company_name }}" class="h-10 w-auto">
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('auth.login') }}" class="text-gray-700 hover:text-golden-700 px-3 py-2 rounded-md text-sm font-medium">Login</a>
                    <a href="{{ route('registration.create') }}" class="bg-golden-500 hover:bg-golden-700 text-white px-4 py-2 rounded-md text-sm font-medium">Register Now</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative overflow-hidden bg-gradient-to-br from-blue-50 to-indigo-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="lg:grid lg:grid-cols-12 lg:gap-8 lg:items-center p-4">
                <!-- Text Content -->
                <div class="sm:text-center md:max-w-2xl md:mx-auto lg:col-span-6 lg:text-left">
                    <h1 class="text-4xl font-extrabold text-gray-900 sm:text-5xl md:text-6xl">
                        <span class="block xl:inline">Smart Investment</span>
                        <span class="block text-golden-500 xl:inline">Made Simple</span>
                    </h1>
                    <p class="mt-3 text-base text-gray-500 sm:mt-5 sm:text-lg md:mt-5 md:text-xl">
                        Join thousands of investors who trust {{ $company_name }} for their financial growth.
                        Invest for 10 months and get benefits for 12 months with attractive returns.
                    </p>
                    <div class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start">
                        <a href="{{ route('registration.create') }}" class="px-8 py-3 rounded-md text-white bg-golden-500 hover:bg-golden-700">
                            Start Investing Today
                        </a>
                        <a href="#features" class="ml-3 px-8 py-3 rounded-md text-golden-900 bg-golden-100 hover:bg-golden-700">
                            Learn More
                        </a>
                    </div>
                </div>

                <!-- Video Content -->
                <div class="mt-10 relative lg:mt-0 lg:col-span-6">
                    <video controls autoplay muted loop class="w-full rounded-2xl shadow-xl">
                        <source src="{{ asset('video.mp4') }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
            </div>
        </div>
    </div>


    <!-- Features Section -->
    <div id="features" class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="lg:text-center">
                <h2 class="text-base text-golden-500 font-semibold tracking-wide uppercase">Features</h2>
                <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                    Why Choose {{ $company_name }}?
                </p>
                <p class="mt-4 max-w-2xl text-xl text-gray-500 lg:mx-auto">
                    Our platform offers the best investment opportunities with secure, transparent, and profitable returns.
                </p>
            </div>

            <div class="mt-10">
                <div class="space-y-10 md:space-y-0 md:grid md:grid-cols-2 md:gap-x-8 md:gap-y-10">
                    <div class="relative">
                        <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-golden-500 text-white">
                            <i class="fas fa-chart-line text-xl"></i>
                        </div>
                        <p class="ml-16 text-lg leading-6 font-medium text-gray-900">High Returns</p>
                        <p class="mt-2 ml-16 text-base text-gray-500">
                            Earn attractive returns on your investments with our carefully managed investment plans.
                        </p>
                    </div>

                    <div class="relative">
                        <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-golden-500 text-white">
                            <i class="fas fa-shield-alt text-xl"></i>
                        </div>
                        <p class="ml-16 text-lg leading-6 font-medium text-gray-900">Secure Investment</p>
                        <p class="mt-2 ml-16 text-base text-gray-500">
                            Your investments are protected with industry-standard security measures and transparent processes.
                        </p>
                    </div>

                    <div class="relative">
                        <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-golden-500 text-white">
                            <i class="fas fa-mobile-alt text-xl"></i>
                        </div>
                        <p class="ml-16 text-lg leading-6 font-medium text-gray-900">Easy Management</p>
                        <p class="mt-2 ml-16 text-base text-gray-500">
                            Manage your investments easily through our user-friendly mobile and web platforms.
                        </p>
                    </div>

                    <div class="relative">
                        <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-golden-500 text-white">
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
                <h2 class="text-base text-golden-500 font-semibold tracking-wide uppercase">Investment Plans</h2>
                <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                    Choose Your Investment Amount
                </p>
                <p class="mt-4 max-w-2xl text-xl text-gray-500 lg:mx-auto">
                    Start with as little as ₹1,000 and scale up to ₹1,00,000 based on your investment capacity.
                </p>
            </div>

            <!-- Plan Grid -->
            <div class="mt-10 grid grid-cols-1 gap-8 sm:grid-cols-3 md:grid-cols-3 lg:grid-cols-5">
                @foreach($plans as $plan)
                <div class="bg-white rounded-lg shadow-lg p-6 cursor-pointer plan-card"
                    data-plan-id="{{ $plan->id }}"
                    data-name="{{ $plan->name }}"
                    data-amount="{{ $plan->formatted_amount }}"
                    data-duration="{{ $plan->formatted_duration }}"
                    data-monthly-due="{{ $plan->formatted_monthly_due }}"
                    data-description="{{ $plan->description }}">
                    <div class="text-center">
                        <h3 class="text-2xl font-bold text-gray-900">₹ {{ $plan->amount }}</h3>
                        <p class="text-gray-500">{{ $plan->name }}</p>
                        <div class="mt-4">
                            <span class="text-sm text-gray-500">Pay for {{ $plan->emi_months }} months</span><br>
                            <span class="text-sm text-gray-500">Get {{ $plan->formatted_duration }} benefit</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Modal -->
            <div id="approveModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 flex items-center justify-center">
                <div class="bg-white rounded-md p-6 w-96 relative">
                    <!-- Close Button -->
                    <button type="button" onclick="closeApproveModal()"
                        class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-xl font-bold">&times;</button>

                    <h3 class="text-lg font-medium text-gray-900 mb-4" id="modalPlanName">Plan Detail(s)</h3>

                    <div class="space-y-3">
                        <!-- Plan Amount -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700">Plan Amount</label>
                            <div class="text-2xl font-bold text-indigo-600" id="modalPlanAmount">₹ 0</div>
                        </div>

                        <!-- Plan Duration -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700">Plan Duration</label>
                            <div class="text-sm text-gray-600" id="modalPlanDuration">0 months</div>
                        </div>

                        <!-- Total EMI Months -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700">Total EMI Months</label>
                            <div class="text-sm text-blue-600 font-medium" id="modalPlanMonthly">0</div>
                        </div>

                        <!-- Plan Description -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700">Plan Description</label>
                            <div class="text-xs text-gray-500 mt-1" id="modalPlanDescription">No description</div>
                        </div>
                    </div>

                    <div class="flex items-center justify-center space-x-3 mt-8">
                        <a href="{{ route('auth.login') }}" class="bg-golden-500 hover:bg-golden-700 text-white px-4 py-2 rounded-md text-sm font-medium">Login</a>
                        <a href="{{ route('registration.create') }}" class="bg-golden-500 hover:bg-golden-700 text-white px-4 py-2 rounded-md text-sm font-medium">Register Now</a>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- CTA Section -->
    <div class="bg-golden-500">
        <div class="max-w-2xl mx-auto text-center py-16 px-4 sm:py-20 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-extrabold text-white sm:text-4xl">
                <span class="block">Ready to start investing?</span>
                <span class="block">Join {{ $company_name }} today.</span>
            </h2>
            <p class="mt-4 text-lg leading-6 text-golden-900">
                Start your investment journey with {{ $company_name }} and secure your financial future.
            </p>
            <a href="{{ route('registration.create') }}" class="mt-8 w-full inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-golden-600 bg-white hover:bg-indigo-50 sm:w-auto">
                Get Started
            </a>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-white text-lg font-semibold">{{ $company_name }}</h3>
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
                <p class="text-gray-300 text-center">&copy; 2024 {{ $company_name }} . All rights reserved.</p>
            </div>
        </div>
    </footer>
    <script>
        const modal = document.getElementById('approveModal');
        const modalName = document.getElementById('modalPlanName');
        const modalAmount = document.getElementById('modalPlanAmount');
        const modalDuration = document.getElementById('modalPlanDuration');
        const modalMonthly = document.getElementById('modalPlanMonthly');
        const modalDescription = document.getElementById('modalPlanDescription');

        document.querySelectorAll('.plan-card').forEach(card => {
            card.addEventListener('click', () => {
                modalName.textContent = card.dataset.name;
                modalAmount.textContent = card.dataset.amount;
                modalDuration.textContent = card.dataset.duration;
                modalMonthly.textContent = "Monthly: " + card.dataset.monthlyDue;
                modalDescription.textContent = card.dataset.description;

                modal.classList.remove('hidden');
            });
        });

        function closeApproveModal() {
            modal.classList.add('hidden');
        }
    </script>
</body>

</html>