@extends('layouts.app')

@section('title', 'Edit User — Refugee Needs System')

@section('content')

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Edit Staff Account</h1>
        <p class="text-slate-500 text-sm mt-1">Update details for {{ $user->name }}</p>
    </div>
    <a href="{{ route('admin.users.index') }}"
       class="inline-flex items-center gap-2 bg-white hover:bg-slate-50 text-slate-700 text-sm font-medium px-4 py-2 rounded-lg ring-1 ring-slate-300 transition-colors">
        ← Back to Users
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm ring-1 ring-slate-200 p-6">

        @if($errors->any())
        <div class="mb-5 bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 text-sm">
            {{ $errors->first() }}
        </div>
        @endif

        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-sm font-medium text-slate-700 mb-1.5">Full Name *</label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                    class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-slate-700 mb-1.5">Email Address *</label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                    class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="role" class="block text-sm font-medium text-slate-700 mb-1.5">Role *</label>
                <select id="role" name="role" required
                    class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    <option value="aid_worker" {{ old('role', $user->roles->first()?->name) === 'aid_worker' ? 'selected' : '' }}>Aid Worker</option>
                    <option value="admin"      {{ old('role', $user->roles->first()?->name) === 'admin'      ? 'selected' : '' }}>Administrator</option>
                </select>
                @error('role') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <hr class="border-slate-100">

            <div>
                <label for="password" class="block text-sm font-medium text-slate-700 mb-1.5">
                    New Password
                    <span class="text-slate-400 font-normal text-xs ml-1">— leave blank to keep current</span>
                </label>
                <input type="password" id="password" name="password"
                    class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-1.5">Confirm New Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation"
                    class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            </div>

            <div class="flex gap-3 pt-1">
                <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 px-6 rounded-lg text-sm transition-colors shadow-sm">
                    Save Changes
                </button>
                <a href="{{ route('admin.users.index') }}"
                   class="bg-white hover:bg-slate-50 text-slate-700 font-semibold py-2.5 px-6 rounded-lg text-sm ring-1 ring-slate-300 transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>

    {{-- User info card --}}
    <div class="bg-white rounded-xl shadow-sm ring-1 ring-slate-200 p-6">
        <div class="flex items-center gap-3 mb-5">
            <div class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-lg shrink-0">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div>
                <p class="text-sm font-semibold text-slate-800">{{ $user->name }}</p>
                <p class="text-xs text-slate-500">{{ $user->email }}</p>
            </div>
        </div>
        <dl class="space-y-2 text-sm">
            <div class="flex justify-between">
                <dt class="text-slate-500">Current role</dt>
                <dd>
                    @foreach($user->roles as $role)
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                        {{ $role->name === 'admin' ? 'bg-indigo-100 text-indigo-800' : 'bg-slate-100 text-slate-700' }}">
                        {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                    </span>
                    @endforeach
                </dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-slate-500">Member since</dt>
                <dd class="text-slate-800 font-medium">{{ $user->created_at->format('d M Y') }}</dd>
            </div>
        </dl>

        @if($user->id === Auth::id())
        <div class="mt-4 bg-amber-50 border border-amber-200 text-amber-700 rounded-lg px-3 py-2 text-xs">
            You are editing your own account.
        </div>
        @endif
    </div>

</div>
@endsection
