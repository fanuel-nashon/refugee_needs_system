@extends('layouts.app')

@section('title', 'My Profile — Refugee Needs System')

@section('content')

<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Welcome, {{ $refugee->name }}</h1>
        <p class="text-slate-500 text-sm mt-1">Your registration is on record. Staff will assess your needs.</p>
    </div>
    <form method="POST" action="{{ route('refugee.logout') }}">
        @csrf
        <button type="submit"
            class="inline-flex items-center gap-2 bg-white hover:bg-slate-50 text-slate-600 text-sm font-medium px-4 py-2 rounded-lg ring-1 ring-slate-300 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
            </svg>
            Log Out
        </button>
    </form>
</div>

@if(session('success'))
<div class="mb-5 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg px-4 py-3 text-sm">
    <svg class="w-4 h-4 shrink-0 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
    </svg>
    {{ session('success') }}
</div>
@endif

<div class="mb-6">
    <a href="{{ route('refugee.needs.create') }}"
       class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold px-5 py-2.5 rounded-lg transition-colors shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Request a Need
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Profile card --}}
    <div class="bg-white rounded-xl shadow-sm ring-1 ring-slate-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50">
            <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wide">Registration Details</h2>
        </div>
        <dl class="divide-y divide-slate-100">
            @foreach([
                ['Full Name',        $refugee->name],
                ['Phone Number',     $refugee->phone_no],
                ['Date of Birth',    \Carbon\Carbon::parse($refugee->date_of_birth)->format('d M Y')],
                ['Country of Origin',$refugee->country_of_origin],
                ['Host Country',     $refugee->host_country],
                ['Registered On',    $refugee->created_at->format('d M Y')],
            ] as [$label, $value])
            <div class="px-6 py-3 flex gap-4">
                <dt class="w-36 shrink-0 text-xs font-medium text-slate-500 pt-0.5">{{ $label }}</dt>
                <dd class="text-sm text-slate-800 font-medium">{{ $value }}</dd>
            </div>
            @endforeach
        </dl>
    </div>

    {{-- Needs assessed --}}
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm ring-1 ring-slate-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100">
            <h2 class="text-base font-semibold text-slate-800">
                Assessed Needs
                <span class="ml-2 text-xs text-slate-400 font-normal">{{ $needs->count() }} record{{ $needs->count() === 1 ? '' : 's' }}</span>
            </h2>
        </div>

        @if($needs->isEmpty())
        <div class="px-6 py-16 text-center">
            <svg class="w-10 h-10 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <p class="text-sm text-slate-500">No needs recorded yet.</p>
            <p class="text-xs text-slate-400 mt-1">
                <a href="{{ route('refugee.needs.create') }}" class="text-emerald-600 hover:underline">Submit your first request</a>
                or wait for an aid worker to assess your needs.
            </p>
        </div>
        @else
        @php
            $badgeClasses = [
                'pending'     => 'bg-amber-100 text-amber-800',
                'in_progress' => 'bg-blue-100 text-blue-800',
                'resolved'    => 'bg-emerald-100 text-emerald-800',
            ];
        @endphp
        <table class="min-w-full divide-y divide-slate-100">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Category</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Description</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Urgency</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($needs as $need)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-4 py-3 text-sm font-medium text-slate-800 capitalize">{{ $need->category }}</td>
                    <td class="px-4 py-3 text-sm text-slate-600 max-w-xs truncate">{{ $need->description }}</td>
                    <td class="px-4 py-3 text-center">
                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-full text-xs font-bold
                            {{ $need->urgency_level >= 4 ? 'bg-red-100 text-red-700' : ($need->urgency_level >= 3 ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-600') }}">
                            {{ $need->urgency_level }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badgeClasses[$need->status] ?? 'bg-slate-100 text-slate-700' }}">
                            {{ ucfirst(str_replace('_', ' ', $need->status)) }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-sm text-slate-500">{{ $need->created_at->format('d M Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>

</div>
@endsection
