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
            <form method="POST" action="{{ route('user.profile.kyc.store') }}" enctype="multipart/form-data">
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

                <!-- Submit Buttons -->
                <div class="flex justify-between">
                                            <a href="{{ route('user.profile.kyc.index') }}" 
                           class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-arrow-left mr-2"></i>Back to KYC
                        </a>
                    <button type="submit" 
                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-upload mr-2"></i>Upload Document
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
