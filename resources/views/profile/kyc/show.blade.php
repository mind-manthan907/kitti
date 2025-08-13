@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">KYC Document Details</h1>
            <p class="mt-2 text-gray-600">View your uploaded document information</p>
        </div>

        <!-- Document Details -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Document Information</h3>
                    <div class="space-y-3">
                        <div>
                            <span class="text-sm font-medium text-gray-500">Document Type:</span>
                            <p class="text-sm text-gray-900">{{ $kycDocument->document_type_display }}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Document Number:</span>
                            <p class="text-sm text-gray-900">{{ $kycDocument->document_number }}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Upload Date:</span>
                            <p class="text-sm text-gray-900">{{ $kycDocument->created_at->format('M d, Y \a\t g:i A') }}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Status:</span>
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $kycDocument->status_badge_class }}">
                                {{ ucfirst($kycDocument->status) }}
                            </span>
                        </div>
                        @if($kycDocument->status === 'approved')
                            <div>
                                <span class="text-sm font-medium text-gray-500">Verified Date:</span>
                                <p class="text-sm text-gray-900">{{ $kycDocument->verified_at->format('M d, Y \a\t g:i A') }}</p>
                            </div>
                        @endif
                        @if($kycDocument->status === 'rejected')
                            <div>
                                <span class="text-sm font-medium text-gray-500">Rejection Reason:</span>
                                <p class="text-sm text-red-600">{{ $kycDocument->rejection_reason }}</p>
                            </div>
                        @endif
                    </div>
                </div>
                
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Document Preview</h3>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                        @if(in_array(pathinfo($kycDocument->document_file_path, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png']))
                            <img src="{{ Storage::url($kycDocument->document_file_path) }}" 
                                 alt="Document Preview" 
                                 class="max-w-full h-auto mx-auto rounded">
                        @else
                            <div class="text-gray-400">
                                <i class="fas fa-file-pdf text-6xl mb-4"></i>
                                <p class="text-sm">PDF Document</p>
                                <p class="text-xs text-gray-500">Click download to view</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex justify-between">
            <a href="{{ route('user.profile.kyc.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-md text-sm font-medium">
                <i class="fas fa-arrow-left mr-2"></i>Back to KYC
            </a>
            
            <div class="flex space-x-3">
                @if($kycDocument->status === 'pending')
                    <a href="{{ route('user.profile.kyc.create') }}" 
                       class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-edit mr-2"></i>Upload New Document
                    </a>
                @elseif($kycDocument->status === 'approved')
                    <a href="{{ route('user.profile.kyc.download', $kycDocument) }}" 
                       class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-download mr-2"></i>Download Document
                    </a>
                @elseif($kycDocument->status === 'rejected')
                    <a href="{{ route('user.profile.kyc.create') }}" 
                       class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-upload mr-2"></i>Re-upload Document
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
