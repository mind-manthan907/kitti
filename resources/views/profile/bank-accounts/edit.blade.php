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
            <form method="POST" action="{{ route('user.profile.bank-accounts.update', $bankAccount) }}">
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

                <!-- Submit Buttons -->
                <div class="flex justify-between">
                    <a href="{{ route('user.profile.bank-accounts.index') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Accounts
                    </a>
                    <button type="submit" 
                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-save mr-2"></i>Update Account
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
