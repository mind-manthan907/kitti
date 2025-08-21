@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">KYC Document Management</h1>
            <p class="mt-2 text-gray-600">Review and manage user identity verification documents</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-file-alt text-blue-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Documents</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-clock text-yellow-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Pending Review</p>
                        <p class="text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-check text-green-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Approved</p>
                        <p class="text-2xl font-bold text-green-600">{{ $stats['approved'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-times text-red-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Rejected</p>
                        <p class="text-2xl font-bold text-red-600">{{ $stats['rejected'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <form method="GET" action="{{ route('admin.kyc.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select id="status" name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>

                <div>
                    <label for="document_type" class="block text-sm font-medium text-gray-700 mb-2">Document Type</label>
                    <select id="document_type" name="document_type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">All Types</option>
                        <option value="aadhar" {{ request('document_type') == 'aadhar' ? 'selected' : '' }}>Aadhar Card</option>
                        <option value="pan" {{ request('document_type') == 'pan' ? 'selected' : '' }}>PAN Card</option>
                        <option value="driving_license" {{ request('document_type') == 'driving_license' ? 'selected' : '' }}>Driving License</option>
                        <option value="passport" {{ request('document_type') == 'passport' ? 'selected' : '' }}>Passport</option>
                    </select>
                </div>

                <div>
                    <label for="date_from" class="block text-sm font-medium text-gray-700 mb-2">From Date</label>
                    <input type="date" id="date_from" name="date_from" value="{{ request('date_from') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <div>
                    <label for="date_to" class="block text-sm font-medium text-gray-700 mb-2">To Date</label>
                    <input type="date" id="date_to" name="date_to" value="{{ request('date_to') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <div class="flex items-end">
                    <button type="submit" class="w-full bg-golden-500 hover:bg-golden-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-search mr-2"></i>Apply Filters
                    </button>
                </div>
            </form>
        </div>

        <!-- KYC Documents List -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">KYC Documents</h2>
            </div>
            
            @if($kycDocuments->count() > 0)
                <div class="divide-y divide-gray-200">
                    @foreach($kycDocuments as $document)
                    <div class="px-6 py-4 hover:bg-gray-50">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-id-card text-indigo-600"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $document->user->name }}
                                        <span class="text-xs text-gray-500 ml-2">({{ $document->user->email }})</span>
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $document->document_type_display }} - {{ $document->document_number }}
                                    </div>
                                    <div class="text-xs text-gray-400">
                                        Uploaded: {{ $document->created_at->format('M d, Y \a\t g:i A') }}
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
                                    <a href="{{ route('admin.kyc.show', $document) }}" 
                                       class="text-indigo-600 hover:text-indigo-900 px-3 py-1 rounded text-sm">
                                        <i class="fas fa-eye mr-1"></i>Review
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $kycDocuments->links() }}
                </div>
            @else
                <div class="px-6 py-8 text-center">
                    <div class="text-gray-400 mb-4">
                        <i class="fas fa-file-alt text-6xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No KYC documents found</h3>
                    <p class="text-gray-500">No documents match the current filters.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
