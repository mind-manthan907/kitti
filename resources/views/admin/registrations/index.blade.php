@extends('layouts.admin')

@section('content')

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="px-4 py-6 sm:px-0 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Registrations</h1>
            <p class="mt-2 text-gray-600">Manage all user registrations</p>
        </div>
        <div class="space-x-2">
            <button id="bulkApproveBtn" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                Bulk Approve
            </button>
            <button id="bulkRejectBtn" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                Bulk Reject
            </button>
        </div>
    </div>

    <!-- Filters -->

    <!-- Filters -->
    <div class="bg-white shadow rounded-lg mb-6 p-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Plan Amount</label>
                <select name="plan_amount" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">All Amounts</option>
                    <option value="10000" {{ request('plan_amount') == '10000' ? 'selected' : '' }}>₹10,000</option>
                    <option value="25000" {{ request('plan_amount') == '25000' ? 'selected' : '' }}>₹25,000</option>
                    <option value="50000" {{ request('plan_amount') == '50000' ? 'selected' : '' }}>₹50,000</option>
                    <option value="100000" {{ request('plan_amount') == '100000' ? 'selected' : '' }}>₹100,000</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Date From</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Date To</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div class="md:col-span-4">
                <button type="submit" class="bg-golden-500 hover:bg-golden-700 text-white px-4 py-2 rounded-md hover:bg-indigo-700">Apply Filters</button>
                <a href="{{ route('admin.registrations.index') }}" class="ml-2 bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">Clear</a>
            </div>
        </form>
    </div>

    <!-- Registrations Table -->
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <div class="px-4 py-5 sm:p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3">
                                <input type="checkbox" id="select-all" class="h-4 w-4 text-indigo-600 border-gray-300 rounded">
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Plan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Payment</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($registrations as $registration)
                        <tr>
                            <td class="px-4 py-4">
                                @if($registration->status == 'pending')
                                <input type="checkbox" class="row-checkbox h-4 w-4 text-indigo-600 border-gray-300 rounded" value="{{ $registration->id }}">
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $registration->full_name }}</div>
                                <div class="text-sm text-gray-500">{{ $registration->email }}</div>
                                <div class="text-sm text-gray-500">{{ $registration->mobile }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                ₹{{ number_format($registration->plan_amount) }} / {{ $registration->duration_months }} months
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($registration->status == 'pending')
                                <span class="px-2 py-1 text-xs rounded bg-yellow-100 text-yellow-800">Pending</span>
                                @elseif($registration->status == 'approved')
                                <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-800">Approved</span>
                                @else
                                <span class="px-2 py-1 text-xs rounded bg-red-100 text-red-800">Rejected</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($registration->latestPayment && $registration->latestPayment->status == 'success')
                                <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-800">Paid</span>
                                @else
                                <span class="px-2 py-1 text-xs rounded bg-red-100 text-red-800">Unpaid</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $registration->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('admin.registrations.show', $registration) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">View</a>
                                @if($registration->status == 'pending')
                                <button onclick="openApproveModal([{{ $registration->id }}])"
                                    class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded-md text-sm">Approve</button>
                                <button onclick="openRejectModal([{{ $registration->id }}])"
                                    class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-md text-sm">Reject</button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">No registrations found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($registrations->hasPages())
            <div class="mt-6">
                {{ $registrations->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Approve Modal -->
<div id="approveModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-md p-6 w-96">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Approve Registration(s)</h3>
        <form id="approveForm" class="space-y-4">
            <input type="hidden" id="approveIds" name="ids">
            <div>
                <label for="adminCredentialsId" class="block text-sm font-medium text-gray-700">Admin Credentials ID</label>
                <input type="text" id="adminCredentialsId" name="admin_credentials_id" required
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="Enter credentials ID">
            </div>
            <div>
                <label for="adminCredentialsPassword" class="block text-sm font-medium text-gray-700">Admin Credentials Password</label>
                <input type="password" id="adminCredentialsPassword" name="admin_credentials_password" required
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="Enter credentials password">
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeApproveModal()" class="px-4 py-2 border border-gray-300 rounded-md">Cancel</button>
                <button type="submit" id="submitApproveBtn" class="px-4 py-2 bg-green-600 text-white rounded-md">Approve</button>
            </div>
        </form>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-md p-6 w-96">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Reject Registration(s)</h3>
        <form id="rejectForm" class="space-y-4">
            <input type="hidden" id="rejectIds" name="ids">
            <div>
                <label for="rejectionReason" class="block text-sm font-medium text-gray-700">Rejection Reason</label>
                <textarea id="rejectionReason" name="rejection_reason" rows="4" required
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="Please provide a reason for rejection..."></textarea>
            </div>

            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeRejectModal()" class="px-4 py-2 border border-gray-300 rounded-md">Cancel</button>
                <button type="submit" id="submitRejectBtn" class="px-4 py-2 bg-red-600 text-white rounded-md">Reject</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Checkbox select-all
    document.getElementById('select-all').addEventListener('change', function(e) {
        document.querySelectorAll('.row-checkbox').forEach(cb => cb.checked = e.target.checked);
    });

    function getSelectedIds() {
        return Array.from(document.querySelectorAll('.row-checkbox:checked')).map(cb => cb.value);
    }

    // Open Modals
    function openApproveModal(ids) {
        document.getElementById('approveIds').value = ids.join(',');
        document.getElementById('approveModal').classList.remove('hidden');
    }

    function openRejectModal(ids) {
        document.getElementById('rejectIds').value = ids.join(',');
        document.getElementById('rejectModal').classList.remove('hidden');
    }

    function closeApproveModal() {
        document.getElementById('approveModal').classList.add('hidden');
    }

    function closeRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
    }

    // Bulk buttons
    document.getElementById('bulkApproveBtn').addEventListener('click', () => {
        let ids = getSelectedIds();
        if (ids.length === 0) return alert("Select at least one registration");
        openApproveModal(ids);
    });
    document.getElementById('bulkRejectBtn').addEventListener('click', () => {
        let ids = getSelectedIds();
        if (ids.length === 0) return alert("Select at least one registration");
        openRejectModal(ids);
    });

    // Submit Approve
    document.getElementById('approveForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        let ids = document.getElementById('approveIds').value.split(',');
        let adminId = document.getElementById('adminCredentialsId').value;
        let password = document.getElementById('adminCredentialsPassword').value;

        let res = await fetch("{{ route('admin.registrations.bulk-approve') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                ids,
                admin_credentials_id: adminId,
                admin_credentials_password: password
            })
        });
        let data = await res.json();
        alert(data.message);
        if (data.success) location.reload();
    });

    // Submit Reject
    document.getElementById('rejectForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        let ids = document.getElementById('rejectIds').value.split(',');
        let reason = document.getElementById('rejectionReason').value;

        let res = await fetch("{{ route('admin.registrations.bulk-reject') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                ids,
                rejection_reason: reason
            })
        });
        let data = await res.json();
        alert(data.message);
        if (data.success) location.reload();
    });
</script>
@endsection