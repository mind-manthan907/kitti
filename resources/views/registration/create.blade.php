<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KITTI Registration - Investment Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">
    <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <!-- Loading, Success, and Error Messages -->
            <div id="loading" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
                <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                    <div class="mt-3 text-center">
                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100">
                            <i class="fas fa-spinner fa-spin text-indigo-600 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mt-4">Processing...</h3>
                        <p class="text-sm text-gray-500 mt-2">Please wait while we process your request.</p>
                    </div>
                </div>
            </div>

            <div id="success-message" class="hidden fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded z-50">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span id="success-text"></span>
                </div>
            </div>

            <div id="error-message" class="hidden fixed top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded z-50">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <span id="error-text"></span>
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div class="flex items-center" data-step="1">
                        <div class="bg-indigo-600 text-white rounded-full h-8 w-8 flex items-center justify-center text-sm font-medium">1</div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-indigo-900">Personal Information</p>
                            <p class="text-xs text-indigo-600">Step 1 of 8</p>
                        </div>
                    </div>
                    <div class="flex items-center" data-step="2">
                        <div class="bg-gray-200 text-gray-500 rounded-full h-8 w-8 flex items-center justify-center text-sm font-medium">2</div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">OTP Verification</p>
                            <p class="text-xs text-gray-400">Step 2 of 8</p>
                        </div>
                    </div>
                    <div class="flex items-center" data-step="3">
                        <div class="bg-gray-200 text-gray-500 rounded-full h-8 w-8 flex items-center justify-center text-sm font-medium">3</div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Plan Selection</p>
                            <p class="text-xs text-gray-400">Step 3 of 8</p>
                        </div>
                    </div>
                    <div class="flex items-center" data-step="4">
                        <div class="bg-gray-200 text-gray-500 rounded-full h-8 w-8 flex items-center justify-center text-sm font-medium">4</div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Document Upload</p>
                            <p class="text-xs text-gray-400">Step 4 of 8</p>
                        </div>
                    </div>
                    <div class="flex items-center" data-step="5">
                        <div class="bg-gray-200 text-gray-500 rounded-full h-8 w-8 flex items-center justify-center text-sm font-medium">5</div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Duration Selection</p>
                            <p class="text-xs text-gray-400">Step 5 of 8</p>
                        </div>
                    </div>
                    <div class="flex items-center" data-step="6">
                        <div class="bg-gray-200 text-gray-500 rounded-full h-8 w-8 flex items-center justify-center text-sm font-medium">6</div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Account & Payment</p>
                            <p class="text-xs text-gray-400">Step 6 of 8</p>
                        </div>
                    </div>
                    <div class="flex items-center" data-step="7">
                        <div class="bg-gray-200 text-gray-500 rounded-full h-8 w-8 flex items-center justify-center text-sm font-medium">7</div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Create Account</p>
                            <p class="text-xs text-gray-400">Step 7 of 8</p>
                        </div>
                    </div>
                    <div class="flex items-center" data-step="8">
                        <div class="bg-gray-200 text-gray-500 rounded-full h-8 w-8 flex items-center justify-center text-sm font-medium">8</div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Password Creation</p>
                            <p class="text-xs text-gray-400">Step 8 of 8</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 1: Personal Information -->
            <div id="step-1" class="bg-white shadow-lg rounded-lg p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Personal Information</h2>
                
                <form id="step1-form" class="space-y-6" action="#" method="POST" onsubmit="return false;">
                    <div>
                        <label for="full_name" class="block text-sm font-medium text-gray-700">Full Name</label>
                        <input type="text" id="full_name" name="full_name" required
                               class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    
                    <div>
                        <label for="mobile" class="block text-sm font-medium text-gray-700">Mobile Number</label>
                        <input type="tel" id="mobile" name="mobile" required maxlength="10"
                               class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                               placeholder="10-digit mobile number">
                    </div>
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                        <input type="email" id="email" name="email" required
                               class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="submit" 
                                class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Next Step
                        </button>
                    </div>
                </form>
            </div>

            <!-- Step 2: OTP Verification -->
            <div id="step-2" class="bg-white shadow-lg rounded-lg p-6 hidden">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Mobile Verification</h2>
                
                <div class="text-center mb-6">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mb-4">
                        <i class="fas fa-mobile-alt text-2xl text-green-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">OTP Sent Successfully</h3>
                    <p class="text-gray-600">We've sent a 6-digit OTP to your mobile number</p>
                    <p class="text-sm text-gray-500 mt-1" id="mobile-display"></p>
                </div>
                
                <form id="step2-form" class="space-y-6">
                    <div>
                        <label for="otp" class="block text-sm font-medium text-gray-700">Enter OTP</label>
                        <input type="text" id="otp" name="otp" required maxlength="6" minlength="6"
                               class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-center text-lg font-mono focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                               placeholder="000000">
                        <p class="text-xs text-gray-500 mt-1">Enter the 6-digit code sent to your mobile</p>
                    </div>
                    
                    <div class="flex justify-between">
                        <button type="button" onclick="previousStep()" 
                                class="bg-gray-500 text-white px-6 py-2 rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                            Previous
                        </button>
                        <button type="submit" 
                                class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Verify OTP
                        </button>
                    </div>
                </form>
            </div>

            <!-- Step 3: Plan Selection -->
            <div id="step-3" class="bg-white shadow-lg rounded-lg p-6 hidden">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Select Investment Plan</h2>
                
                <p class="text-gray-600 mb-4">Choose an investment plan or skip for now and join later from your dashboard:</p>
                
                <form id="step3-form" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="border border-gray-200 rounded-lg p-4 hover:border-indigo-300 cursor-pointer plan-option">
                            <input type="radio" name="plan_amount" value="1000" class="sr-only">
                            <h3 class="text-lg font-semibold text-gray-900">₹1,000</h3>
                            <p class="text-sm text-gray-600">Basic Plan</p>
                            <p class="text-xs text-gray-500 mt-2">Pay for 10 months, get 12 months benefit</p>
                        </div>
                        
                        <div class="border border-gray-200 rounded-lg p-4 hover:border-indigo-300 cursor-pointer plan-option">
                            <input type="radio" name="plan_amount" value="10000" class="sr-only">
                            <h3 class="text-lg font-semibold text-gray-900">₹10,000</h3>
                            <p class="text-sm text-gray-600">Standard Plan</p>
                            <p class="text-xs text-gray-500 mt-2">Pay for 10 months, get 12 months benefit</p>
                        </div>
                        
                        <div class="border border-gray-200 rounded-lg p-4 hover:border-indigo-300 cursor-pointer plan-option">
                            <input type="radio" name="plan_amount" value="50000" class="sr-only">
                            <h3 class="text-lg font-semibold text-gray-900">₹50,000</h3>
                            <p class="text-sm text-gray-600">Premium Plan</p>
                            <p class="text-xs text-gray-500 mt-2">Pay for 10 months, get 12 months benefit</p>
                        </div>
                        
                        <div class="border border-gray-200 rounded-lg p-4 hover:border-indigo-300 cursor-pointer plan-option">
                            <input type="radio" name="plan_amount" value="100000" class="sr-only">
                            <h3 class="text-lg font-semibold text-gray-900">₹1,00,000</h3>
                            <p class="text-sm text-gray-600">Ultimate Plan</p>
                            <p class="text-xs text-gray-500 mt-2">Pay for 10 months, get 12 months benefit</p>
                        </div>
                    </div>
                    
                    <div class="flex justify-between">
                        <button type="button" onclick="previousStep()" 
                                class="bg-gray-500 text-white px-6 py-2 rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                            Previous
                        </button>
                        <div class="flex space-x-3">
                            <button type="button" onclick="skipInvestmentPlan()" 
                                    class="bg-gray-500 text-white px-6 py-2 rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                Skip for Now
                            </button>
                            <button type="submit" 
                                    class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Next Step
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Step 4: Document Upload -->
            <div id="step-4" class="bg-white shadow-lg rounded-lg p-6 hidden">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Document Upload</h2>
                
                <form id="step4-form" class="space-y-6">
                    <div>
                        <label for="document_type" class="block text-sm font-medium text-gray-700">Document Type</label>
                        <select id="document_type" name="document_type" required
                                class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Select document type</option>
                            <option value="aadhar">Aadhar Card</option>
                            <option value="pan">PAN Card</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="document_number" class="block text-sm font-medium text-gray-700">Document Number</label>
                        <input type="text" id="document_number" name="document_number" required
                               class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                               placeholder="Enter document number">
                    </div>
                    
                    <div>
                        <label for="document_file" class="block text-sm font-medium text-gray-700">Upload Document</label>
                        <input type="file" id="document_file" name="document_file" required accept=".jpg,.jpeg,.png,.pdf"
                               class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <p class="text-xs text-gray-500 mt-1">Accepted formats: JPG, PNG, PDF (Max 5MB)</p>
                    </div>
                    
                    <div class="flex justify-between">
                        <button type="button" onclick="previousStep()" 
                                class="bg-gray-500 text-white px-6 py-2 rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                            Previous
                        </button>
                        <button type="submit" 
                                class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Next Step
                        </button>
                    </div>
                </form>
            </div>

            <!-- Step 5: Duration Selection -->
            <div id="step-5" class="bg-white shadow-lg rounded-lg p-6 hidden">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Investment Duration</h2>
                
                <form id="step5-form" class="space-y-6">
                    <!-- Hidden input for fixed duration -->
                    <input type="hidden" name="duration_months" value="12">
                    
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                        <div class="flex items-center">
                            <i class="fas fa-info-circle text-blue-600 mr-3"></i>
                            <div>
                                <h3 class="text-sm font-semibold text-blue-900">Investment Terms</h3>
                                <p class="text-sm text-blue-700">Pay for 10 months and get benefit of 12 months</p>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Duration</label>
                        <div class="border border-gray-300 rounded-lg p-4 bg-gray-50">
                            <div class="flex items-center justify-between">
                                <span class="text-lg font-semibold text-gray-900">12 Months</span>
                                <span class="text-sm text-gray-600">Fixed Duration</span>
                            </div>
                            <div class="mt-2 text-sm text-gray-600">
                                <p>• Start Date: <span id="start-date">Today</span></p>
                                <p>• Maturity Date: <span id="maturity-date">12 months from start</span></p>
                                <p>• Payment Schedule: 10 monthly payments</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-between">
                        <button type="button" onclick="previousStep()" 
                                class="bg-gray-500 text-white px-6 py-2 rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                            Previous
                        </button>
                        <button type="submit" 
                                class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Next Step
                        </button>
                    </div>
                </form>
            </div>

            <!-- Step 6: Account & Payment Information -->
            <div id="step-6" class="bg-white shadow-lg rounded-lg p-6 hidden">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Account & Payment Information</h2>
                <p class="text-gray-600 mb-6">Provide your bank account details for investment returns and settlements.</p>
                
                <form id="step6-form" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Bank Account Details</h3>
                            <div class="space-y-4">
                                <div>
                                    <label for="bank_account_holder_name" class="block text-sm font-medium text-gray-700">Account Holder Name</label>
                                    <input type="text" id="bank_account_holder_name" name="bank_account_holder_name" required
                                           class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                           placeholder="Enter account holder name">
                                    <div id="account-holder-error" class="hidden text-xs text-red-600 mt-1"></div>
                                </div>
                                
                                <div>
                                    <label for="bank_account_number" class="block text-sm font-medium text-gray-700">Account Number</label>
                                    <input type="text" id="bank_account_number" name="bank_account_number" required
                                           pattern="[0-9]+" minlength="9" maxlength="18"
                                           class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                           placeholder="Enter account number (numbers only)"
                                           oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                           onblur="validateAccountNumber(this)">
                                    <p class="text-xs text-gray-500 mt-1">Only numbers allowed, 9-18 digits</p>
                                    <div id="account-number-error" class="hidden text-xs text-red-600 mt-1"></div>
                                </div>
                                
                                <div>
                                    <label for="bank_ifsc_code" class="block text-sm font-medium text-gray-700">IFSC Code</label>
                                    <input type="text" id="bank_ifsc_code" name="bank_ifsc_code" required
                                           pattern="[A-Z]{4}0[A-Z0-9]{6}" maxlength="11"
                                           class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                           placeholder="e.g., SBIN0001234"
                                           oninput="this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '')"
                                           onblur="validateIfscCode(this)">
                                    <p class="text-xs text-gray-500 mt-1">Format: SBIN0001234 (11 characters)</p>
                                    <div id="ifsc-code-error" class="hidden text-xs text-red-600 mt-1"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">UPI Details (Optional)</h3>
                            <div class="space-y-4">
                                <div>
                                    <label for="upi_id" class="block text-sm font-medium text-gray-700">UPI ID (Optional)</label>
                                    <input type="text" id="upi_id" name="upi_id"
                                           class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                           placeholder="username@upi"
                                           onblur="validateUpiId(this)">
                                    <p class="text-xs text-gray-500 mt-1">Format: username@upi (e.g., john@okicici)</p>
                                    <div id="upi-id-error" class="hidden text-xs text-red-600 mt-1"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-between">
                        <button type="button" onclick="previousStep()" 
                                class="bg-gray-500 text-white px-6 py-2 rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                            Previous
                        </button>
                        <button type="submit" 
                                class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Next Step
                        </button>
                    </div>
                </form>
            </div>

            <!-- Step 7: Terms & Conditions -->
            <div id="step-7" class="bg-white shadow-lg rounded-lg p-6 hidden">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Terms & Conditions</h2>
                <p class="text-gray-600 mb-6">Please review and accept the terms and conditions to continue.</p>
                
                <form id="step7-form" class="space-y-6">
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Investment Terms</h3>
                        <ul class="space-y-2 text-sm text-gray-700">
                            <li>• Pay for 10 months and get benefit of 12 months</li>
                            <li>• Investment amount will be locked for the duration</li>
                            <li>• Returns will be credited to your registered bank account</li>
                            <li>• Early withdrawal may incur penalties</li>
                            <li>• All transactions are subject to platform terms</li>
                        </ul>
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" id="terms_accepted" name="terms_accepted" required
                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label for="terms_accepted" class="ml-2 block text-sm text-gray-900">
                            I agree to the <a href="#" class="text-indigo-600 hover:text-indigo-500">Terms and Conditions</a>
                        </label>
                    </div>
                    
                    <div class="flex justify-between">
                        <button type="button" onclick="previousStep()" 
                                class="bg-gray-500 text-white px-6 py-2 rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                            Previous
                        </button>
                        <button type="submit" 
                                class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Next Step
                        </button>
                    </div>
                </form>
            </div>

            <!-- Step 8: Password Creation -->
            <div id="step-8" class="bg-white shadow-lg rounded-lg p-6 hidden">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Create Your Account</h2>
                <p class="text-gray-600 mb-6">
                    @if(session('skipInvestmentPlan', false))
                        You've chosen to skip the investment plan for now. You can join investment plans later from your dashboard.
                    @else
                        Complete your registration by creating a secure password for your account.
                    @endif
                </p>
                
                <form id="step8-form" class="space-y-6">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <input type="password" id="password" name="password" required minlength="8"
                               class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                               placeholder="Minimum 8 characters">
                        <div id="password-error" class="text-red-500 text-sm mt-1 hidden"></div>
                    </div>
                    
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                               class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                               placeholder="Confirm your password">
                        <div id="password-confirmation-error" class="text-xs text-red-600 mt-1 hidden"></div>
                    </div>
                    
                    <div class="flex justify-between">
                        <button type="button" onclick="previousStep()" 
                                class="bg-gray-500 text-white px-6 py-2 rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                            Previous
                        </button>
                        <button type="submit" 
                                class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Create Account
                        </button>
                    </div>
                </form>
            </div>

            <!-- Loading and Success Messages -->
            <div id="loading" class="hidden bg-white shadow-lg rounded-lg p-6 text-center">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600 mx-auto"></div>
                <p class="mt-4 text-gray-600">Processing...</p>
            </div>

            <div id="success-message" class="hidden bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded">
                <p id="success-text"></p>
            </div>

            <div id="error-message" class="hidden bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
                <p id="error-text"></p>
            </div>
        </div>
    </div>

    <script>
        // Global variables and functions (accessible from onclick)
        let currentStep = 1;
        const totalSteps = 8;

        // Skip investment plan function (global scope for onclick)
        function skipInvestmentPlan() {
            console.log('skipInvestmentPlan function called');
            
            // Send request to backend to set skip flag
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            console.log('CSRF Token:', csrfToken);
            
            console.log('Sending request to:', '{{ route("registration.skip-investment") }}');
            
            fetch('{{ route("registration.skip-investment") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({})
            })
            .then(response => {
                console.log('Response received:', response);
                return response.json();
            })
            .then(data => {
                console.log('Data received:', data);
                if (data.success) {
                    console.log('Successfully skipped investment plan, moving to step 8');
                    // Move directly to step 8 (Password Creation) - skip all investment steps
                    currentStep = 8;
                    
                    // Hide all steps
                    for (let i = 1; i <= totalSteps; i++) {
                        const stepElement = document.getElementById(`step-${i}`);
                        if (stepElement) {
                            stepElement.classList.add('hidden');
                        }
                    }
                    
                    // Show step 8 (Password Creation)
                    const step8Element = document.getElementById('step-8');
                    if (step8Element) {
                        step8Element.classList.remove('hidden');
                    }
                    
                    // Update progress bar
                    updateProgressBar();
                    
                    // Scroll to step 8
                    step8Element.scrollIntoView({ behavior: 'smooth', block: 'start' });
                } else {
                    console.error('Failed to skip investment plan:', data.message);
                    showError('Failed to skip investment plan. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error skipping investment plan:', error);
                showError('Failed to skip investment plan. Please try again.');
            });
        }

        // Navigation functions (global scope)
        function nextStep() {
            console.log('nextStep called, current step:', currentStep, 'total steps:', totalSteps);
            
            if (currentStep < totalSteps) {
                // Hide current step
                document.getElementById(`step-${currentStep}`).classList.add('hidden');
                
                // Move to next step
                currentStep++;
                console.log('Moved to step:', currentStep);
                
                // Show next step
                document.getElementById(`step-${currentStep}`).classList.remove('hidden');
                
                // Update progress bar
                updateProgressBar();
                
                // Scroll to top of the new step
                document.getElementById(`step-${currentStep}`).scrollIntoView({ behavior: 'smooth', block: 'start' });
            } else {
                console.log('Already at last step');
            }
        }

        function previousStep() {
            console.log('previousStep called, current step:', currentStep);
            
            if (currentStep > 1) {
                // Hide current step
                document.getElementById(`step-${currentStep}`).classList.add('hidden');
                
                // Move to previous step
                currentStep--;
                console.log('Moved to step:', currentStep);
                
                // Show previous step
                document.getElementById(`step-${currentStep}`).classList.remove('hidden');
                
                // Update progress bar
                updateProgressBar();
                
                // Scroll to top of the new step
                document.getElementById(`step-${currentStep}`).scrollIntoView({ behavior: 'smooth', block: 'start' });
            } else {
                console.log('Already at first step');
            }
        }

        // Progress bar function (global scope)
        function updateProgressBar() {
            console.log('Updating progress bar, current step:', currentStep);
            
            // Update progress indicators using data-step attributes
            const progressSteps = document.querySelectorAll('[data-step]');
            progressSteps.forEach((step) => {
                const stepNumber = parseInt(step.getAttribute('data-step'));
                const stepCircle = step.querySelector('.rounded-full');
                const stepTitle = step.querySelector('.text-sm.font-medium');
                const stepSubtitle = step.querySelector('.text-xs');
                
                if (!stepCircle || !stepTitle || !stepSubtitle) {
                    console.warn('Missing elements for step', stepNumber);
                    return;
                }
                
                // Reset all classes first
                stepCircle.classList.remove('bg-gray-200', 'text-gray-500', 'bg-indigo-600', 'bg-green-600', 'text-white');
                stepTitle.classList.remove('text-gray-500', 'text-gray-900', 'text-green-900', 'text-indigo-900');
                stepSubtitle.classList.remove('text-gray-400', 'text-gray-500', 'text-green-600', 'text-indigo-600');
                
                if (stepNumber < currentStep) {
                    // Completed step
                    stepCircle.classList.add('bg-green-600', 'text-white');
                    stepTitle.classList.add('text-green-900');
                    stepSubtitle.classList.add('text-green-600');
                    console.log(`Step ${stepNumber}: Completed (green)`);
                } else if (stepNumber === currentStep) {
                    // Current step
                    stepCircle.classList.add('bg-indigo-600', 'text-white');
                    stepTitle.classList.add('text-indigo-900');
                    stepSubtitle.classList.add('text-indigo-600');
                    console.log(`Step ${stepNumber}: Current (blue)`);
                } else {
                    // Future step
                    stepCircle.classList.add('bg-gray-200', 'text-gray-500');
                    stepTitle.classList.add('text-gray-500');
                    stepSubtitle.classList.add('text-gray-400');
                    console.log(`Step ${stepNumber}: Future (gray)`);
                }
            });
        }

        // Wait for DOM to be fully loaded
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM fully loaded, initializing JavaScript...');
            
            // CSRF token for AJAX requests
            const csrfTokenElement = document.querySelector('meta[name="csrf-token"]');
            if (!csrfTokenElement) {
                console.error('CSRF token meta tag not found!');
            }
            const csrfToken = csrfTokenElement ? csrfTokenElement.getAttribute('content') : '';
            console.log('CSRF Token retrieved:', csrfToken);

            // Step 1 Form Handler
            const step1Form = document.getElementById('step1-form');
            if (step1Form) {
                console.log('Step 1 form found, attaching event listener');
                step1Form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    console.log('Step 1 form submitted - preventDefault called');
                    
                    const formData = new FormData(this);
                    
                    // Log form data for debugging
                    for (let [key, value] of formData.entries()) {
                        console.log(key + ': ' + value);
                    }
                    
                    showLoading();
                    
                    console.log('Sending request to:', '{{ route("registration.step1") }}');
                    console.log('CSRF Token:', csrfToken);
                    
                    fetch('{{ route("registration.step1") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        },
                        body: formData
                    })
                    .then(response => {
                        console.log('Response received:', response);
                        return response.json();
                    })
                    .then(data => {
                        console.log('Data received:', data);
                        hideLoading();
                        if (data.success) {
                            // Store mobile number for display in OTP step
                            const mobile = document.getElementById('mobile').value;
                            document.getElementById('mobile-display').textContent = mobile;
                            
                            showSuccess(data.message);
                            console.log('Step 1 completed successfully, moving to next step');
                            setTimeout(() => {
                                nextStep();
                            }, 1500);
                        } else {
                            showError(data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error occurred:', error);
                        hideLoading();
                        showError('An error occurred. Please try again.');
                    });
                });
                console.log('Step 1 event listener attached successfully');
            } else {
                console.error('Step 1 form not found!');
            }





            // Step 2 Form Handler (OTP Verification)
            const step2Form = document.getElementById('step2-form');
            if (step2Form) {
                step2Form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const otp = document.getElementById('otp').value;
                    if (!otp || otp.length !== 6) {
                        showError('Please enter a valid 6-digit OTP');
                        return;
                    }
                    
                    const formData = new FormData();
                    formData.append('otp', otp);
                    
                    showLoading();
                    
                    fetch('{{ route("registration.step2") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        hideLoading();
                        if (data.success) {
                            showSuccess(data.message);
                            setTimeout(() => {
                                nextStep();
                            }, 1500);
                        } else {
                            showError(data.message);
                        }
                    })
                    .catch(error => {
                        hideLoading();
                        showError('An error occurred. Please try again.');
                    });
                });
            }

            // Step 3 Form Handler (Plan Selection)
            const step3Form = document.getElementById('step3-form');
            if (step3Form) {
                step3Form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const selectedPlan = document.querySelector('input[name="plan_amount"]:checked');
                    if (!selectedPlan) {
                        showError('Please select a plan');
                        return;
                    }
                    
                    const formData = new FormData();
                    formData.append('plan_amount', selectedPlan.value);
                    
                    showLoading();
                    
                    fetch('{{ route("registration.step3") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        hideLoading();
                        if (data.success) {
                            showSuccess(data.message);
                            setTimeout(() => {
                                nextStep();
                            }, 1500);
                        } else {
                            showError(data.message);
                        }
                    })
                    .catch(error => {
                        hideLoading();
                        showError('An error occurred. Please try again.');
                    });
                });
            }

            // Step 4 Form Handler (Document Upload)
            const step4Form = document.getElementById('step4-form');
            if (step4Form) {
                step4Form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const formData = new FormData(this);
                    
                    showLoading();
                    
                    fetch('{{ route("registration.step4") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        hideLoading();
                        if (data.success) {
                            showSuccess(data.message);
                            setTimeout(() => {
                                nextStep();
                            }, 1500);
                        } else {
                            showError(data.message);
                        }
                    })
                    .catch(error => {
                        hideLoading();
                        showError('An error occurred. Please try again.');
                    });
                });
            }

            // Step 5 Form Handler (Duration Selection)
            const step5Form = document.getElementById('step5-form');
            if (step5Form) {
                step5Form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const formData = new FormData();
                    formData.append('duration_months', '12');
                    
                    showLoading();
                    
                    fetch('{{ route("registration.step5") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        hideLoading();
                        if (data.success) {
                            showSuccess(data.message);
                            setTimeout(() => {
                                nextStep();
                            }, 1500);
                        } else {
                            showError(data.message);
                        }
                    })
                    .catch(error => {
                        hideLoading();
                        showError('An error occurred. Please try again.');
                    });
                });
            }

            // Step 6 Form Handler (Account & Payment)
            const step6Form = document.getElementById('step6-form');
            if (step6Form) {
                step6Form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const formData = new FormData(this);
                    
                    showLoading();
                    
                    fetch('{{ route("registration.step6") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        hideLoading();
                        if (data.success) {
                            showSuccess(data.message);
                            setTimeout(() => {
                                nextStep();
                            }, 1500);
                        } else {
                            showError(data.message);
                        }
                    })
                    .catch(error => {
                        hideLoading();
                        showError('An error occurred. Please try again.');
                    });
                });
            }

            // Step 7 Form Handler (Terms & Conditions)
            const step7Form = document.getElementById('step7-form');
            if (step7Form) {
                step7Form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const termsAccepted = document.getElementById('terms_accepted').checked;
                    if (!termsAccepted) {
                        showError('Please accept the terms and conditions');
                        return;
                    }
                    
                    const formData = new FormData();
                    formData.append('terms_accepted', '1');
                    
                    showLoading();
                    
                    fetch('{{ route("registration.step7") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        hideLoading();
                        if (data.success) {
                            showSuccess(data.message);
                            setTimeout(() => {
                                nextStep();
                            }, 1500);
                        } else {
                            showError(data.message);
                        }
                    })
                    .catch(error => {
                        hideLoading();
                        showError('An error occurred. Please try again.');
                    });
                });
            }

            // Step 8 Form Handler (Password Creation)
            const step8Form = document.getElementById('step8-form');
            if (step8Form) {
                step8Form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const password = document.getElementById('password').value;
                    const passwordConfirmation = document.getElementById('password_confirmation').value;
                    
                    if (password.length < 8) {
                        showError('Password must be at least 8 characters long');
                        return;
                    }
                    
                    if (password !== passwordConfirmation) {
                        showError('Passwords do not match');
                        return;
                    }
                    
                    const formData = new FormData();
                    formData.append('password', password);
                    formData.append('password_confirmation', passwordConfirmation);
                    
                    showLoading();
                    
                    fetch('{{ route("registration.create-account") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        hideLoading();
                        if (data.success) {
                            showSuccess(data.message);
                            setTimeout(() => {
                                window.location.href = data.redirect || '{{ route("user.dashboard") }}';
                            }, 1500);
                        } else {
                            showError(data.message);
                        }
                    })
                    .catch(error => {
                        hideLoading();
                        showError('An error occurred. Please try again.');
                    });
                });
            }



            // Plan selection handlers
            document.querySelectorAll('.plan-option').forEach(option => {
                option.addEventListener('click', function() {
                    // Remove selection from all options
                    document.querySelectorAll('.plan-option').forEach(opt => {
                        opt.classList.remove('border-indigo-500', 'bg-indigo-50');
                        opt.classList.add('border-gray-200');
                    });
                    
                    // Select this option
                    this.classList.remove('border-gray-200');
                    this.classList.add('border-indigo-500', 'bg-indigo-50');
                    
                    // Check the radio button
                    const radio = this.querySelector('input[type="radio"]');
                    radio.checked = true;
                });
            });


        
        // Validation functions
        function validateAccountNumber(input) {
            const value = input.value;
            const errorDiv = document.getElementById('account-number-error');
            
            if (!value) {
                showFieldError(input, errorDiv, 'Account number is required');
                return false;
            }
            
            if (!/^[0-9]+$/.test(value)) {
                showFieldError(input, errorDiv, 'Account number must contain only numbers');
                return false;
            }
            
            if (value.length < 9 || value.length > 18) {
                showFieldError(input, errorDiv, 'Account number must be 9-18 digits');
                return false;
            }
            
            hideFieldError(input, errorDiv);
            return true;
        }
        
        function validateIfscCode(input) {
            const value = input.value;
            const errorDiv = document.getElementById('ifsc-code-error');
            
            if (!value) {
                showFieldError(input, errorDiv, 'IFSC code is required');
                return false;
            }
            
            if (!/^[A-Z]{4}0[A-Z0-9]{6}$/.test(value)) {
                showFieldError(input, errorDiv, 'IFSC code must be in format: SBIN0001234');
                return false;
            }
            
            hideFieldError(input, errorDiv);
            return true;
        }
        
        function validateUpiId(input) {
            const value = input.value.trim();
            const errorDiv = document.getElementById('upi-id-error');
            
            // UPI ID is optional, so if empty, it's valid
            if (!value) {
                hideFieldError(input, errorDiv);
                return true;
            }
            
            if (!/^[a-zA-Z0-9._-]+@[a-zA-Z]+$/.test(value)) {
                showFieldError(input, errorDiv, 'UPI ID must be in format: username@upi');
                return false;
            }
            
            hideFieldError(input, errorDiv);
            return true;
        }
        
        function showFieldError(input, errorDiv, message) {
            input.classList.add('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
            input.classList.remove('border-gray-300', 'focus:border-indigo-500', 'focus:ring-indigo-500');
            errorDiv.textContent = message;
            errorDiv.classList.remove('hidden');
        }
        
        function hideFieldError(input, errorDiv) {
            input.classList.remove('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
            input.classList.add('border-gray-300', 'focus:border-indigo-500', 'focus:ring-indigo-500');
            errorDiv.classList.add('hidden');
        }

        function showLoading() {
            document.getElementById('loading').classList.remove('hidden');
        }

        function hideLoading() {
            document.getElementById('loading').classList.add('hidden');
        }

        function showSuccess(message) {
            document.getElementById('success-text').textContent = message;
            document.getElementById('success-message').classList.remove('hidden');
            document.getElementById('error-message').classList.add('hidden');
            
            // Auto-hide after 5 seconds
            setTimeout(() => {
                document.getElementById('success-message').classList.add('hidden');
            }, 5000);
        }

        function showError(message) {
            document.getElementById('error-text').textContent = message;
            document.getElementById('error-message').classList.remove('hidden');
            document.getElementById('success-message').classList.add('hidden');
            
            // Auto-hide after 5 seconds
            setTimeout(() => {
                document.getElementById('error-message').classList.add('hidden');
            }, 5000);
        }

        // Initialize the form
        console.log('Initial current step:', currentStep);
        
        // Initialize progress bar
        updateProgressBar();
        
        // Ensure step 1 is visible and others are hidden on page load
        console.log('DOM loaded, initializing steps');
        
        // Hide all steps except step 1
        for (let i = 2; i <= totalSteps; i++) {
            const stepElement = document.getElementById(`step-${i}`);
            if (stepElement) {
                stepElement.classList.add('hidden');
            }
        }
        
        // Ensure step 1 is visible
        const step1Element = document.getElementById('step-1');
        if (step1Element) {
            step1Element.classList.remove('hidden');
        }
        
        // Set current step to 1
        currentStep = 1;
        console.log('Initial current step:', currentStep);
        
        // Initialize progress bar
        updateProgressBar();
    }); // End of DOMContentLoaded event

    </script>
</body>
</html>

