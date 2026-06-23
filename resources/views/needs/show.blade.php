@extends('layouts.app')

@section('title', 'Need Detail — Refugee Needs System')

@section('content')

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Need Detail</h1>
        <p class="text-slate-500 text-sm mt-1">Recorded {{ $need->created_at->format('d M Y, H:i') }}</p>
    </div>
    <a href="{{ route('needs.index') }}"
       class="inline-flex items-center gap-2 bg-white hover:bg-slate-50 text-slate-700 text-sm font-medium px-4 py-2 rounded-lg ring-1 ring-slate-300 transition-colors">
        ← Back to Needs
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Main details --}}
    <div class="lg:col-span-2 space-y-5">

        <div class="bg-white rounded-xl shadow-sm ring-1 ring-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50">
                <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wide">Need Information</h2>
            </div>
            <dl class="divide-y divide-slate-100">
                @foreach([
                    ['Refugee',        $need->refugee->name . ' (' . $need->refugee->phone_no . ')'],
                    ['Category',       ucfirst($need->category)],
                    ['Description',    $need->description],
                    ['Urgency Level',  $need->urgency_level . ' / 5'],
                    ['Family Size',    $need->family_size],
                    ['Recorded By',    $need->recorder->name ?? '—'],
                ] as [$key, $val])
                <div class="px-6 py-3.5 flex gap-4">
                    <dt class="w-36 shrink-0 text-sm font-medium text-slate-500">{{ $key }}</dt>
                    <dd class="text-sm text-slate-800">{{ $val }}</dd>
                </div>
                @endforeach
            </dl>
        </div>

        <div class="bg-white rounded-xl shadow-sm ring-1 ring-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50">
                <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wide">Vulnerability Indicators</h2>
            </div>
            <div class="px-6 py-4 grid grid-cols-3 gap-4">
                @foreach([
                    ['Disability',       $need->has_disability],
                    ['Pregnant',         $need->is_pregnant],
                    ['Critical Health',  $need->has_critical_health],
                ] as [$label, $val])
                <div class="flex items-center gap-2">
                    <span class="w-5 h-5 rounded-full flex items-center justify-center {{ $val ? 'bg-red-100' : 'bg-slate-100' }}">
                        @if($val)
                            <svg class="w-3 h-3 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        @else
                            <svg class="w-3 h-3 text-slate-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        @endif
                    </span>
                    <span class="text-sm {{ $val ? 'text-red-700 font-medium' : 'text-slate-500' }}">{{ $label }}</span>
                </div>
                @endforeach
            </div>
        </div>

    </div>

    {{-- Sidebar --}}
    <div class="space-y-4">

        {{-- Priority Score --}}
        @php $s = $need->priority_score; @endphp
        <div class="bg-white rounded-xl shadow-sm ring-1 ring-slate-200 p-6 text-center">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">Priority Score</p>
            <p class="text-5xl font-extrabold {{ $s >= 200 ? 'text-red-600' : ($s >= 100 ? 'text-amber-600' : 'text-emerald-600') }}">
                {{ $s }}
            </p>
            <p class="text-xs text-slate-400 mt-1">out of 292.50 max</p>
            <div class="mt-3 w-full bg-slate-100 rounded-full h-2">
                <div class="h-2 rounded-full {{ $s >= 200 ? 'bg-red-500' : ($s >= 100 ? 'bg-amber-500' : 'bg-emerald-500') }}"
                     style="width: {{ min(100, ($s / 292.5) * 100) }}%"></div>
            </div>
        </div>

        {{-- Status --}}
        @php $badgeClasses = ['pending'=>'bg-amber-100 text-amber-800','in_progress'=>'bg-blue-100 text-blue-800','resolved'=>'bg-emerald-100 text-emerald-800']; @endphp
        <div class="bg-white rounded-xl shadow-sm ring-1 ring-slate-200 p-5">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-3">Status</p>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold {{ $badgeClasses[$need->status] ?? 'bg-slate-100 text-slate-700' }}">
                {{ ucfirst(str_replace('_', ' ', $need->status)) }}
            </span>
        </div>

        {{-- Actions --}}
        @auth
            @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('aid_worker'))
            <div class="space-y-2">
                <a href="{{ route('needs.edit', $need) }}"
                   class="flex items-center justify-center gap-2 w-full bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold py-2.5 rounded-lg transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit Need
                </a>
                <form method="POST" action="{{ route('needs.destroy', $need) }}"
                      onsubmit="return confirm('Permanently delete this need?');">
                    @csrf @method('DELETE')
                    <button type="submit"
                        class="flex items-center justify-center gap-2 w-full bg-white hover:bg-red-50 text-red-600 text-sm font-semibold py-2.5 rounded-lg ring-1 ring-red-300 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Delete
                    </button>
                </form>
            </div>
            @endif
        @endauth

    </div>

</div>
@endsection
