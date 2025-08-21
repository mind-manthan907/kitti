@extends('layouts.admin')

@section('content')
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="px-4 py-6 sm:px-0">
            <h1 class="text-3xl font-bold text-gray-900">Users Management</h1>
            <p class="mt-2 text-gray-600">Manage all registered users</p>
        </div>

        <!-- Filters and Search -->
        <div class="px-4 sm:px-0 mb-6">
            <div class="bg-white shadow rounded-lg p-6">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                               placeholder="Name or email">
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" id="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">All</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="bg-golden-500 hover:bg-golden-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-search mr-2"></i>Apply Filters
                        </button>
                        <a href="{{ route('admin.users.index') }}" class="ml-2 bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium">
                            Clear
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Users List -->
        <div class="px-4 sm:px-0">
            <div class="bg-white shadow overflow-hidden sm:rounded-md">
                <ul class="divide-y divide-gray-200">
                    @forelse($users as $user)
                    <li class="px-4 py-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                        <span class="text-indigo-600 font-medium">{{ substr($user->name, 0, 1) }}</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                    <div class="text-xs text-gray-400">
                                        Joined: {{ $user->created_at->format('M d, Y') }}
                                        @if($user->last_login_at)
                                            • Last login: {{ $user->last_login_at->format('M d, Y') }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-4">
                                <div class="text-right">
                                    <div class="text-sm text-gray-900">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            @if($user->is_active) bg-green-100 text-green-800
                                            @else bg-red-100 text-red-800 @endif">
                                            @if($user->is_active)
                                                <i class="fas fa-check-circle mr-1"></i>Active
                                            @else
                                                <i class="fas fa-times-circle mr-1"></i>Blocked
                                            @endif
                                        </span>
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        {{ $user->kittiRegistrations->count() }} registrations
                                    </div>
                                </div>
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.users.show', $user) }}" class="text-golden-500 hover:text-golden-700 px-3 py-1 rounded-md text-sm font-medium">
                                        <i class="fas fa-eye mr-1"></i>View
                                    </a>
                                    <button onclick="toggleUserStatus({{ $user->id }}, {{ $user->is_active ? 'true' : 'false' }})" 
                                            class="px-3 py-1 rounded-md text-sm font-medium 
                                            @if($user->is_active) text-red-600 hover:text-red-500
                                            @else text-green-600 hover:text-green-500 @endif">
                                        @if($user->is_active)
                                            <i class="fas fa-ban mr-1"></i>Block
                                        @else
                                            <i class="fas fa-check mr-1"></i>Enable
                                        @endif
                                    </button>
                                </div>
                            </div>
                        </div>
                    </li>
                    @empty
                    <li class="px-4 py-8 text-center text-gray-500">
                        <i class="fas fa-users text-4xl text-gray-300 mb-4"></i>
                        <p>No users found</p>
                    </li>
                    @endforelse
                </ul>
            </div>

            <!-- Pagination -->
            @if($users->hasPages())
            <div class="px-4 sm:px-0 mt-6">
                {{ $users->links() }}
            </div>
            @endif
        </div>
    </div>

    <script>
        function toggleUserStatus(userId, currentStatus) {
            const action = currentStatus ? 'block' : 'enable';
            if (!confirm(`Are you sure you want to ${action} this user?`)) {
                return;
            }

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            fetch(`/admin/users/${userId}/toggle-status`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert('Failed to update user status: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating user status');
            });
        }
    </script>
@endsection
