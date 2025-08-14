@extends('layouts.app')

@section('content')
<script>
console.log('🚀 Inline script test - JavaScript is working!');
</script>
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Upload KYC Document</h1>
            <p class="mt-2 text-gray-600">Please provide valid identity verification document</p>
        </div>



        <!-- Upload Form -->
        <div class="bg-white rounded-lg shadow p-6">
            <form id="kyc-upload-form" method="POST" action="{{ route('user.profile.kyc.store') }}" enctype="multipart/form-data">
                @csrf
                
                <!-- Document Type -->
                <div class="mb-6">
                    <label for="document_type" class="block text-sm font-medium text-gray-700 mb-2">
                        Document Type <span class="text-red-500">*</span>
                    </label>
                    <select id="document_type" name="document_type" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">Select document type</option>
                        <option value="aadhar" {{ old('document_type') == 'aadhar' ? 'selected' : '' }}>Aadhar Card</option>
                        <option value="pan" {{ old('document_type') == 'pan' ? 'selected' : '' }}>PAN Card</option>
                        <option value="driving_license" {{ old('document_type') == 'driving_license' ? 'selected' : '' }}>Driving License</option>
                        <option value="passport" {{ old('document_type') == 'passport' ? 'selected' : '' }}>Passport</option>
                    </select>
                    @error('document_type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Document Number -->
                <div class="mb-6">
                    <label for="document_number" class="block text-sm font-medium text-gray-700 mb-2">
                        Document Number <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="document_number" name="document_number" required
                           value="{{ old('document_number') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                           placeholder="Enter document number">
                    @error('document_number')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Document File -->
                <div class="mb-6">
                    <label for="document_file" class="block text-sm font-medium text-gray-700 mb-2">
                        Upload Document <span class="text-red-500">*</span>
                    </label>
                    <input type="file" id="document_file" name="document_file" required
                           accept=".pdf,.jpg,.jpeg,.png"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <p class="mt-1 text-xs text-gray-500">Accepted formats: PDF, JPG, JPEG, PNG (Max: 2MB)</p>
                    @error('document_file')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Important Notes -->
                <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <h4 class="font-medium text-blue-900 mb-2">📋 Important Notes</h4>
                    <div class="text-sm text-blue-800 space-y-1">
                        <p>• Ensure document is clear and readable</p>
                        <p>• Document number should match exactly</p>
                        <p>• File size should not exceed 2MB</p>
                        <p>• Verification typically takes 24-48 hours</p>
                        <p>• You can upload only one document per type</p>
                    </div>
                </div>

                                <!-- Loader Spinner -->
                <div id="upload-loader" class="hidden mb-6">
                    <div class="flex items-center justify-center space-x-3 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                        <span class="text-blue-700 font-medium">Uploading document, please wait...</span>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex justify-between">
                    <a href="{{ route('user.profile.kyc.index') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-arrow-left mr-2"></i>Back to KYC
                    </a>
                    <button type="submit" id="submit-btn"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-upload mr-2"></i>Upload Document
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
    console.log('DOM loaded, initializing KYC upload form...');
    console.log('✅ KYC form JavaScript loaded successfully!');
    
    // Get form elements
    const form = document.getElementById('kyc-upload-form');
    const loaderContainer = document.getElementById('upload-loader');
    const submitBtn = document.getElementById('submit-btn');

    // Debug logging
    console.log('Form found:', form);
    console.log('Loader container found:', loaderContainer);
    console.log('Submit button found:', submitBtn);

    // Check if all elements are found
    if (!form || !loaderContainer || !submitBtn) {
        console.error('Some form elements not found!');
        return;
    }

    // Add real-time validation
    const documentTypeSelect = document.getElementById('document_type');
    const documentNumberInput = document.getElementById('document_number');
    const documentFileInput = document.getElementById('document_file');

    // Real-time document type validation
    documentTypeSelect.addEventListener('change', function() {
        clearFieldError('document_type');
        if (this.value) {
            // Clear document number when type changes
            documentNumberInput.value = '';
            clearFieldError('document_number');
        }
    });

    // Real-time document number validation
    documentNumberInput.addEventListener('input', function() {
        clearFieldError('document_number');
        const value = this.value.trim();
        const documentType = documentTypeSelect.value;
        
        if (value && documentType) {
            validateDocumentNumber(value, documentType);
        }
    });

    // Real-time file validation
    documentFileInput.addEventListener('change', function() {
        clearFieldError('document_file');
        const file = this.files[0];
        if (file) {
            validateDocumentFile(file);
        }
    });



    form.addEventListener('submit', function(e) {
        console.log('Form submit event triggered');
        e.preventDefault();
        console.log('Form submission started - preventDefault called');
        
        // Client-side validation
        const documentType = document.getElementById('document_type').value;
        const documentNumber = document.getElementById('document_number').value.trim();
        const documentFile = document.getElementById('document_file').files[0];
        
        let hasErrors = false;
        
        // Clear previous error messages
        document.querySelectorAll('.text-red-600').forEach(el => el.remove());
        
        // Document Type validation
        if (!documentType) {
            hasErrors = true;
            showFieldError('document_type', 'Please select a document type.');
        }
        
        // Document Number validation
        if (!documentNumber) {
            hasErrors = true;
            showFieldError('document_number', 'Please enter document number.');
        } else {
            // Document-specific validation
            if (documentType === 'aadhar') {
                if (!/^\d{12}$/.test(documentNumber)) {
                    hasErrors = true;
                    showFieldError('document_number', 'Aadhar number must be exactly 12 digits.');
                }
            } else if (documentType === 'pan') {
                if (!/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/.test(documentNumber.toUpperCase())) {
                    hasErrors = true;
                    showFieldError('document_number', 'PAN number must be in format: ABCDE1234F (5 letters + 4 digits + 1 letter).');
                }
            } else if (documentType === 'driving_license') {
                if (!/^[A-Z0-9]{16}$/.test(documentNumber.toUpperCase())) {
                    hasErrors = true;
                    showFieldError('document_number', 'Driving License number must be exactly 16 alphanumeric characters.');
                }
            } else if (documentType === 'passport') {
                if (!/^[A-Z0-9]{8,9}$/.test(documentNumber.toUpperCase())) {
                    hasErrors = true;
                    showFieldError('document_number', 'Passport number must be 8-9 alphanumeric characters.');
                }
            }
        }
        
        // Document File validation
        if (!documentFile) {
            hasErrors = true;
            showFieldError('document_file', 'Please select a document file.');
        } else {
            // File size validation
            if (documentFile.size > 2 * 1024 * 1024) { // 2MB
                hasErrors = true;
                showFieldError('document_file', 'File size must be less than 2MB.');
            }
            
            // File type validation
            const allowedTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
            if (!allowedTypes.includes(documentFile.type)) {
                hasErrors = true;
                showFieldError('document_file', 'Only PDF, JPG, JPEG, and PNG files are allowed.');
            }
        }
        
        if (hasErrors) {
            showMessage('Please fix the validation errors below.', 'error');
            return;
        }
        
        // Show loader
        loaderContainer.classList.remove('hidden');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Uploading...';
        
        console.log('Loader shown, button disabled');

        // Submit form
        const formData = new FormData(form);
        
        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        console.log('CSRF token found:', csrfToken ? 'Yes' : 'No');
        
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => {
            console.log('Response received:', response.status);
            return response.text();
        })
        .then(response => {
            console.log('Response received:', response.status);
            
            // Check if response is a redirect (success) or HTML with errors
            if (response.redirected || response.url !== form.action) {
                // Success - redirect
                console.log('Success - redirecting to:', response.url);
                showMessage('KYC document uploaded successfully! Redirecting...', 'success');
                setTimeout(() => {
                    window.location.href = '{{ route("user.profile.kyc.index") }}';
                }, 2000);
                return;
            }
            
            return response.text();
        })
        .then(html => {
            if (!html) return; // Already handled redirect
            
            console.log('HTML response length:', html.length);
            console.log('Upload completed');
            
            // Check if there are validation errors by looking for specific error patterns
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            
            // Look for validation error messages
            const errorElements = doc.querySelectorAll('.text-red-600');
            const hasValidationErrors = errorElements.length > 0;
            
            if (hasValidationErrors) {
                console.log('Validation errors found:', errorElements.length);
                showMessage('Please fix the validation errors below.', 'error');
                
                // Replace form content with new HTML (preserving errors)
                const formContainer = document.querySelector('.bg-white.rounded-lg.shadow.p-6');
                if (formContainer) {
                    const newFormContent = doc.querySelector('.bg-white.rounded-lg.shadow.p-6');
                    if (newFormContent) {
                        formContainer.innerHTML = newFormContent.innerHTML;
                        
                        // Re-attach event listeners to the new form
                        const newForm = document.getElementById('kyc-upload-form');
                        if (newForm) {
                            newForm.addEventListener('submit', arguments.callee);
                        }
                    }
                }
            } else {
                // Check if it's a success response
                if (html.includes('success') || html.includes('redirect')) {
                    showMessage('KYC document uploaded successfully! Redirecting...', 'success');
                    setTimeout(() => {
                        window.location.href = '{{ route("user.profile.kyc.index") }}';
                    }, 2000);
                } else {
                    showMessage('Upload failed. Please try again.', 'error');
                }
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            showMessage('Upload failed. Please try again.', 'error');
        })
        .finally(() => {
            console.log('Form submission completed, resetting state');
            // Reset form state
            setTimeout(() => {
                loaderContainer.classList.add('hidden');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-upload mr-2"></i>Upload Document';
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

    function clearFieldError(fieldId) {
        const field = document.getElementById(fieldId);
        if (field) {
            const errorEl = field.parentNode.querySelector('.text-red-600');
            if (errorEl) {
                errorEl.remove();
            }
        }
    }

    function validateDocumentNumber(value, documentType) {
        let isValid = true;
        let errorMessage = '';

        if (documentType === 'aadhar') {
            if (!/^\d{12}$/.test(value)) {
                isValid = false;
                errorMessage = 'Aadhar number must be exactly 12 digits.';
            }
        } else if (documentType === 'pan') {
            if (!/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/.test(value.toUpperCase())) {
                isValid = false;
                errorMessage = 'PAN number must be in format: ABCDE1234F (5 letters + 4 digits + 1 letter).';
            }
        } else if (documentType === 'driving_license') {
            if (!/^[A-Z0-9]{16}$/.test(value.toUpperCase())) {
                isValid = false;
                errorMessage = 'Driving License number must be exactly 16 alphanumeric characters.';
            }
        } else if (documentType === 'passport') {
            if (!/^[A-Z0-9]{8,9}$/.test(value.toUpperCase())) {
                isValid = false;
                errorMessage = 'Passport number must be 8-9 alphanumeric characters.';
            }
        }

        if (!isValid) {
            showFieldError('document_number', errorMessage);
        }
    }

    function validateDocumentFile(file) {
        let isValid = true;
        let errorMessage = '';

        // File size validation
        if (file.size > 2 * 1024 * 1024) { // 2MB
            isValid = false;
            errorMessage = 'File size must be less than 2MB.';
        }

        // File type validation
        const allowedTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
        if (!allowedTypes.includes(file.type)) {
            isValid = false;
            errorMessage = 'Only PDF, JPG, JPEG, and PNG files are allowed.';
        }

        if (!isValid) {
            showFieldError('document_file', errorMessage);
        }
    }

    function showMessage(message, type) {
        console.log('Showing message:', message, 'Type:', type);
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
