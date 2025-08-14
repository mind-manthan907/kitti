@extends('layouts.app')

@section('content')
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

                                <!-- Progress Bar -->
                <div id="upload-progress" class="hidden mb-6">
                    <div class="mb-2 flex justify-between text-sm text-gray-600">
                        <span>Uploading...</span>
                        <span id="progress-percentage">0%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div id="progress-bar" class="bg-indigo-600 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
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
    const form = document.getElementById('kyc-upload-form');
    const progressContainer = document.getElementById('upload-progress');
    const progressBar = document.getElementById('progress-bar');
    const progressPercentage = document.getElementById('progress-percentage');
    const submitBtn = document.getElementById('submit-btn');

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Show progress bar
        progressContainer.classList.remove('hidden');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Uploading...';
        
        // Simulate progress (since we can't get real upload progress with regular form submission)
        let progress = 0;
        const progressInterval = setInterval(() => {
            progress += Math.random() * 15;
            if (progress > 90) progress = 90;
            
            progressBar.style.width = progress + '%';
            progressPercentage.textContent = Math.round(progress) + '%';
        }, 200);

        // Submit form
        const formData = new FormData(form);
        
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.text())
        .then(html => {
            // Complete progress
            clearInterval(progressInterval);
            progressBar.style.width = '100%';
            progressPercentage.textContent = '100%';
            
            // Check if there are validation errors
            if (html.includes('error')) {
                // Parse HTML to check for validation errors
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const errorElements = doc.querySelectorAll('.text-red-600');
                
                if (errorElements.length > 0) {
                    // Show validation errors
                    showMessage('Please fix the validation errors below.', 'error');
                    // Replace form content with new HTML (preserving errors)
                    document.querySelector('.bg-white.rounded-lg.shadow.p-6').innerHTML = 
                        doc.querySelector('.bg-white.rounded-lg.shadow.p-6').innerHTML;
                } else {
                    showMessage('Upload failed. Please try again.', 'error');
                }
            } else {
                // Success - redirect
                showMessage('KYC document uploaded successfully! Redirecting...', 'success');
                setTimeout(() => {
                    window.location.href = '{{ route("user.profile.kyc.index") }}';
                }, 2000);
            }
        })
        .catch(error => {
            clearInterval(progressInterval);
            console.error('Error:', error);
            showMessage('Upload failed. Please try again.', 'error');
        })
        .finally(() => {
            // Reset form state
            setTimeout(() => {
                progressContainer.classList.add('hidden');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-upload mr-2"></i>Upload Document';
                progressBar.style.width = '0%';
                progressPercentage.textContent = '0%';
            }, 1000);
        });
    });

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
