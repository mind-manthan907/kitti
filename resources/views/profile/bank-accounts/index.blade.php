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
                <h1 class="text-3xl font-bold text-gray-900">Bank Account Management</h1>
                <p class="mt-2 text-gray-600">Manage your bank accounts for investment plans</p>
            </div>
            <a href="{{ route('user.profile.bank-accounts.create') }}" 
               class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                <i class="fas fa-plus mr-2"></i>Add New Bank Account
            </a>
        </div>

        <!-- Bank Accounts List -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Your Bank Accounts</h2>
            </div>
            
            @if($bankAccounts->count() > 0)
                <div class="divide-y divide-gray-200">
                    @foreach($bankAccounts as $account)
                    <div class="px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-university text-green-600"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $account->bank_name }}
                                        @if($account->is_primary)
                                            <span class="ml-2 inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                                Primary
                                            </span>
                                        @endif
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $account->account_holder_name }}
                                    </div>
                                    <div class="text-xs text-gray-400">
                                        A/C: {{ $account->masked_account_number }} | IFSC: {{ $account->ifsc_code }}
                                        @if($account->branch_name)
                                            | Branch: {{ $account->branch_name }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-3">
                                <!-- Status Badge -->
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $account->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $account->is_active ? 'Active' : 'Inactive' }}
                                </span>
                                
                                <!-- Actions -->
                                <div class="flex space-x-2">
                                    <a href="{{ route('user.profile.bank-accounts.edit', $account) }}" 
                                       class="text-indigo-600 hover:text-indigo-900">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    @if(!$account->is_primary)
                                        <form method="POST" action="{{ route('user.profile.bank-accounts.set-primary', $account) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="text-blue-600 hover:text-blue-900" 
                                                    title="Set as Primary">
                                                <i class="fas fa-star"></i>
                                            </button>
                                        </form>
                                    @endif
                                    
                                    <form method="POST" action="{{ route('user.profile.bank-accounts.toggle-status', $account) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="{{ $account->is_active ? 'text-red-600 hover:text-red-900' : 'text-green-600 hover:text-red-900' }}"
                                                title="{{ $account->is_active ? 'Deactivate' : 'Activate' }}">
                                            <i class="fas fa-{{ $account->is_active ? 'times' : 'check' }}"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="px-6 py-8 text-center">
                    <div class="text-gray-400 mb-4">
                        <i class="fas fa-university text-6xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No bank accounts added</h3>
                    <p class="text-gray-500 mb-4">You need to add at least one bank account to create investment plans.</p>
                                            <a href="{{ route('user.profile.bank-accounts.create') }}" 
                           class="bg-indigo-600 hover:bg-indigo-700 text-sm font-medium">
                            <i class="fas fa-plus mr-2"></i>Add First Bank Account
                        </a>
                </div>
            @endif
        </div>

        <!-- Important Notice -->
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-blue-400"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">Bank Account Information</h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <p>• You can add multiple bank accounts</p>
                        <p>• Only one account can be set as primary</p>
                        <p>• Primary account will be used for investment plans</p>
                        <p>• You can edit account details but cannot delete them</p>
                        <p>• Inactive accounts won't be used for transactions</p>
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
    // Auto-hide session messages after 3 seconds
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
