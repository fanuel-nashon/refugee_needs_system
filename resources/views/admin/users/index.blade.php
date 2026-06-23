@extends('layouts.app')

@section('title', 'User Management — Refugee Needs System')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-bold text-slate-800">User Management</h1>
    <p class="text-slate-500 text-sm mt-1">Create and manage staff accounts (admins and aid workers)</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

    {{-- User list --}}
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm ring-1 ring-slate-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100">
            <h2 class="text-base font-semibold text-slate-800">Staff Accounts <span class="ml-2 text-xs text-slate-400 font-normal">{{ $users->total() }} total</span></h2>
        </div>
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Name</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Email</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Role</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Created</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-slate-100">
                @forelse($users as $user)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-semibold text-sm shrink-0">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <span class="text-sm font-medium text-slate-800">{{ $user->name }}</span>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-sm text-slate-600">{{ $user->email }}</td>
                    <td class="px-4 py-3">
                        @foreach($user->roles as $role)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $role->name === 'admin' ? 'bg-indigo-100 text-indigo-800' : 'bg-slate-100 text-slate-700' }}">
                                {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                            </span>
                        @endforeach
                    </td>
                    <td class="px-4 py-3 text-sm text-slate-500">{{ $user->created_at->format('d M Y') }}</td>
                    <td class="px-4 py-3 text-right">
                        @if($user->id !== Auth::id())
                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                              onsubmit="return confirm('Remove {{ addslashes($user->name) }}? This cannot be undone.');">
                            @csrf @method('DELETE')
                            <button type="submit"
                                class="text-red-500 hover:text-red-700 text-xs font-medium hover:underline transition-colors">
                                Remove
                            </button>
                        </form>
                        @else
                        <span class="text-xs text-slate-400 italic">You</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-4 py-10 text-center text-sm text-slate-400">No staff accounts yet.</td></tr>
                @endforelse
            </tbody>
        </table>
        @if($users->hasPages())
        <div class="px-4 py-3 border-t border-slate-100">{{ $users->links() }}</div>
        @endif
    </div>

    {{-- Create form --}}
    <div class="bg-white rounded-xl shadow-sm ring-1 ring-slate-200 p-6">
        <h2 class="text-base font-semibold text-slate-800 mb-5">Create Staff Account</h2>

        @if($errors->any())
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 text-sm">
            {{ $errors->first() }}
        </div>
        @endif

        <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-4">
            @csrf

            <div>
                <label for="name" class="block text-xs font-medium text-slate-600 mb-1.5">Full Name *</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                    class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="email" class="block text-xs font-medium text-slate-600 mb-1.5">Email *</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                    class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="role" class="block text-xs font-medium text-slate-600 mb-1.5">Role *</label>
                <select id="role" name="role" required
                    class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    <option value="aid_worker" {{ old('role') == 'aid_worker' ? 'selected' : '' }}>Aid Worker</option>
                    <option value="admin"      {{ old('role') == 'admin'      ? 'selected' : '' }}>Administrator</option>
                </select>
                @error('role') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="password" class="block text-xs font-medium text-slate-600 mb-1.5">Password *</label>
                <input type="password" id="password" name="password" required
                    class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-xs font-medium text-slate-600 mb-1.5">Confirm Password *</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required
                    class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            </div>

            <button type="submit"
                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 rounded-lg text-sm transition-colors shadow-sm">
                Create Account
            </button>
        </form>
    </div>

</div>
@endsection
