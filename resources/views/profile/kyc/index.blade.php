@extends('layouts.app')

@section('content')
@if(session('success'))
<div class="fixed top-4 right-4 z-50 transition-all duration-300 ease-in-out">
    <div class="p-4 rounded-lg shadow-lg max-w-md bg-green-50 border border-green-200">
        <div class="flex items-center">
            <div class="flex-shrink-0 mr-3">
                <i class="fas fa-check-circle text-green-500 text-xl"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
            </div>
        </div>
    </div>
</div>
@endif

@if(session('error'))
<div class="fixed top-4 right-4 z-50 transition-all duration-300 ease-in-out">
    <div class="p-4 rounded-lg shadow-lg max-w-md bg-red-50 border border-red-200">
        <div class="flex items-center">
            <div class="flex-shrink-0 mr-3">
                <i class="fas fa-exclamation-circle text-red-500 text-xl"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
            </div>
        </div>
    </div>
</div>
@endif
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">KYC Management</h1>
                <p class="mt-2 text-gray-600">Manage your identity verification documents</p>
            </div>
            <a href="{{ route('user.profile.kyc.create') }}" 
               class="bg-golden-500 hover:bg-golden-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                <i class="fas fa-plus mr-2"></i>Upload New Document
            </a>
        </div>

        <!-- KYC Status Overview -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">KYC Status Overview</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="text-center p-4 bg-blue-50 rounded-lg">
                    <div class="text-2xl font-bold text-blue-600">{{ $kycDocuments->where('status', 'pending')->count() }}</div>
                    <div class="text-sm text-blue-600">Pending</div>
                </div>
                <div class="text-center p-4 bg-green-50 rounded-lg">
                    <div class="text-2xl font-bold text-green-600">{{ $kycDocuments->where('status', 'approved')->count() }}</div>
                    <div class="text-sm text-green-600">Approved</div>
                </div>
                <div class="text-center p-4 bg-red-50 rounded-lg">
                    <div class="text-2xl font-bold text-red-600">{{ $kycDocuments->where('status', 'rejected')->count() }}</div>
                    <div class="text-sm text-red-600">Rejected</div>
                </div>
            </div>
        </div>

        <!-- KYC Documents List -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Your KYC Documents</h2>
            </div>
            
            @if($kycDocuments->count() > 0)
                <div class="divide-y divide-gray-200">
                    @foreach($kycDocuments as $document)
                    <div class="px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-id-card text-indigo-600"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $document->document_type_display }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $document->document_number }}
                                    </div>
                                    <div class="text-xs text-gray-400">
                                        Uploaded: {{ $document->created_at->format('M d, Y') }}
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-3">
                                <!-- Status Badge -->
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $document->status_badge_class }}">
                                    {{ ucfirst($document->status) }}
                                </span>
                                
                                <!-- Actions -->
                                <div class="flex space-x-2">
                                    @if($document->status === 'pending')
                                        <a href="{{ route('user.profile.kyc.show', $document) }}" 
                                           class="text-indigo-600 hover:text-indigo-900">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <form method="POST" action="{{ route('user.profile.kyc.destroy', $document) }}" 
                                              class="inline" onsubmit="return confirm('Are you sure you want to delete this document?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @elseif($document->status === 'approved')
                                        <a href="{{ route('user.profile.kyc.download', $document) }}" 
                                           class="text-green-600 hover:text-green-900">
                                            <i class="fas fa-download"></i>
                                        </a>
                                        <span class="text-xs text-gray-500">
                                            Verified: {{ $document->verified_at->format('M d, Y') }}
                                        </span>
                                                                         @elseif($document->status === 'rejected')
                                         <a href="{{ route('user.profile.kyc.show', $document) }}" 
                                            class="text-red-600 hover:text-red-900">
                                             <i class="fas fa-eye"></i>
                                         </a>
                                         <div class="text-xs text-red-600 max-w-xs">
                                             <strong>Reason:</strong> {{ $document->rejection_reason }}
                                             @if($document->admin_notes)
                                                 <br><strong>Admin Notes:</strong> {{ $document->admin_notes }}
                                             @endif
                                         </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="px-6 py-8 text-center">
                    <div class="text-gray-400 mb-4">
                        <i class="fas fa-id-card text-6xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No KYC documents uploaded</h3>
                    <p class="text-gray-500 mb-4">You need to upload KYC documents to create investment plans.</p>
                                            <a href="{{ route('user.profile.kyc.create') }}" 
                           class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-plus mr-2"></i>Upload First Document
                        </a>
                </div>
            @endif
        </div>

        <!-- Important Notice -->
        <div class="mt-6 bg-golden-200 border border-yellow-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-golden-600"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-golden-900">Important Notice</h3>
                    <div class="mt-2 text-sm text-golden-800">
                        <p>• KYC verification is mandatory before creating investment plans</p>
                        <p>• Document verification typically takes 24-48 hours</p>
                        <p>• If rejected, please re-upload with correct information</p>
                        <p>• Only approved documents allow investment plan creation</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide session messages after 
    const messages = document.querySelectorAll('.fixed.top-4.right-4.z-50');
    messages.forEach(message => {
        setTimeout(() => {
            message.style.opacity = '0';
            message.style.transform = 'translateY(-100%)';
            setTimeout(() => {
                message.remove();
            }, 300);
        }, 3000);
    });
});
</script>
@endpush
