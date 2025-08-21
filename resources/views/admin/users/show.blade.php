@extends('layouts.admin')

@section('content')
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="px-4 py-6 sm:px-0">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">User Details</h1>
                    <p class="mt-2 text-gray-600">{{ $user->name }}</p>
                </div>
                <a href="{{ route('admin.users.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Users
                </a>
            </div>
        </div>

        <div class="px-4 sm:px-0">
            <!-- User Information -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">User Information</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">Basic user details</p>
                </div>
                <div class="border-t border-gray-200">
                    <dl>
                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Name</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $user->name }}</dd>
                        </div>
                        <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Email</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $user->email }}</dd>
                        </div>
                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="mt-1 text-sm sm:mt-0 sm:col-span-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    @if($user->is_active) bg-green-100 text-green-800
                                    @else bg-red-100 text-red-800 @endif">
                                    @if($user->is_active)
                                        <i class="fas fa-check-circle mr-1"></i>Active
                                    @else
                                        <i class="fas fa-times-circle mr-1"></i>Blocked
                                    @endif
                                </span>
                            </dd>
                        </div>
                        <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Joined</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $user->created_at->format('M d, Y H:i:s') }}</dd>
                        </div>
                        @if($user->last_login_at)
                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Last Login</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $user->last_login_at->format('M d, Y H:i:s') }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>
            </div>

            <!-- KITTI Registrations -->
            @if($user->kittiRegistrations->count() > 0)
            <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">KITTI Registrations</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">Investment registrations by this user</p>
                </div>
                <div class="border-t border-gray-200">
                    <ul class="divide-y divide-gray-200">
                        @foreach($user->kittiRegistrations as $registration)
                        <li class="px-4 py-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">Registration #{{ $registration->id }}</div>
                                    <div class="text-sm text-gray-500">₹{{ number_format($registration->plan_amount) }} • {{ $registration->duration_months }} months</div>
                                    <div class="text-xs text-gray-400">
                                        {{ $registration->start_date->format('M d, Y') }} - {{ $registration->maturity_date->format('M d, Y') }}
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        @if($registration->status === 'approved') bg-green-100 text-green-800
                                        @elseif($registration->status === 'pending') bg-yellow-100 text-yellow-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst($registration->status) }}
                                    </span>
                                    <div class="text-xs text-gray-500 mt-1">
                                        <a href="{{ route('admin.registrations.show', $registration) }}" class="text-indigo-600 hover:text-indigo-500">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            <!-- Discontinue Requests -->
            @if($user->discontinueRequests->count() > 0)
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Discontinue Requests</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">Discontinue requests by this user</p>
                </div>
                <div class="border-t border-gray-200">
                    <ul class="divide-y divide-gray-200">
                        @foreach($user->discontinueRequests as $request)
                        <li class="px-4 py-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">Request #{{ $request->id }}</div>
                                    <div class="text-sm text-gray-500">{{ Str::limit($request->reason, 100) }}</div>
                                    <div class="text-xs text-gray-400">{{ $request->created_at->format('M d, Y H:i:s') }}</div>
                                </div>
                                <div class="text-right">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        @if($request->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($request->status === 'approved') bg-green-100 text-green-800
                                        @else bg-red-100 text-red-800 @endif">
                                        {{ ucfirst($request->status) }}
                                    </span>
                                    <div class="text-xs text-gray-500 mt-1">
                                        <a href="{{ route('admin.discontinue-requests.show', $request) }}" class="text-indigo-600 hover:text-indigo-500">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif
        </div>
    </div>
@endsection