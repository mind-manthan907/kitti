@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">
            {{ isset($investmentPlan) ? 'Edit Plan' : 'Create New Plan' }}
        </h1>

        <a href="{{ route('admin.investment-plans.index') }}"
            class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium">
            <i class="fas fa-arrow-left mr-2"></i>Back to Investment Plans
        </a>
    </div>

    <div class="px-4 sm:px-0">
        <form id="investmentPlanForm"
            class="space-y-8"
            action="{{ isset($investmentPlan) ? route('admin.investment-plans.update', $investmentPlan->id) : route('admin.investment-plans.store') }}"
            method="POST">
            @csrf
            @if(isset($investmentPlan))
            @method('PUT')
            @endif

            <!-- General Settings -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Plan Name</label>
                            <input type="text" id="name" name="name"
                                value="{{ old('name', $investmentPlan->name ?? '') }}"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                            <p class="text-red-500 text-sm mt-1 error-name"></p>
                        </div>

                        <div>
                            <label for="amount" class="block text-sm font-medium text-gray-700">Amount</label>
                            <input type="number" id="amount" name="amount" min="1000"
                                value="{{ old('amount', $investmentPlan->amount ?? '') }}"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                            <p class="text-red-500 text-sm mt-1 error-amount"></p>
                        </div>

                        <div>
                            <label for="duration_months" class="block text-sm font-medium text-gray-700">Duration Months</label>
                            <select id="duration_months" name="duration_months"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                                <option value="12" selected>12 Months</option>
                            </select>
                            <p class="text-red-500 text-sm mt-1 error-duration_months"></p>
                        </div>

                        <div>
                            <label for="interest_rate" class="block text-sm font-medium text-gray-700">Interest Rate (%)</label>
                            <input type="number" id="interest_rate" name="interest_rate" min="0" max="100" step="0.01"
                                value="{{ old('interest_rate', $investmentPlan->interest_rate ?? '') }}"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                            <p class="text-red-500 text-sm mt-1 error-interest_rate"></p>
                        </div>

                        <div>
                            <label for="min_duration_months" class="block text-sm font-medium text-gray-700">Minimum Duration (Months)</label>
                            <input type="number" id="min_duration_months" name="min_duration_months" min="1" max="120"
                                value="{{ old('min_duration_months', $investmentPlan->min_duration_months ?? '') }}"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                            <p class="text-red-500 text-sm mt-1 error-min_duration_months"></p>
                        </div>

                        <div>
                            <label for="max_duration_months" class="block text-sm font-medium text-gray-700">Maximum Duration (Months)</label>
                            <input type="number" id="max_duration_months" name="max_duration_months" min="1" max="120"
                                value="{{ old('max_duration_months', $investmentPlan->max_duration_months ?? '') }}"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                            <p class="text-red-500 text-sm mt-1 error-max_duration_months"></p>
                        </div>
                    </div>

                    <div class="mt-3 grid grid-cols-1 gap-12 sm:grid-cols-1">
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea id="description" name="description" rows="4"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">{{ old('description', $investmentPlan->description ?? '') }}</textarea>
                            <p class="text-red-500 text-sm mt-1 error-description"></p>
                        </div>

                        <div class="flex items-center">
                            <input
                                type="checkbox"
                                id="is_active"
                                name="is_active"
                                value="1"
                                {{ old('is_active', $investmentPlan->is_active ?? false) ? 'checked' : '' }}
                                class="h-4 w-4 text-indigo-600 border-gray-300 rounded">
                            <label for="is_active" class="ml-2 block text-sm text-gray-900">Active</label>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Save Button -->
            <div class="flex justify-end">
                <button type="submit" id="savePlanBtn"
                    class="bg-golden-500 hover:bg-golden-700 text-white px-6 py-3 rounded-md text-sm font-medium">
                    <i class="fas fa-save mr-2"></i>
                    {{ isset($investmentPlan) ? 'Update Plan' : 'Save Plan' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById("investmentPlanForm").addEventListener("submit", function(e) {
        e.preventDefault();

        let form = e.target;
        let formData = new FormData(form);
        let submitBtn = document.getElementById("savePlanBtn");
        submitBtn.disabled = true;
        submitBtn.innerHTML = "Updating...";

        // Clear previous errors
        document.querySelectorAll("[class^='error-']").forEach(el => el.textContent = "");

        fetch(form.action, {
                method: form.method,
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value,
                    "Accept": "application/json"
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.errors) {
                    Object.keys(data.errors).forEach(field => {
                        let errorEl = document.querySelector(".error-" + field);
                        if (errorEl) errorEl.textContent = data.errors[field][0];
                    });
                } else if (data.success) {
                    alert("✅ Plan updated successfully!");
                    window.location.href = "{{ route('admin.investment-plans.index') }}";
                } else {
                    alert("❌ Something went wrong.");
                }
            })
            .catch(err => {
                console.error(err);
                alert("⚠ Server error. Please try again.");
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-save mr-2"></i> {{ isset($investmentPlan) ? "Update Plan" : "Save Plan" }}';
            });
    });
</script>
@endpush