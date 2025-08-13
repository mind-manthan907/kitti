<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Configuration - KITTI Admin</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <h1 class="text-2xl font-bold text-indigo-600">KITTI Admin</h1>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.dashboard') }}" class="text-indigo-600 hover:text-indigo-500 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-home mr-2"></i>Dashboard
                    </a>
                    <form method="POST" action="{{ route('auth.logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="px-4 py-6 sm:px-0">
            <h1 class="text-3xl font-bold text-gray-900">System Configuration</h1>
            <p class="mt-2 text-gray-600">Manage platform settings and configurations</p>
        </div>

        <div class="px-4 sm:px-0">
            <form id="systemConfigForm" class="space-y-8">
                <!-- General Settings -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                            <i class="fas fa-cog mr-2 text-indigo-600"></i>
                            General Settings
                        </h3>
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="company_name" class="block text-sm font-medium text-gray-700">Company Name</label>
                                <input type="text" id="company_name" name="company_name" 
                                       value="{{ $configs['company_name'] ?? 'KITTI Investment Platform' }}"
                                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            
                            <div>
                                <label for="admin_email" class="block text-sm font-medium text-gray-700">Admin Email</label>
                                <input type="email" id="admin_email" name="admin_email" 
                                       value="{{ $configs['admin_email'] ?? '' }}"
                                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            
                            <div>
                                <label for="support_phone" class="block text-sm font-medium text-gray-700">Support Phone</label>
                                <input type="text" id="support_phone" name="support_phone" 
                                       value="{{ $configs['support_phone'] ?? '' }}"
                                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            
                            <div>
                                <label for="support_email" class="block text-sm font-medium text-gray-700">Support Email</label>
                                <input type="email" id="support_email" name="support_email" 
                                       value="{{ $configs['support_email'] ?? '' }}"
                                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Settings -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                            <i class="fas fa-credit-card mr-2 text-indigo-600"></i>
                            Payment Settings
                        </h3>
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="auto_confirm_hours" class="block text-sm font-medium text-gray-700">Auto-Confirm Hours</label>
                                <input type="number" id="auto_confirm_hours" name="auto_confirm_hours" min="1" max="168"
                                       value="{{ $configs['auto_confirm_hours'] ?? 24 }}"
                                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                <p class="mt-1 text-xs text-gray-500">Hours after payment to auto-confirm (1-168)</p>
                            </div>
                            
                            <div class="space-y-4">
                                <div class="flex items-center">
                                    <input type="checkbox" id="payment_gateway_enabled" name="payment_gateway_enabled" value="1"
                                           {{ ($configs['payment_gateway_enabled'] ?? false) ? 'checked' : '' }}
                                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                    <label for="payment_gateway_enabled" class="ml-2 block text-sm text-gray-900">
                                        Enable Payment Gateway
                                    </label>
                                </div>
                                
                                <div class="flex items-center">
                                    <input type="checkbox" id="upi_enabled" name="upi_enabled" value="1"
                                           {{ ($configs['upi_enabled'] ?? false) ? 'checked' : '' }}
                                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                    <label for="upi_enabled" class="ml-2 block text-sm text-gray-900">
                                        Enable UPI Payments
                                    </label>
                                </div>
                                
                                <div class="flex items-center">
                                    <input type="checkbox" id="qr_enabled" name="qr_enabled" value="1"
                                           {{ ($configs['qr_enabled'] ?? false) ? 'checked' : '' }}
                                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                    <label for="qr_enabled" class="ml-2 block text-sm text-gray-900">
                                        Enable QR Code Payments
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notification Settings -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                            <i class="fas fa-bell mr-2 text-indigo-600"></i>
                            Notification Settings
                        </h3>
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div class="flex items-center">
                                <input type="checkbox" id="email_notifications_enabled" name="email_notifications_enabled" value="1"
                                       {{ ($configs['email_notifications_enabled'] ?? true) ? 'checked' : '' }}
                                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <label for="email_notifications_enabled" class="ml-2 block text-sm text-gray-900">
                                    Enable Email Notifications
                                </label>
                            </div>
                            
                            <div class="flex items-center">
                                <input type="checkbox" id="sms_notifications_enabled" name="sms_notifications_enabled" value="1"
                                       {{ ($configs['sms_notifications_enabled'] ?? false) ? 'checked' : '' }}
                                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <label for="sms_notifications_enabled" class="ml-2 block text-sm text-gray-900">
                                    Enable SMS Notifications
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Save Button -->
                <div class="flex justify-end">
                    <button type="submit" id="saveConfigBtn"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-md text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-save mr-2"></i>
                        Save Configuration
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('systemConfigForm');
            const saveBtn = document.getElementById('saveConfigBtn');
            
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Collect form data
                const formData = new FormData(form);
                const data = {};
                
                // Handle checkboxes
                const checkboxes = ['payment_gateway_enabled', 'upi_enabled', 'qr_enabled', 'email_notifications_enabled', 'sms_notifications_enabled'];
                checkboxes.forEach(key => {
                    data[key] = formData.has(key) ? true : false;
                });
                
                // Handle other fields
                ['company_name', 'admin_email', 'support_phone', 'support_email', 'auto_confirm_hours'].forEach(key => {
                    if (formData.has(key)) {
                        data[key] = formData.get(key);
                    }
                });
                
                // Disable save button
                saveBtn.disabled = true;
                saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Saving...';
                
                fetch('{{ route("admin.system-config.update") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Configuration saved successfully!');
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while saving configuration');
                })
                .finally(() => {
                    // Re-enable save button
                    saveBtn.disabled = false;
                    saveBtn.innerHTML = '<i class="fas fa-save mr-2"></i>Save Configuration';
                });
            });
        });
    </script>
</body>
</html>

