@extends('layouts.app')

@section('title', 'Reports — Refugee Needs System')

@section('content')

<div class="mb-8">
    <h1 class="text-2xl font-bold text-slate-800">Reports & Analytics</h1>
    <p class="text-slate-500 text-sm mt-1">Statistical overview of refugee needs assessment</p>
</div>

{{-- Top stats --}}
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 mb-8">
    @php
    $statCards = [
        ['label'=>'Total Refugees',            'value'=>$totalRefugees,           'color'=>'indigo'],
        ['label'=>'Assessed',                  'value'=>$refugeesWithNeeds,        'color'=>'blue'],
        ['label'=>'Not Yet Assessed',          'value'=>$refugeesWithoutNeeds,     'color'=>'amber'],
        ['label'=>'Pending Needs',             'value'=>$needsByStatus['pending'] ?? 0, 'color'=>'red'],
        ['label'=>'Resolved Needs',            'value'=>$needsByStatus['resolved'] ?? 0,'color'=>'emerald'],
    ];
    $numColors = ['indigo'=>'text-indigo-700','blue'=>'text-blue-700','amber'=>'text-amber-700','red'=>'text-red-700','emerald'=>'text-emerald-700'];
    @endphp
    @foreach($statCards as $card)
    <div class="bg-white rounded-xl shadow-sm ring-1 ring-slate-200 p-5">
        <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">{{ $card['label'] }}</p>
        <p class="mt-2 text-3xl font-bold {{ $numColors[$card['color']] }}">{{ $card['value'] }}</p>
    </div>
    @endforeach
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

    {{-- By category --}}
    <div class="bg-white rounded-xl shadow-sm ring-1 ring-slate-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100">
            <h2 class="text-base font-semibold text-slate-800">Needs by Category</h2>
        </div>
        <table class="min-w-full divide-y divide-slate-100">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Category</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Count</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Avg Score</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($needsByCategory as $row)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-3 text-sm text-slate-700 capitalize font-medium">{{ $row->category }}</td>
                    <td class="px-6 py-3 text-sm text-slate-800 text-right font-semibold">{{ $row->total }}</td>
                    <td class="px-6 py-3 text-sm text-right">
                        @php $avg = round($row->avg_score, 1); @endphp
                        <span class="font-bold {{ $avg >= 200 ? 'text-red-600' : ($avg >= 100 ? 'text-amber-600' : 'text-emerald-600') }}">
                            {{ $avg }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="3" class="px-6 py-8 text-center text-sm text-slate-400">No data yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- By urgency --}}
    <div class="bg-white rounded-xl shadow-sm ring-1 ring-slate-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100">
            <h2 class="text-base font-semibold text-slate-800">Needs by Urgency Level</h2>
        </div>
        <table class="min-w-full divide-y divide-slate-100">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Level</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Label</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Count</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @php $urgencyLabels = [5=>'Critical',4=>'Very High',3=>'High',2=>'Moderate',1=>'Low']; @endphp
                @for($i = 5; $i >= 1; $i--)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-3">
                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-full text-xs font-bold
                            {{ $i >= 4 ? 'bg-red-100 text-red-700' : ($i >= 3 ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-600') }}">
                            {{ $i }}
                        </span>
                    </td>
                    <td class="px-6 py-3 text-sm text-slate-700">{{ $urgencyLabels[$i] }}</td>
                    <td class="px-6 py-3 text-sm font-semibold text-slate-800 text-right">{{ $needsByUrgency[$i] ?? 0 }}</td>
                </tr>
                @endfor
            </tbody>
        </table>
    </div>

</div>

{{-- Top 20 priority --}}
<div class="bg-white rounded-xl shadow-sm ring-1 ring-slate-200 overflow-hidden mb-6">
    <div class="px-6 py-4 border-b border-slate-100">
        <h2 class="text-base font-semibold text-slate-800">Top 20 Priority Cases <span class="font-normal text-slate-400 text-sm">(unresolved)</span></h2>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider w-10">#</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Refugee</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Category</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Urgency</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Score</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Recorded</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-slate-100">
                @php $badgeClasses = ['pending'=>'bg-amber-100 text-amber-800','in_progress'=>'bg-blue-100 text-blue-800','resolved'=>'bg-emerald-100 text-emerald-800']; @endphp
                @forelse($topPriorityCases as $i => $need)
                @php $s = $need->priority_score; @endphp
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-4 py-3 text-sm text-slate-500">{{ $i + 1 }}</td>
                    <td class="px-4 py-3 text-sm font-medium text-slate-800">{{ $need->refugee->name ?? '—' }}</td>
                    <td class="px-4 py-3 text-sm text-slate-600 capitalize">{{ $need->category }}</td>
                    <td class="px-4 py-3 text-center">
                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-full text-xs font-bold
                            {{ $need->urgency_level >= 4 ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700' }}">
                            {{ $need->urgency_level }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-right">
                        <span class="text-sm font-bold {{ $s >= 200 ? 'text-red-600' : ($s >= 100 ? 'text-amber-600' : 'text-emerald-600') }}">{{ $s }}</span>
                    </td>
                    <td class="px-4 py-3">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $badgeClasses[$need->status] ?? '' }}">
                            {{ str_replace('_',' ',$need->status) }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-sm text-slate-500">{{ $need->created_at->format('d M Y') }}</td>
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('needs.show', $need) }}" class="text-indigo-600 hover:text-indigo-700 text-xs font-medium">View →</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="px-4 py-10 text-center text-sm text-slate-400">All needs have been resolved.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Recent needs --}}
<div class="bg-white rounded-xl shadow-sm ring-1 ring-slate-200 overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-100">
        <h2 class="text-base font-semibold text-slate-800">Recently Recorded</h2>
    </div>
    <table class="min-w-full divide-y divide-slate-100">
        <thead class="bg-slate-50">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Refugee</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Category</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Recorded By</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Date</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @foreach($recentNeeds as $need)
            <tr class="hover:bg-slate-50 transition-colors">
                <td class="px-4 py-3 text-sm font-medium text-slate-800">{{ $need->refugee->name ?? '—' }}</td>
                <td class="px-4 py-3 text-sm text-slate-600 capitalize">{{ $need->category }}</td>
                <td class="px-4 py-3 text-sm text-slate-600">{{ $need->recorder->name ?? '—' }}</td>
                <td class="px-4 py-3 text-sm text-slate-500">{{ $need->created_at->format('d M Y, H:i') }}</td>
                <td class="px-4 py-3 text-right"><a href="{{ route('needs.show', $need) }}" class="text-indigo-600 hover:text-indigo-700 text-xs font-medium">View →</a></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection
