@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Edit Bank Account</h1>
            <p class="mt-2 text-gray-600">Update your bank account information</p>
        </div>

        <!-- Edit Form -->
        <div class="bg-white rounded-lg shadow p-6">
            <form id="bank-account-edit-form" method="POST" action="{{ route('user.profile.bank-accounts.update', $bankAccount) }}">
                @csrf
                @method('PUT')
                
                <!-- Account Holder Name -->
                <div class="mb-6">
                    <label for="account_holder_name" class="block text-sm font-medium text-gray-700 mb-2">
                        Account Holder Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="account_holder_name" name="account_holder_name" required
                           value="{{ old('account_holder_name', $bankAccount->account_holder_name) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                           placeholder="Enter account holder name">
                    @error('account_holder_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Account Number -->
                <div class="mb-6">
                    <label for="account_number" class="block text-sm font-medium text-gray-700 mb-2">
                        Account Number <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="account_number" name="account_number" required
                           value="{{ old('account_number', $bankAccount->account_number) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                           placeholder="Enter account number">
                    @error('account_number')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Bank Name -->
                <div class="mb-6">
                    <label for="bank_name" class="block text-sm font-medium text-gray-700 mb-2">
                        Bank Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="bank_name" name="bank_name" required
                           value="{{ old('bank_name', $bankAccount->bank_name) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                           placeholder="Enter bank name">
                    @error('bank_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- IFSC Code -->
                <div class="mb-6">
                    <label for="ifsc_code" class="block text-sm font-medium text-gray-700 mb-2">
                        IFSC Code <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="ifsc_code" name="ifsc_code" required
                           value="{{ old('ifsc_code', $bankAccount->ifsc_code) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                           placeholder="Enter IFSC code">
                    @error('ifsc_code')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Branch Name -->
                <div class="mb-6">
                    <label for="branch_name" class="block text-sm font-medium text-gray-700 mb-2">
                        Branch Name (Optional)
                    </label>
                    <input type="text" id="branch_name" name="branch_name"
                           value="{{ old('branch_name', $bankAccount->branch_name) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                           placeholder="Enter branch name">
                    @error('branch_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Primary Account -->
                <div class="mb-6">
                    <div class="flex items-center">
                        <input type="checkbox" id="is_primary" name="is_primary" value="1"
                               {{ old('is_primary', $bankAccount->is_primary) ? 'checked' : '' }}
                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label for="is_primary" class="ml-2 block text-sm text-gray-900">
                            Set as primary account
                        </label>
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Primary account will be used for investment plans</p>
                </div>

                <!-- Loader Spinner -->
                <div id="submit-loader" class="hidden mb-6">
                    <div class="flex items-center justify-center space-x-3 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                        <span class="text-blue-700 font-medium">Updating bank account, please wait...</span>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex justify-between">
                    <a href="{{ route('user.profile.bank-accounts.index') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Accounts
                    </a>
                    <button type="submit" id="submit-btn"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-save mr-2"></i>Update Account
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Response Messages -->
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
    const form = document.getElementById('bank-account-edit-form');
    const loaderContainer = document.getElementById('submit-loader');
    const submitBtn = document.getElementById('submit-btn');

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Client-side validation
        const accountHolderName = document.getElementById('account_holder_name').value.trim();
        const accountNumber = document.getElementById('account_number').value.trim();
        const bankName = document.getElementById('bank_name').value.trim();
        const ifscCode = document.getElementById('ifsc_code').value.trim();
        
        let hasErrors = false;
        
        // Clear previous error messages
        document.querySelectorAll('.text-red-600').forEach(el => el.remove());
        
        // Account Holder Name validation
        if (!accountHolderName) {
            hasErrors = true;
            showFieldError('account_holder_name', 'Please enter account holder name.');
        } else if (accountHolderName.length < 2) {
            hasErrors = true;
            showFieldError('account_holder_name', 'Account holder name must be at least 2 characters.');
        } else if (/\d/.test(accountHolderName)) {
            hasErrors = true;
            showFieldError('account_holder_name', 'Account holder name should not contain numbers.');
        } else if (!/^[a-zA-Z\s\.]+$/.test(accountHolderName)) {
            hasErrors = true;
            showFieldError('account_holder_name', 'Account holder name should only contain letters, spaces, and dots.');
        }
        
        // Account Number validation
        if (!accountNumber) {
            hasErrors = true;
            showFieldError('account_number', 'Please enter account number.');
        } else if (!/^\d+$/.test(accountNumber)) {
            hasErrors = true;
            showFieldError('account_number', 'Account number must contain only digits.');
        } else if (accountNumber.length < 8 || accountNumber.length > 20) {
            hasErrors = true;
            showFieldError('account_number', 'Account number must be 8-20 digits.');
        }
        
        // Bank Name validation
        if (!bankName) {
            hasErrors = true;
            showFieldError('bank_name', 'Please enter bank name.');
        } else if (bankName.length < 2) {
            hasErrors = true;
            showFieldError('bank_name', 'Bank name must be at least 2 characters.');
        } else if (/\d/.test(bankName)) {
            hasErrors = true;
            showFieldError('bank_name', 'Bank name should not contain numbers.');
        } else if (!/^[a-zA-Z\s\&\-\.]+$/.test(bankName)) {
            hasErrors = true;
            showFieldError('bank_name', 'Bank name should only contain letters, spaces, hyphens, ampersands, and dots.');
        }
        
        // IFSC Code validation
        if (!ifscCode) {
            hasErrors = true;
            showFieldError('ifsc_code', 'Please enter IFSC code.');
        } else if (ifscCode.length !== 11) {
            hasErrors = true;
            showFieldError('ifsc_code', 'IFSC code must be exactly 11 characters.');
        } else if (!/^[A-Z]{4}0[A-Z0-9]{6}$/.test(ifscCode.toUpperCase())) {
            hasErrors = true;
            showFieldError('ifsc_code', 'IFSC code must be in format: BANK0001234 (4 letters + 0 + 6 alphanumeric).');
        }
        
        if (hasErrors) {
            showMessage('Please fix the validation errors below.', 'error');
            return;
        }
        
        // Show loader
        loaderContainer.classList.remove('hidden');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Updating...';
        
        // Submit form
        const formData = new FormData(form);
        
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            // Check if response is a redirect (success) or HTML with errors
            if (response.redirected || response.url !== form.action) {
                // Success - redirect
                showMessage('Bank account updated successfully! Redirecting...', 'success');
                setTimeout(() => {
                    window.location.href = '{{ route("user.profile.bank-accounts.index") }}';
                }, 2000);
                return;
            }
            
            return response.text();
        })
        .then(html => {
            if (!html) return; // Already handled redirect
            
            // Check if there are validation errors by looking for specific error patterns
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            
            // Look for validation error messages
            const errorElements = doc.querySelectorAll('.text-red-600');
            const hasValidationErrors = errorElements.length > 0;
            
            if (hasValidationErrors) {
                // Show validation errors
                showMessage('Please fix the validation errors below.', 'error');
                
                // Replace form content with new HTML (preserving errors)
                const formContainer = document.querySelector('.bg-white.rounded-lg.shadow.p-6');
                if (formContainer) {
                    const newFormContent = doc.querySelector('.bg-white.rounded-lg.shadow.p-6');
                    if (newFormContent) {
                        formContainer.innerHTML = newFormContent.innerHTML;
                        
                        // Re-attach event listeners to the new form
                        const newForm = document.getElementById('bank-account-edit-form');
                        if (newForm) {
                            newForm.addEventListener('submit', arguments.callee);
                        }
                    }
                }
            } else {
                // Check if it's a success response
                if (html.includes('success') || html.includes('redirect')) {
                    showMessage('Bank account updated successfully! Redirecting...', 'success');
                    setTimeout(() => {
                        window.location.href = '{{ route("user.profile.bank-accounts.index") }}';
                    }, 2000);
                } else {
                    showMessage('Failed to update bank account. Please try again.', 'error');
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showMessage('Failed to update bank account. Please try again.', 'error');
        })
        .finally(() => {
            // Reset form state
            setTimeout(() => {
                loaderContainer.classList.add('hidden');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-save mr-2"></i>Update Account';
            }, 1000);
        });
    });

    function showFieldError(fieldId, message) {
        const field = document.getElementById(fieldId);
        if (field) {
            const errorEl = document.createElement('p');
            errorEl.className = 'mt-1 text-sm text-red-600';
            errorEl.textContent = message;
            field.parentNode.appendChild(errorEl);
        }
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
