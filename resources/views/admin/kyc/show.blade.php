@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Review KYC Document</h1>
                    <p class="mt-2 text-gray-600">Review and verify user identity document</p>
                </div>
                <a href="{{ route('admin.kyc.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-arrow-left mr-2"></i>Back to KYC List
                </a>
            </div>
        </div>

        <!-- User Information -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <h2 class="text-lg font-medium text-gray-900 mb-4">User Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <div class="mb-3">
                        <span class="text-sm font-medium text-gray-500">Name:</span>
                        <p class="text-sm text-gray-900">{{ $kycDocument->user->name }}</p>
                    </div>
                    <div class="mb-3">
                        <span class="text-sm font-medium text-gray-500">Email:</span>
                        <p class="text-sm text-gray-900">{{ $kycDocument->user->email }}</p>
                    </div>
                    <div class="mb-3">
                        <span class="text-sm font-medium text-gray-500">Phone:</span>
                        <p class="text-sm text-gray-900">{{ $kycDocument->user->phone ?? 'Not provided' }}</p>
                    </div>
                </div>
                <div>
                    <div class="mb-3">
                        <span class="text-sm font-medium text-gray-500">Document Type:</span>
                        <p class="text-sm text-gray-900">{{ $kycDocument->document_type_display }}</p>
                    </div>
                    <div class="mb-3">
                        <span class="text-sm font-medium text-gray-500">Document Number:</span>
                        <p class="text-sm text-gray-900">{{ $kycDocument->document_number }}</p>
                    </div>
                    <div class="mb-3">
                        <span class="text-sm font-medium text-gray-500">Upload Date:</span>
                        <p class="text-sm text-gray-900">{{ $kycDocument->created_at->format('M d, Y \a\t g:i A') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Document Preview -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Document Preview</h2>
            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                @if(in_array(pathinfo($kycDocument->document_file_path, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png']))
                    <img src="{{ Storage::url($kycDocument->document_file_path) }}" 
                         alt="Document Preview" 
                         class="max-w-full h-auto mx-auto rounded max-h-96">
                @else
                    <div class="text-gray-400">
                        <i class="fas fa-file-pdf text-6xl mb-4"></i>
                        <p class="text-sm">PDF Document</p>
                        <a href="{{ route('user.profile.kyc.download', $kycDocument) }}" 
                           class="text-indigo-600 hover:text-indigo-500 text-sm mt-2 inline-block">
                            <i class="fas fa-download mr-1"></i>Download to view
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Current Status -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Current Status</h2>
            <div class="flex items-center space-x-4">
                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $kycDocument->status_badge_class }}">
                    {{ ucfirst($kycDocument->status) }}
                </span>
                
                @if($kycDocument->status === 'approved')
                    <div class="text-sm text-gray-600">
                        <span class="font-medium">Verified by:</span> {{ $kycDocument->verifiedBy->name ?? 'Unknown' }}
                        <span class="mx-2">•</span>
                        <span class="font-medium">Date:</span> {{ $kycDocument->verified_at->format('M d, Y \a\t g:i A') }}
                    </div>
                @elseif($kycDocument->status === 'rejected')
                    <div class="text-sm text-gray-600">
                        <span class="font-medium">Rejection Reason:</span> {{ $kycDocument->rejection_reason }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Admin Notes (if any) -->
        @if($kycDocument->admin_notes)
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Admin Notes</h2>
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-sm text-gray-700">{{ $kycDocument->admin_notes }}</p>
            </div>
        </div>
        @endif

        <!-- Action Buttons -->
        @if($kycDocument->status === 'pending')
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Take Action</h2>
            
            <!-- Approve Form -->
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                <h3 class="text-md font-medium text-green-900 mb-3">Approve Document</h3>
                <form id="approve-form" method="POST" action="{{ route('admin.kyc.approve', $kycDocument) }}" class="space-y-4">
                    @csrf
                    <div>
                        <label for="admin_notes_approve" class="block text-sm font-medium text-gray-700 mb-2">
                            Admin Notes (Optional)
                        </label>
                        <textarea id="admin_notes_approve" name="admin_notes" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
                                  placeholder="Add any notes about this approval..."></textarea>
                    </div>
                    <button type="submit" 
                            class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-check mr-2"></i>Approve Document
                    </button>
                </form>
            </div>

            <!-- Reject Form -->
            <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                <h3 class="text-md font-medium text-red-900 mb-3">Reject Document</h3>
                <form id="reject-form" method="POST" action="{{ route('admin.kyc.reject', $kycDocument) }}" class="space-y-4">
                    @csrf
                    <div>
                        <label for="rejection_reason" class="block text-sm font-medium text-gray-700 mb-2">
                            Rejection Reason <span class="text-red-500">*</span>
                        </label>
                        <textarea id="rejection_reason" name="rejection_reason" rows="3" required
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500"
                                  placeholder="Please provide a clear reason for rejection..."></textarea>
                    </div>
                    <div>
                        <label for="admin_notes_reject" class="block text-sm font-medium text-gray-700 mb-2">
                            Admin Notes (Optional)
                        </label>
                        <textarea id="admin_notes_reject" name="admin_notes" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500"
                                  placeholder="Add any additional notes..."></textarea>
                    </div>
                    <button type="submit" 
                            class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-times mr-2"></i>Reject Document
                    </button>
                </form>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Success/Error Messages -->
<div id="message-container" class="fixed top-4 right-4 z-50 hidden">
    <div id="message-content" class="p-4 rounded-lg shadow-lg max-w-md">
        <div class="flex items-center">
            <div id="message-icon" class="flex-shrink-0 mr-3"></div>
            <div>
                <p id="message-text" class="text-sm font-medium"></p>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle approve form submission
    document.getElementById('approve-form').addEventListener('submit', function(e) {
        e.preventDefault();
        submitForm(this, 'approve');
    });

    // Handle reject form submission
    document.getElementById('reject-form').addEventListener('submit', function(e) {
        e.preventDefault();
        submitForm(this, 'reject');
    });

    function submitForm(form, action) {
        const formData = new FormData(form);
        const url = form.action;

        fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showMessage(data.message, 'success');
                // Redirect after a short delay
                setTimeout(() => {
                    window.location.href = '{{ route("admin.kyc.index") }}';
                }, 2000);
            } else {
                showMessage(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showMessage('An error occurred. Please try again.', 'error');
        });
    }

    function showMessage(message, type) {
        const container = document.getElementById('message-container');
        const content = document.getElementById('message-content');
        const icon = document.getElementById('message-icon');
        const text = document.getElementById('message-text');

        // Set message content
        text.textContent = message;

        // Set styling based on type
        if (type === 'success') {
            content.className = 'p-4 rounded-lg shadow-lg max-w-md bg-green-50 border border-green-200';
            icon.innerHTML = '<i class="fas fa-check-circle text-green-500 text-xl"></i>';
        } else {
            content.className = 'p-4 rounded-lg shadow-lg max-w-md bg-red-50 border border-red-200';
            icon.innerHTML = '<i class="fas fa-exclamation-circle text-red-500 text-xl"></i>';
        }

        // Show message
        container.classList.remove('hidden');

        // Hide message after 5 seconds
        setTimeout(() => {
            container.classList.add('hidden');
        }, 5000);
    }
});
</script>
@endpush
