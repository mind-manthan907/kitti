@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <!-- Dashboard Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
            <p class="mt-2 text-gray-600">Manage your investments and account</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('user.profile.kyc.index') }}"
                class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                <i class="fas fa-id-card mr-2"></i>KYC Management
            </a>
            <a href="{{ route('user.profile.bank-accounts.index') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                <i class="fas fa-university mr-2"></i>Bank Accounts
            </a>
            @if($user->hasVerifiedKyc() && $user->hasBankAccount())
            <a href="{{ route('registration.investment-plan') }}"
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                <i class="fas fa-plus mr-2"></i>Create Investment Plan
            </a>
            @endif
        </div>
    </div>

    <!-- KYC Status Section -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-medium text-gray-900">KYC Status</h2>
            <a href="{{ route('user.profile.kyc.index') }}" class="text-indigo-600 hover:text-indigo-500 text-sm">
                Manage KYC <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>

        @if($user->hasVerifiedKyc())
        <div class="flex items-center p-3 bg-green-50 border border-green-200 rounded-lg">
            <div class="flex-shrink-0">
                <i class="fas fa-check-circle text-green-400 text-xl"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-green-800">KYC Verified</p>
                <p class="text-xs text-green-600">You can create investment plans</p>
            </div>
        </div>
        @elseif($user->hasKycDocument())
        <div class="flex items-center p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
            <div class="flex-shrink-0">
                <i class="fas fa-clock text-yellow-400 text-xl"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-yellow-800">KYC Pending Verification</p>
                <p class="text-xs text-yellow-600">Please wait for admin approval</p>
            </div>
        </div>
        @else
        <div class="flex items-center p-3 bg-red-50 border border-red-200 rounded-lg">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-red-400 text-xl"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-red-800">KYC Not Uploaded</p>
                <p class="text-xs text-red-600">Upload KYC documents to create investment plans</p>
            </div>
        </div>
        @endif
    </div>

    <!-- Bank Account Status Section -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-medium text-gray-900">Bank Account Status</h2>
            <a href="{{ route('user.profile.bank-accounts.index') }}" class="text-indigo-600 hover:text-indigo-500 text-sm">
                Manage Accounts <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>

        @if($user->hasBankAccount())
        <div class="flex items-center p-3 bg-green-50 border border-green-200 rounded-lg">
            <div class="flex-shrink-0">
                <i class="fas fa-university text-green-400 text-xl"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-green-800">Bank Account Available</p>
                <p class="text-xs text-green-600">You can proceed with investment plans</p>
            </div>
        </div>
        @else
        <div class="flex items-center p-3 bg-red-50 border border-red-200 rounded-lg">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-red-400 text-xl"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-red-800">No Bank Account</p>
                <p class="text-xs text-red-600">Add bank account to create investment plans</p>
            </div>
        </div>
        @endif
    </div>

    <!-- Investment Plans Section -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-medium text-gray-900">Investment Plans</h2>
            @if($user->hasVerifiedKyc() && $user->hasBankAccount())
            <a href="{{ route('registration.investment-plan') }}" class="bg-golden-500 hover:bg-golden-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                <i class="fas fa-plus mr-2"></i>Create New Plan
            </a>
            @else
            <button disabled class="bg-gray-400 text-white px-4 py-2 rounded-md text-sm font-medium cursor-not-allowed">
                <i class="fas fa-plus mr-2"></i>Create New Plan
            </button>
            @endif
        </div>

        @if($user->kittiRegistrations->count() > 0)
        <div class="space-y-4">
            @foreach($user->kittiRegistrations as $registration)
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-medium text-gray-900">₹{{ number_format($registration->plan_amount) }} Plan</h3>
                        <p class="text-sm text-gray-500">
                            Duration: {{ $registration->duration_months }} months |
                            Start: {{ $registration->start_date->format('M d, Y') }}
                        </p>
                        <p class="text-xs text-gray-400">
                            Maturity: {{ $registration->maturity_date->format('M d, Y') }}
                        </p>
                    </div>
                    <div class="text-right">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $registration->status === 'approved' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ ucfirst($registration->status) }}
                        </span>
                        <div class="mt-1">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $registration->payment_status_badge_class }}">
                                Payment: {{ ucfirst($registration->payment_status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-8">
            <div class="text-golden-700 mb-4">
                <i class="fas fa-chart-line text-6xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No Investment Plans</h3>
            <p class="text-gray-500 mb-4">Start your investment journey by creating a new plan</p>
            @if($user->hasVerifiedKyc() && $user->hasBankAccount())
            <a href="{{ route('registration.investment-plan') }}"
                class="bg-golden-500 hover:bg-golden-700 text-white px-6 py-2 rounded-md text-sm font-medium">
                <i class="fas fa-plus mr-2"></i>Create First Plan
            </a>
            @else
            <div class="space-y-2">
                @if(!$user->hasVerifiedKyc())
                <a href="{{ route('user.profile.kyc.create') }}"
                    class="inline-block bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-id-card mr-2"></i>Upload KYC First
                </a>
                @endif
                @if(!$user->hasBankAccount())
                <a href="{{ route('user.profile.bank-accounts.create') }}"
                    class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-university mr-2"></i>Add Bank Account First
                </a>
                @endif
            </div>
            @endif
        </div>
        @endif
    </div>

    <!-- Dashboard Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 px-4 sm:px-0">
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
                    <a href="{{ route('registration.investment-plan') }}" class="font-medium text-indigo-700 hover:text-indigo-500">
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
                                ₹{{ number_format($activeRegistration->getPaymentAmount()) }}
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

        <!-- Monthly Due Card -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-clock text-2xl text-orange-600"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Monthly Due</dt>
                            <dd class="text-lg font-medium text-gray-900">
                                @if($activeRegistration)
                                ₹{{ number_format($activeRegistration->getPaymentAmount()/ 10) }}
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

    <!-- Benefits & Projected Returns -->
    <div class="mt-8 px-4 sm:px-0">
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Benefits & Projected Returns</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Your investment benefits and expected returns</p>
            </div>
            <div class="border-t border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 p-6">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-green-600">₹{{ number_format($activeRegistration->plan_amount) }}</div>
                        <div class="text-sm text-gray-500">Maturity Value</div>
                        <div class="text-xs text-gray-400">After {{ $activeRegistration->duration_months }} months</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-blue-600">₹{{ number_format($activeRegistration->plan_amount - $activeRegistration->getPaymentAmount()) }}</div>
                        <div class="text-sm text-gray-500">Expected Returns</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-purple-600">{{ $activeRegistration->duration_months }}</div>
                        <div class="text-sm text-gray-500">Investment Period</div>
                        <div class="text-xs text-gray-400">Months</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Payment Section -->
    <div class="mt-8 px-4 sm:px-0">
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Monthly Payment</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Pay your monthly dues and track payment schedule</p>
            </div>
            <div class="border-t border-gray-200 p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Payment Schedule</h4>
                        <div class="space-y-3">
                            @php
                            $startDate = \Carbon\Carbon::parse($activeRegistration->start_date);
                            $totalPayments = 10; // 10 months payment for 12 months benefit
                            @endphp
                            @for($i = 0; $i < $totalPayments; $i++)
                                @php
                                $paymentDate=$startDate->copy()->addMonths($i);
                                $isOverdue = $paymentDate->isPast() && !$activeRegistration->paymentTransactions()->whereMonth('payment_completed_at', $paymentDate->month)->whereYear('payment_completed_at', $paymentDate->year)->where('status', 'success')->exists();
                                @endphp
                                <div class="flex items-center justify-between p-3 {{ $isOverdue ? 'bg-red-50 border border-red-200 rounded' : 'bg-gray-50 border border-gray-200 rounded' }}">
                                    <div>
                                        <div class="font-medium text-gray-900">Month {{ $i + 1 }}</div>
                                        <div class="text-sm text-gray-500">{{ $paymentDate->format('M d, Y') }}</div>
                                    </div>
                                    <div class="text-right">
                                        <div class="font-medium text-gray-900">₹{{ number_format($activeRegistration->getPaymentAmount() / 10) }}</div>
                                        @if($isOverdue)
                                        <div class="text-xs text-red-600">Overdue</div>
                                        @elseif(!$isOverdue)
                                        <div class="text-xs text-orange-400">Unpaid</div>
                                        @else
                                        <div class="text-xs text-green-600">Paid</div>
                                        @endif
                                    </div>
                                </div>
                                @endfor
                        </div>
                    </div>
                    <div>
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Pay Monthly Due</h4>
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="text-sm text-blue-800 mb-4">
                                <strong>Next Payment:</strong> ₹{{ number_format($activeRegistration->getPaymentAmount() /10) }}<br>
                                <strong>Due Date:</strong> {{ $nextPaymentDate ? \Carbon\Carbon::parse($nextPaymentDate)->format('M d, Y') : 'N/A' }}
                            </div>
                            <a href="{{ route('registration.payment', $activeRegistration) }}" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium text-center block">
                                <i class="fas fa-credit-card mr-2"></i>Pay Now
                            </a>
                        </div>
                    </div>
                </div>
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
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 p-4">
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

                    <!-- <a href="{{ route('registration.investment-plan') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                        <i class="fas fa-plus-circle text-2xl text-indigo-600 mr-3"></i>
                        <div>
                            <h4 class="font-medium text-gray-900">Create New Plan</h4>
                            <p class="text-sm text-gray-500">Start another investment</p>
                        </div>
                    </a> -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection