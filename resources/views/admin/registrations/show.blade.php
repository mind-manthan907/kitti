<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Details - KITTI Admin</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <h1 class="text-2xl font-bold text-indigo-600">KITTI Admin</h1>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.dashboard') }}" class="text-indigo-600 hover:text-indigo-500 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-home mr-2"></i>Dashboard
                    </a>
                    <a href="{{ route('admin.registrations.index') }}" class="text-indigo-600 hover:text-indigo-500 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-list mr-2"></i>Registrations
                    </a>
                    <form method="POST" action="{{ route('auth.logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="px-4 py-6 sm:px-0">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Registration Details</h1>
                    <p class="mt-2 text-gray-600">Registration #{{ $registration->id }} - {{ $registration->full_name }}</p>
                </div>
                <div class="flex space-x-3">
                    @if($registration->status === 'pending')
                    <button id="approveBtn" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-check mr-2"></i>Approve
                    </button>
                    <button id="rejectBtn" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-times mr-2"></i>Reject
                    </button>
                    @endif
                    <a href="{{ route('admin.registrations.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-arrow-left mr-2"></i>Back
                    </a>
                </div>
            </div>
        </div>

        <!-- Registration Information -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 px-4 sm:px-0">
            <!-- Personal Information -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                        <i class="fas fa-user mr-2 text-indigo-600"></i>
                        Personal Information
                    </h3>
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Full Name</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $registration->full_name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Email</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $registration->email }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Mobile</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $registration->mobile }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    @if($registration->status === 'approved') bg-green-100 text-green-800
                                    @elseif($registration->status === 'pending') bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ ucfirst($registration->status) }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Created</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $registration->created_at->format('M d, Y \a\t g:i A') }}</dd>
                        </div>
                        @if($registration->approved_at)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Approved</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $registration->approved_at->format('M d, Y \a\t g:i A') }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>
            </div>

            <!-- Investment Details -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                        <i class="fas fa-chart-line mr-2 text-indigo-600"></i>
                        Investment Details
                    </h3>
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Plan Amount</dt>
                            <dd class="mt-1 text-sm text-gray-900">₹{{ number_format($registration->plan_amount) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Duration</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $registration->duration_months }} months</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Start Date</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($registration->start_date)->format('M d, Y') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Maturity Date</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($registration->maturity_date)->format('M d, Y') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        <!-- Payment & Bank Information -->
        <div class="mt-8 px-4 sm:px-0">
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                        <i class="fas fa-university mr-2 text-indigo-600"></i>
                        Payment & Bank Information
                    </h3>
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Bank Account Holder</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $registration->bank_account_holder_name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Bank Account Number</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $registration->getMaskedBankAccount() }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">IFSC Code</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $registration->bank_ifsc_code }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">UPI ID</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $registration->getMaskedUpiId() }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        <!-- Payment Transactions -->
        @if($registration->paymentTransactions->count() > 0)
        <div class="mt-8 px-4 sm:px-0">
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                        <i class="fas fa-credit-card mr-2 text-indigo-600"></i>
                        Payment Transactions
                    </h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reference</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Method</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($registration->paymentTransactions as $payment)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $payment->transaction_reference }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        ₹{{ number_format($payment->amount, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $payment->getPaymentMethodDisplay() }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            @if($payment->status === 'success') bg-green-100 text-green-800
                                            @elseif($payment->status === 'pending') bg-yellow-100 text-yellow-800
                                            @else bg-red-100 text-red-800 @endif">
                                            {{ ucfirst($payment->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $payment->created_at->format('M d, Y') }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Discontinue Requests -->
        @if($registration->discontinueRequests->count() > 0)
        <div class="mt-8 px-4 sm:px-0">
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                        <i class="fas fa-stop-circle mr-2 text-indigo-600"></i>
                        Discontinue Requests
                    </h3>
                    <div class="space-y-4">
                        @foreach($registration->discontinueRequests as $request)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Request #{{ $request->id }}</p>
                                    <p class="text-sm text-gray-600 mt-1">{{ $request->reason }}</p>
                                    <p class="text-xs text-gray-500 mt-1">Submitted: {{ $request->created_at->format('M d, Y \a\t g:i A') }}</p>
                                </div>
                                <div class="text-right">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        @if($request->status === 'approved') bg-green-100 text-green-800
                                        @elseif($request->status === 'rejected') bg-red-100 text-red-800
                                        @else bg-yellow-100 text-yellow-800 @endif">
                                        {{ ucfirst($request->status) }}
                                    </span>
                                    @if($request->status === 'pending')
                                    <div class="mt-2 space-x-2">
                                        <a href="{{ route('admin.discontinue-requests.show', $request) }}" 
                                           class="text-indigo-600 hover:text-indigo-500 text-sm font-medium">
                                            Review
                                        </a>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Approve Modal -->
    <div id="approveModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Approve Registration</h3>
                <form id="approveForm" class="space-y-4">
                    <div>
                        <label for="adminCredentialsId" class="block text-sm font-medium text-gray-700">Admin Credentials ID</label>
                        <input type="text" id="adminCredentialsId" name="admin_credentials_id" required
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                               placeholder="Enter credentials ID">
                    </div>
                    
                    <div>
                        <label for="adminCredentialsPassword" class="block text-sm font-medium text-gray-700">Admin Credentials Password</label>
                        <input type="password" id="adminCredentialsPassword" name="admin_credentials_password" required
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                               placeholder="Enter credentials password">
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button type="button" id="cancelApproveBtn"
                                class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Cancel
                        </button>
                        <button type="submit" id="submitApproveBtn"
                                class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            Approve
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Reject Registration</h3>
                <form id="rejectForm" class="space-y-4">
                    <div>
                        <label for="rejectionReason" class="block text-sm font-medium text-gray-700">Rejection Reason</label>
                        <textarea id="rejectionReason" name="rejection_reason" rows="4" required
                                  class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                  placeholder="Please provide a reason for rejection..."></textarea>
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button type="button" id="cancelRejectBtn"
                                class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Cancel
                        </button>
                        <button type="submit" id="submitRejectBtn"
                                class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            Reject
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const approveBtn = document.getElementById('approveBtn');
            const rejectBtn = document.getElementById('rejectBtn');
            const approveModal = document.getElementById('approveModal');
            const rejectModal = document.getElementById('rejectModal');
            const cancelApproveBtn = document.getElementById('cancelApproveBtn');
            const cancelRejectBtn = document.getElementById('cancelRejectBtn');
            const approveForm = document.getElementById('approveForm');
            const rejectForm = document.getElementById('rejectForm');
            
            if (approveBtn) {
                approveBtn.addEventListener('click', function() {
                    approveModal.classList.remove('hidden');
                });
            }
            
            if (rejectBtn) {
                rejectBtn.addEventListener('click', function() {
                    rejectModal.classList.remove('hidden');
                });
            }
            
            if (cancelApproveBtn) {
                cancelApproveBtn.addEventListener('click', function() {
                    approveModal.classList.add('hidden');
                    approveForm.reset();
                });
            }
            
            if (cancelRejectBtn) {
                cancelRejectBtn.addEventListener('click', function() {
                    rejectModal.classList.add('hidden');
                    rejectForm.reset();
                });
            }
            
            if (approveForm) {
                approveForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const credentialsId = document.getElementById('adminCredentialsId').value;
                    const credentialsPassword = document.getElementById('adminCredentialsPassword').value;
                    const submitBtn = document.getElementById('submitApproveBtn');
                    
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Approving...';
                    
                    fetch('{{ route("admin.registrations.approve", $registration) }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            admin_credentials_id: credentialsId,
                            admin_credentials_password: credentialsPassword
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Registration approved successfully!');
                            location.reload();
                        } else {
                            alert('Error: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while approving the registration');
                    })
                    .finally(() => {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = 'Approve';
                    });
                });
            }
            
            if (rejectForm) {
                rejectForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const reason = document.getElementById('rejectionReason').value;
                    const submitBtn = document.getElementById('submitRejectBtn');
                    
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Rejecting...';
                    
                    fetch('{{ route("admin.registrations.reject", $registration) }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            rejection_reason: reason
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Registration rejected successfully!');
                            location.reload();
                        } else {
                            alert('Error: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while rejecting the registration');
                    })
                    .finally(() => {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = 'Reject';
                    });
                });
            }
            
            // Close modals when clicking outside
            approveModal.addEventListener('click', function(e) {
                if (e.target === approveModal) {
                    approveModal.classList.add('hidden');
                    approveForm.reset();
                }
            });
            
            rejectModal.addEventListener('click', function(e) {
                if (e.target === rejectModal) {
                    rejectModal.classList.add('hidden');
                    rejectForm.reset();
                }
            });
        });
    </script>
</body>
</html>

