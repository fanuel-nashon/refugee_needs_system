@extends('layouts.app')

@section('title', 'Dashboard — Refugee Needs System')

@section('content')

<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Dashboard</h1>
        <p class="text-slate-500 text-sm mt-1">Overview of needs assessment and prioritization</p>
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

{{-- Stats --}}
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 mb-8">
    @php
    $statCards = [
        ['label' => 'Refugees',   'value' => $stats['total_refugees'], 'color' => 'indigo'],
        ['label' => 'Total Needs','value' => $stats['total_needs'],    'color' => 'slate'],
        ['label' => 'Pending',    'value' => $stats['pending'],        'color' => 'amber'],
        ['label' => 'In Progress','value' => $stats['in_progress'],    'color' => 'blue'],
        ['label' => 'Resolved',   'value' => $stats['resolved'],       'color' => 'emerald'],
    ];
    $colorMap = [
        'indigo'  => 'bg-indigo-50 text-indigo-700 ring-indigo-200',
        'slate'   => 'bg-slate-50 text-slate-700 ring-slate-200',
        'amber'   => 'bg-amber-50 text-amber-700 ring-amber-200',
        'blue'    => 'bg-blue-50 text-blue-700 ring-blue-200',
        'emerald' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
    ];
    @endphp
    @foreach($statCards as $card)
    <div class="bg-white rounded-xl shadow-sm ring-1 ring-slate-200 p-5">
        <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">{{ $card['label'] }}</p>
        <p class="mt-2 text-3xl font-bold {{ explode(' ', $colorMap[$card['color']])[1] }}">{{ $card['value'] }}</p>
    </div>
    @endforeach
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- Needs by category --}}
    <div class="bg-white rounded-xl shadow-sm ring-1 ring-slate-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100">
            <h2 class="text-base font-semibold text-slate-800">Needs by Category</h2>
        </div>
        <table class="min-w-full divide-y divide-slate-100">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Category</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Count</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($byCategory as $category => $count)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-3 text-sm text-slate-700 capitalize">{{ $category }}</td>
                    <td class="px-6 py-3 text-sm font-semibold text-slate-800 text-right">{{ $count }}</td>
                </tr>
                @empty
                <tr><td colspan="2" class="px-6 py-8 text-center text-sm text-slate-400">No needs recorded yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Top priority cases --}}
    <div class="bg-white rounded-xl shadow-sm ring-1 ring-slate-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h2 class="text-base font-semibold text-slate-800">Top Priority Cases</h2>
            <a href="{{ route('needs.index') }}" class="text-indigo-600 hover:text-indigo-700 text-xs font-medium">View all →</a>
        </div>
        <table class="min-w-full divide-y divide-slate-100">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Refugee</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Category</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Score</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($topPriority as $need)
                @php $s = $need->priority_score; @endphp
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-4 py-3 text-sm">
                        <a href="{{ route('needs.show', $need) }}" class="text-indigo-600 hover:text-indigo-700 font-medium">
                            {{ $need->refugee->name ?? '—' }}
                        </a>
                    </td>
                    <td class="px-4 py-3 text-sm text-slate-700 capitalize">{{ $need->category }}</td>
                    <td class="px-4 py-3 text-right">
                        <span class="text-sm font-bold {{ $s >= 200 ? 'text-red-600' : ($s >= 100 ? 'text-amber-600' : 'text-emerald-600') }}">
                            {{ $s }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        @php
                            $badgeClasses = ['pending'=>'bg-amber-100 text-amber-800','in_progress'=>'bg-blue-100 text-blue-800','resolved'=>'bg-emerald-100 text-emerald-800'];
                        @endphp
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $badgeClasses[$need->status] ?? 'bg-slate-100 text-slate-700' }}">
                            {{ str_replace('_', ' ', $need->status) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-4 py-8 text-center text-sm text-slate-400">No active needs.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
