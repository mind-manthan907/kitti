@extends('layouts.admin')

@section('content')
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="px-4 py-6 sm:px-0">
            <h1 class="text-3xl font-bold text-gray-900">Monthly Dues Tracking</h1>
            <p class="mt-2 text-gray-600">Track overdue payments and send reminders</p>
        </div>

        <!-- Summary Stats -->
        <div class="px-4 sm:px-0 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total Overdue</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ count($monthlyDues) }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-rupee-sign text-orange-600 text-2xl"></i>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total Amount</dt>
                                    <dd class="text-lg font-medium text-gray-900">₹{{ number_format(collect($monthlyDues)->sum('amount'), 2) }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-users text-blue-600 text-2xl"></i>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Affected Users</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ count(array_unique(array_column($monthlyDues, 'user_email'))) }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-clock text-yellow-600 text-2xl"></i>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Avg. Days Late</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ count($monthlyDues) > 0 ? round(collect($monthlyDues)->avg('days_overdue')) : 0 }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bulk Actions -->
        @if(count($monthlyDues) > 0)
        <div class="px-4 sm:px-0 mb-6">
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Bulk Actions</h3>
                <div class="flex flex-wrap gap-4">
                    <button onclick="selectAllUsers()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-check-square mr-2"></i>Select All Users
                    </button>
                    <button onclick="sendBulkReminder()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-envelope mr-2"></i>Send Reminder to Selected
                    </button>
                    <button onclick="exportOverdueData()" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-download mr-2"></i>Export Data
                    </button>
                </div>
            </div>
        </div>
        @endif

        <!-- Overdue Payments List -->
        <div class="px-4 sm:px-0">
            @if(count($monthlyDues) > 0)
            <div class="bg-white shadow overflow-hidden sm:rounded-md">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Overdue Payments</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">List of all overdue monthly payments</p>
                </div>
                <div class="border-t border-gray-200">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <input type="checkbox" id="selectAll" onchange="toggleAllUsers(this)">
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Registration</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Days Overdue</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($monthlyDues as $index => $due)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="checkbox" class="user-checkbox" value="{{ $due['user_email'] }}" data-user-name="{{ $due['user_name'] }}">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                                    <span class="text-indigo-600 font-medium">{{ substr($due['user_name'], 0, 1) }}</span>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $due['user_name'] }}</div>
                                                <div class="text-sm text-gray-500">{{ $due['user_email'] }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">#{{ $due['registration']->id }}</div>
                                        <div class="text-sm text-gray-500">₹{{ number_format($due['registration']->plan_amount) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $due['payment_date']->format('M d, Y') }}</div>
                                        <div class="text-sm text-gray-500">{{ $due['payment_date']->format('l') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">₹{{ number_format($due['amount'], 2) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            @if($due['days_overdue'] <= 7) bg-yellow-100 text-yellow-800
                                            @elseif($due['days_overdue'] <= 30) bg-orange-100 text-orange-800
                                            @else bg-red-100 text-red-800 @endif">
                                            {{ $due['days_overdue'] }} days
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button onclick="sendIndividualReminder('{{ $due['user_email'] }}', '{{ $due['user_name'] }}')" 
                                                class="text-indigo-600 hover:text-indigo-500 mr-3">
                                            <i class="fas fa-envelope mr-1"></i>Remind
                                        </button>
                                        <a href="{{ route('admin.users.show', $due['registration']->user) }}" class="text-green-600 hover:text-green-500">
                                            <i class="fas fa-eye mr-1"></i>View
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @else
            <div class="bg-white shadow rounded-lg p-8 text-center">
                <i class="fas fa-check-circle text-6xl text-green-400 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No Overdue Payments</h3>
                <p class="text-gray-500">All users are up to date with their monthly payments.</p>
            </div>
            @endif
        </div>
    </div>

    <script>
        function selectAllUsers() {
            const checkboxes = document.querySelectorAll('.user-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = true;
            });
            document.getElementById('selectAll').checked = true;
        }

        function toggleAllUsers(checkbox) {
            const userCheckboxes = document.querySelectorAll('.user-checkbox');
            userCheckboxes.forEach(userCheckbox => {
                userCheckbox.checked = checkbox.checked;
            });
        }

        function sendIndividualReminder(email, userName) {
            const message = prompt(`Enter reminder message for ${userName}:`, 'Your monthly payment is overdue. Please make the payment as soon as possible.');
            if (!message) return;

            sendReminder([email], message);
        }

        function sendBulkReminder() {
            const selectedEmails = Array.from(document.querySelectorAll('.user-checkbox:checked')).map(cb => cb.value);
            if (selectedEmails.length === 0) {
                alert('Please select users to send reminders to.');
                return;
            }

            const message = prompt('Enter reminder message for selected users:', 'Your monthly payment is overdue. Please make the payment as soon as possible.');
            if (!message) return;

            sendReminder(selectedEmails, message);
        }

        function sendReminder(emails, message) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            fetch('{{ route("admin.monthly-dues.send-reminder") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    user_emails: emails,
                    reminder_message: message
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                } else {
                    alert('Failed to send reminder: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while sending reminders');
            });
        }

        function exportOverdueData() {
            const data = @json($monthlyDues);
            let csv = 'User Name,Email,Registration ID,Payment Date,Amount,Days Overdue\n';
            
            data.forEach(due => {
                csv += `"${due.user_name}","${due.user_email}","${due.registration.id}","${due.payment_date.format('Y-m-d')}","${due.amount}","${due.days_overdue}"\n`;
            });
            
            const blob = new Blob([csv], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'overdue_payments.csv';
            a.click();
            window.URL.revokeObjectURL(url);
        }
    </script>
@endsection