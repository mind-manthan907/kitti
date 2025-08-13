<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Investment Plan - KITTI</title>
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
                    <span class="text-gray-700">Welcome, {{ $user->name }}</span>
                    <a href="{{ route('user.dashboard') }}" class="text-indigo-600 hover:text-indigo-500 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
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

    <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Create Investment Plan</h1>
            <p class="mt-2 text-gray-600">Fill in your investment details to start your KITTI investment journey</p>
        </div>

        <!-- Investment Plan Form -->
        <div class="bg-white shadow-lg rounded-lg p-8">
            <form id="investmentPlanForm" enctype="multipart/form-data">
                <!-- Investment Details -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Investment Details</h3>
                    
                    <!-- How It Works Explanation -->
                    <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <h4 class="font-medium text-blue-900 mb-2">💡 How Monthly Contribution Works</h4>
                        <div class="text-sm text-blue-800 space-y-1">
                            <p>• <strong>Total Target:</strong> The final amount you want to achieve (e.g., ₹12,000)</p>
                            <p>• <strong>Monthly Due:</strong> Fixed amount you pay every month (e.g., ₹1,000)</p>
                            <p>• <strong>Payment Period:</strong> Pay for 10 months, get 12 months benefit</p>
                            <p>• <strong>Maturity:</strong> You get the total target amount at maturity</p>
                            <p class="text-xs mt-2 text-blue-600">Example: Pay ₹1,000/month for 10 months = ₹10,000 contributed. Get ₹12,000 after 12 months (2 months free benefit).</p>
                        </div>
                    </div>
                    
                    <!-- Plan Selection -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            Select Investment Plan <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            @foreach($plans as $plan)
                            <div class="plan-option border-2 border-gray-200 rounded-lg p-4 cursor-pointer hover:border-indigo-300 transition-colors"
                                 data-plan-id="{{ $plan->id }}" 
                                 data-amount="{{ $plan->amount }}"
                                 data-duration="{{ $plan->duration_months }}"
                                 data-interest="{{ $plan->interest_rate }}"
                                 data-monthly-due="{{ $plan->monthly_due }}">
                                <div class="text-center">
                                    <h4 class="font-semibold text-gray-900 mb-2">{{ $plan->name }}</h4>
                                    <div class="text-2xl font-bold text-indigo-600 mb-1">{{ $plan->formatted_amount }}</div>
                                    <div class="text-sm text-gray-600 mb-2">{{ $plan->formatted_duration }}</div>
                                    <div class="text-sm text-blue-600 font-medium mb-2">Monthly: {{ $plan->formatted_monthly_due }}</div>
                                    <div class="text-xs text-gray-500 mt-2">{{ $plan->description }}</div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <input type="hidden" id="selected_plan_id" name="plan_id" required>
                        <div id="plan-error" class="text-red-500 text-sm mt-2" style="display: none;">Please select an investment plan</div>
                    </div>

                    <!-- Duration Selection -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="duration_months" class="block text-sm font-medium text-gray-700 mb-2">
                                Investment Duration (Reference) <span class="text-gray-400 text-xs">(Payment: 10 months)</span>
                            </label>
                            <select id="duration_months" name="duration_months" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                <option value="">Select duration</option>
                                <option value="12">12 months (1 year)</option>
                                <option value="24">24 months (2 years)</option>
                                <option value="36">36 months (3 years)</option>
                                <option value="48">48 months (4 years)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Computed Maturity Date
                            </label>
                            <div id="maturity-date-display" class="px-3 py-2 bg-gray-100 border border-gray-300 rounded-md text-gray-700">
                                Select plan and duration to see maturity date
                            </div>
                        </div>
                    </div>

                    <!-- Plan Summary (shown after selection) -->
                    <div id="plan-summary" class="mt-6 p-4 bg-indigo-50 border border-indigo-200 rounded-lg" style="display: none;">
                        <h4 class="font-medium text-indigo-900 mb-3">Plan Summary</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-gray-600">Selected Plan:</span>
                                <span id="summary-plan-name" class="font-medium text-gray-900 ml-2"></span>
                            </div>
                            <div>
                                <span class="text-gray-600">Total Target:</span>
                                <span id="summary-amount" class="font-medium text-gray-900 ml-2"></span>
                            </div>
                            <div>
                                <span class="text-gray-600">Monthly Due:</span>
                                <span id="summary-monthly-due" class="font-medium text-blue-600 ml-2"></span>
                            </div>
                            <div>
                                <span class="text-gray-600">Payment Period:</span>
                                <span id="summary-payment-period" class="font-medium text-orange-600 ml-2"></span>
                            </div>
                            <div>
                                <span class="text-gray-600">Total Duration:</span>
                                <span id="summary-duration" class="font-medium text-gray-900 ml-2"></span>
                            </div>
                            <div>
                                <span class="text-gray-600">Maturity Date:</span>
                                <span id="summary-maturity" class="font-medium text-gray-900 ml-2"></span>
                            </div>
                            <div>
                                <span class="text-gray-600">Amount to Pay:</span>
                                <span id="summary-total-pay" class="font-medium text-orange-600 ml-2"></span>
                            </div>
                            <div>
                                <span class="text-gray-600">Free Benefit:</span>
                                <span id="summary-free-benefit" class="font-medium text-green-600 ml-2"></span>
                            </div>
                        </div>
                        
                        <!-- Payment Schedule Preview -->
                        <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded">
                            <h5 class="font-medium text-blue-900 mb-2">Payment Schedule Preview</h5>
                            <p class="text-xs text-blue-700">
                                You will pay <span id="preview-monthly-amount" class="font-medium"></span> every month for <span id="preview-payment-months" class="font-medium"></span> months. 
                                Total payment: <span id="preview-total-payment" class="font-medium"></span>
                            </p>
                            <p class="text-xs text-green-700 mt-1">
                                <strong>Free Benefit:</strong> Last <span id="preview-free-months" class="font-medium"></span> months - no payment required!
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Terms and Submit -->
                <div class="border-t pt-6">
                    <div class="flex items-center mb-6">
                        <input type="checkbox" id="terms_accepted" name="terms_accepted" required
                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label for="terms_accepted" class="ml-2 block text-sm text-gray-900">
                            I agree to the <a href="#" class="text-indigo-600 hover:text-indigo-500">Terms and Conditions</a>
                            and <a href="#" class="text-indigo-600 hover:text-indigo-500">Privacy Policy</a>
                        </label>
                    </div>
                    
                    <div class="flex justify-between">
                        <a href="{{ route('user.dashboard') }}" 
                           class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-md text-sm font-medium">
                            <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
                        </a>
                        <button type="submit" id="submitBtn"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-md text-sm font-medium">
                            <i class="fas fa-paper-plane mr-2"></i>Create Investment Plan
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Success/Error Messages -->
        <div id="messageContainer" class="mt-6" style="display: none;">
            <div id="messageContent" class="p-4 rounded-md"></div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('investmentPlanForm');
            const submitBtn = document.getElementById('submitBtn');
            const messageContainer = document.getElementById('messageContainer');
            const messageContent = document.getElementById('messageContent');
            const planOptions = document.querySelectorAll('.plan-option');
            const selectedPlanInput = document.getElementById('selected_plan_id');
            const durationSelect = document.getElementById('duration_months');
            const maturityDisplay = document.getElementById('maturity-date-display');
            const planSummary = document.getElementById('plan-summary');
            const planError = document.getElementById('plan-error');

            let selectedPlan = null;

            // Plan selection
            planOptions.forEach(option => {
                option.addEventListener('click', function() {
                    // Remove active class from all options
                    planOptions.forEach(opt => {
                        opt.classList.remove('border-indigo-500', 'bg-indigo-50');
                        opt.classList.add('border-gray-200');
                    });
                    
                    // Add active class to selected option
                    this.classList.remove('border-gray-200');
                    this.classList.add('border-indigo-500', 'bg-indigo-50');
                    
                    // Store selected plan data
                    selectedPlan = {
                        id: this.dataset.planId,
                        name: this.querySelector('h4').textContent,
                        amount: parseFloat(this.dataset.amount),
                        duration: parseInt(this.dataset.duration),
                        interest: parseFloat(this.dataset.interest),
                        monthlyDue: parseFloat(this.dataset.monthlyDue) // Add monthly due to selectedPlan
                    };
                    
                    // Update hidden input
                    selectedPlanInput.value = selectedPlan.id;
                    
                    // Hide error
                    planError.style.display = 'none';
                    
                    // Update maturity date if duration is selected
                    if (durationSelect.value) {
                        updateMaturityDate();
                    }
                    
                    // Show plan summary
                    updatePlanSummary();
                });
            });

            // Duration change
            durationSelect.addEventListener('change', function() {
                if (selectedPlan) {
                    updateMaturityDate();
                    updatePlanSummary();
                }
            });

            // Calculate and display maturity date
            function updateMaturityDate() {
                if (!selectedPlan || !durationSelect.value) return;
                
                // Business Logic: Always get 12 months benefit regardless of selected duration
                const totalDuration = 12;
                const startDate = new Date();
                const maturityDate = new Date(startDate.getFullYear(), startDate.getMonth() + totalDuration, startDate.getDate());
                
                const options = { 
                    year: 'numeric', 
                    month: 'long', 
                    day: 'numeric' 
                };
                
                maturityDisplay.textContent = maturityDate.toLocaleDateString('en-US', options);
                maturityDisplay.classList.remove('bg-gray-100', 'text-gray-700');
                maturityDisplay.classList.add('bg-green-100', 'text-green-800');
            }

            // Update plan summary
            function updatePlanSummary() {
                if (!selectedPlan || !durationSelect.value) return;
                
                const duration = parseInt(durationSelect.value);
                const startDate = new Date();
                
                // Business Logic: Pay for 10 months, get 12 months benefit
                const paymentPeriod = 10; // Always pay for 10 months
                const totalDuration = 12; // Always get 12 months benefit
                const maturityDate = new Date(startDate.getFullYear(), startDate.getMonth() + totalDuration, startDate.getDate());
                
                // Calculate actual amount to pay (10 months × monthly due)
                const actualAmountToPay = selectedPlan.monthlyDue * paymentPeriod;
                
                // Calculate free benefit (2 months worth)
                const freeBenefit = selectedPlan.monthlyDue * 2;
                
                // Update summary fields
                document.getElementById('summary-plan-name').textContent = selectedPlan.name;
                document.getElementById('summary-amount').textContent = '₹' + selectedPlan.amount.toLocaleString();
                document.getElementById('summary-monthly-due').textContent = '₹' + selectedPlan.monthlyDue.toLocaleString();
                document.getElementById('summary-payment-period').textContent = paymentPeriod + ' months';
                document.getElementById('summary-duration').textContent = totalDuration + ' months';
                document.getElementById('summary-maturity').textContent = maturityDate.toLocaleDateString('en-US', { 
                    year: 'numeric', 
                    month: 'long', 
                    day: 'numeric' 
                });
                document.getElementById('summary-total-pay').textContent = '₹' + actualAmountToPay.toLocaleString();
                document.getElementById('summary-free-benefit').textContent = '₹' + freeBenefit.toLocaleString() + ' (2 months)';
                
                // Update payment schedule preview
                document.getElementById('preview-monthly-amount').textContent = '₹' + selectedPlan.monthlyDue.toLocaleString();
                document.getElementById('preview-payment-months').textContent = paymentPeriod;
                document.getElementById('preview-total-payment').textContent = '₹' + actualAmountToPay.toLocaleString();
                document.getElementById('preview-free-months').textContent = '2';
                
                // Show summary
                planSummary.style.display = 'block';
            }

            // Form validation
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Validate plan selection
                if (!selectedPlan) {
                    planError.style.display = 'block';
                    planError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    return;
                }
                
                // Validate duration
                if (!durationSelect.value) {
                    alert('Please select investment duration');
                    durationSelect.focus();
                    return;
                }
                
                // Show loading state
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Creating...';
                
                // Hide any existing messages
                messageContainer.style.display = 'none';
                
                // Create FormData object
                const formData = new FormData(form);
                
                // Get CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                fetch('{{ route("registration.investment-plan.store") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showMessage(data.message, 'success');
                        // Redirect after 2 seconds
                        setTimeout(() => {
                            window.location.href = data.redirect_url;
                        }, 2000);
                    } else {
                        showMessage(data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showMessage('An error occurred while creating the investment plan. Please try again.', 'error');
                })
                .finally(() => {
                    // Reset button state
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-paper-plane mr-2"></i>Create Investment Plan';
                });
            });

            function showMessage(message, type) {
                messageContent.textContent = message;
                messageContent.className = `p-4 rounded-md ${
                    type === 'success' 
                        ? 'bg-green-100 text-green-800 border border-green-200' 
                        : 'bg-red-100 text-red-800 border border-red-200'
                }`;
                messageContainer.style.display = 'block';
                
                // Scroll to message
                messageContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        });
    </script>
</body>
</html>
