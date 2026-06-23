@extends('layouts.app')

@section('title', 'Needs — Refugee Needs System')

@section('content')

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Refugee Needs</h1>
        <p class="text-slate-500 text-sm mt-1">All recorded needs, sorted by priority score</p>
    </div>
    @auth
        @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('aid_worker'))
            <a href="{{ route('needs.create') }}"
               class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-4 py-2.5 rounded-lg transition-colors shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Record Need
            </a>
        @endif
    @endauth
</div>

{{-- Filters --}}
<form method="GET" class="bg-white rounded-xl shadow-sm ring-1 ring-slate-200 p-4 mb-6 flex flex-wrap gap-3 items-end">
    <div>
        <label class="block text-xs font-medium text-slate-600 mb-1">Status</label>
        <select name="status" class="border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <option value="">All Statuses</option>
            @foreach(\App\Models\Need::STATUSES as $s)
                <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-xs font-medium text-slate-600 mb-1">Category</label>
        <select name="category" class="border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <option value="">All Categories</option>
            @foreach(\App\Models\Need::CATEGORIES as $c)
                <option value="{{ $c }}" {{ request('category') == $c ? 'selected' : '' }}>{{ ucfirst($c) }}</option>
            @endforeach
        </select>
    </div>
    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
        Filter
    </button>
    <a href="{{ route('needs.index') }}" class="bg-white hover:bg-slate-50 text-slate-700 text-sm font-medium px-4 py-2 rounded-lg ring-1 ring-slate-300 transition-colors">
        Clear
    </a>
</form>

<div class="bg-white rounded-xl shadow-sm ring-1 ring-slate-200 overflow-hidden">
    <table class="min-w-full divide-y divide-slate-200">
        <thead class="bg-slate-50">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Refugee</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Category</th>
                <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Urgency</th>
                <th class="px-4 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Priority Score</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Recorded By</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Date</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-slate-100">
            @php
                $badgeClasses = ['pending'=>'bg-amber-100 text-amber-800','in_progress'=>'bg-blue-100 text-blue-800','resolved'=>'bg-emerald-100 text-emerald-800'];
            @endphp
            @forelse($needs as $need)
            @php $s = $need->priority_score; @endphp
            <tr class="hover:bg-slate-50 transition-colors">
                <td class="px-4 py-3 text-sm font-medium text-slate-800">{{ $need->refugee->name ?? '—' }}</td>
                <td class="px-4 py-3 text-sm text-slate-600 capitalize">{{ $need->category }}</td>
                <td class="px-4 py-3 text-center">
                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full text-xs font-bold
                        {{ $need->urgency_level >= 4 ? 'bg-red-100 text-red-700' : ($need->urgency_level >= 3 ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-600') }}">
                        {{ $need->urgency_level }}
                    </span>
                </td>
                <td class="px-4 py-3 text-right">
                    <span class="text-sm font-bold {{ $s >= 200 ? 'text-red-600' : ($s >= 100 ? 'text-amber-600' : 'text-emerald-600') }}">
                        {{ $s }}
                    </span>
                </td>
                <td class="px-4 py-3">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badgeClasses[$need->status] ?? 'bg-slate-100 text-slate-700' }}">
                        {{ str_replace('_', ' ', $need->status) }}
                    </span>
                </td>
                <td class="px-4 py-3 text-sm text-slate-600">{{ $need->recorder->name ?? '—' }}</td>
                <td class="px-4 py-3 text-sm text-slate-500">{{ $need->created_at->format('d M Y') }}</td>
                <td class="px-4 py-3 text-right">
                    <a href="{{ route('needs.show', $need) }}"
                       class="text-indigo-600 hover:text-indigo-700 text-xs font-medium hover:underline">
                        View →
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="px-4 py-12 text-center text-sm text-slate-400">
                    No needs found. <a href="{{ route('needs.create') }}" class="text-indigo-600 hover:underline">Record the first one</a>.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($needs->hasPages())
<div class="mt-4">{{ $needs->links() }}</div>
@endif
@endsection
