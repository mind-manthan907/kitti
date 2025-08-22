@extends('layouts.app')

@section('content')

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="px-4 py-6 sm:px-0">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Discontinue Requests</h1>
                    <p class="mt-2 text-gray-600">Manage your investment discontinuation requests</p>
                </div>
                @if($registration && $registration->status === 'approved')
                <button id="newRequestBtn" 
                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-plus mr-2"></i>New Request
                </button>
                @endif
            </div>
        </div>

        @if(!$registration)
        <div class="px-4 sm:px-0">
            <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                <div class="flex">
                    <i class="fas fa-exclamation-triangle text-yellow-400 mr-2 mt-0.5"></i>
                    <div class="text-sm text-yellow-700">
                        <p class="font-medium">No KITTI registration found</p>
                        <p class="mt-1">You need to have an active investment plan to submit discontinue requests.</p>
                        <a href="{{ route('registration.create') }}" class="mt-2 inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-yellow-800 bg-yellow-100 hover:bg-yellow-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                            Join Investment Plan
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @else
        <!-- New Request Modal -->
        <div id="newRequestModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">New Discontinue Request</h3>
                    <form id="discontinueForm" class="space-y-4">
                        <div>
                            <label for="reason" class="block text-sm font-medium text-gray-700">Reason for Discontinuation</label>
                            <textarea id="reason" name="reason" rows="4" required
                                      class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                      placeholder="Please provide a detailed reason for discontinuing your investment..."></textarea>
                        </div>
                        
                        <div class="flex justify-end space-x-3">
                            <button type="button" id="cancelRequestBtn"
                                    class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                                Cancel
                            </button>
                            <button type="submit" id="submitRequestBtn"
                                    class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                Submit Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Investment Details -->
        <div class="px-4 sm:px-0 mb-6">
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Current Investment Details</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Plan Amount</p>
                            <p class="text-lg font-medium text-gray-900">₹{{ number_format($registration->plan_amount) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Duration</p>
                            <p class="text-lg font-medium text-gray-900">{{ $registration->duration_months }} months</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Status</p>
                            <p class="text-lg font-medium text-gray-900">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    @if($registration->status === 'approved') bg-green-100 text-green-800
                                    @elseif($registration->status === 'pending') bg-yellow-100 text-yellow-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($registration->status) }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Requests List -->
        <div class="px-4 sm:px-0">
            <div class="bg-white shadow overflow-hidden sm:rounded-md">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Your Requests</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">History of all your discontinue requests</p>
                </div>
                
                @if($requests->count() > 0)
                <ul class="divide-y divide-gray-200">
                    @foreach($requests as $request)
                    <li class="px-4 py-4 sm:px-6">
                        <div class="flex items-center justify-between">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-medium text-indigo-600 truncate">
                                        Request #{{ $request->id }}
                                    </p>
                                    <div class="ml-2 flex-shrink-0 flex">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            @if($request->status === 'approved') bg-green-100 text-green-800
                                            @elseif($request->status === 'rejected') bg-red-100 text-red-800
                                            @else bg-yellow-100 text-yellow-800 @endif">
                                            {{ ucfirst($request->status) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="mt-2 flex">
                                    <div class="flex-1">
                                        <p class="text-sm text-gray-900">{{ $request->reason }}</p>
                                        <p class="text-xs text-gray-500 mt-1">
                                            Submitted: {{ $request->created_at->format('M d, Y \a\t g:i A') }}
                                        </p>
                                        @if($request->processed_at)
                                        <p class="text-xs text-gray-500">
                                            Processed: {{ $request->processed_at->format('M d, Y \a\t g:i A') }}
                                        </p>
                                        @endif
                                    </div>
                                </div>
                                
                                @if($request->status === 'approved' && $request->payout_amount)
                                <div class="mt-2 bg-green-50 border border-green-200 rounded-md p-3">
                                    <p class="text-sm text-green-800">
                                        <strong>Payout Amount:</strong> ₹{{ number_format($request->payout_amount, 2) }}
                                    </p>
                                    <p class="text-sm text-green-700 mt-1">
                                        <strong>Method:</strong> {{ ucfirst($request->payout_method) }}
                                    </p>
                                    @if($request->admin_notes)
                                    <p class="text-sm text-green-700 mt-1">
                                        <strong>Admin Notes:</strong> {{ $request->admin_notes }}
                                    </p>
                                    @endif
                                </div>
                                @endif
                                
                                @if($request->status === 'rejected' && $request->rejection_reason)
                                <div class="mt-2 bg-red-50 border border-red-200 rounded-md p-3">
                                    <p class="text-sm text-red-800">
                                        <strong>Rejection Reason:</strong> {{ $request->rejection_reason }}
                                    </p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
                
                <!-- Pagination -->
                <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                    {{ $requests->links() }}
                </div>
                @else
                <div class="text-center py-12">
                    <i class="fas fa-inbox text-4xl text-gray-400 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No requests yet</h3>
                    <p class="text-gray-500">You haven't submitted any discontinue requests yet.</p>
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('newRequestModal');
            const newRequestBtn = document.getElementById('newRequestBtn');
            const cancelRequestBtn = document.getElementById('cancelRequestBtn');
            const discontinueForm = document.getElementById('discontinueForm');
            
            if (newRequestBtn) {
                newRequestBtn.addEventListener('click', function() {
                    modal.classList.remove('hidden');
                });
            }
            
            if (cancelRequestBtn) {
                cancelRequestBtn.addEventListener('click', function() {
                    modal.classList.add('hidden');
                    discontinueForm.reset();
                });
            }
            
            if (discontinueForm) {
                discontinueForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const reason = document.getElementById('reason').value;
                    const submitBtn = document.getElementById('submitRequestBtn');
                    
                    // Disable submit button
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Submitting...';
                    
                    fetch('{{ route("user.discontinue-requests") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ reason: reason })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Discontinue request submitted successfully!');
                            modal.classList.add('hidden');
                            discontinueForm.reset();
                            location.reload(); // Refresh to show new request
                        } else {
                            alert('Error: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while submitting the request');
                    })
                    .finally(() => {
                        // Re-enable submit button
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = 'Submit Request';
                    });
                });
            }
            
            // Close modal when clicking outside
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    modal.classList.add('hidden');
                    discontinueForm.reset();
                }
            });
        });
    </script>
@endsection
