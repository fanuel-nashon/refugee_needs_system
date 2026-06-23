@extends('layouts.app')

@section('title', 'Audit Logs — Refugee Needs System')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-bold text-slate-800">Audit Trail</h1>
    <p class="text-slate-500 text-sm mt-1">All system actions are recorded here for accountability</p>
</div>

{{-- Filters --}}
<form method="GET" class="bg-white rounded-xl shadow-sm ring-1 ring-slate-200 p-4 mb-6 flex flex-wrap gap-3 items-end">
    <div>
        <label class="block text-xs font-medium text-slate-600 mb-1">Event</label>
        <select name="event" class="border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <option value="">All Events</option>
            @foreach(['created','updated','deleted','login','logout'] as $ev)
                <option value="{{ $ev }}" {{ request('event') == $ev ? 'selected' : '' }}>{{ ucfirst($ev) }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-xs font-medium text-slate-600 mb-1">Record Type</label>
        <select name="auditable_type" class="border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <option value="">All Types</option>
            @foreach(['Need','Refugee','User'] as $t)
                <option value="{{ $t }}" {{ request('auditable_type') == $t ? 'selected' : '' }}>{{ $t }}</option>
            @endforeach
        </select>
    </div>
    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">Filter</button>
    <a href="{{ route('audit-logs.index') }}" class="bg-white hover:bg-slate-50 text-slate-700 text-sm font-medium px-4 py-2 rounded-lg ring-1 ring-slate-300 transition-colors">Clear</a>
</form>

<div class="bg-white rounded-xl shadow-sm ring-1 ring-slate-200 overflow-hidden">
    <table class="min-w-full divide-y divide-slate-200">
        <thead class="bg-slate-50">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Time</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Event</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Record Type</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Record ID</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Performed By</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">IP Address</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-slate-100">
            @forelse($logs as $log)
            <tr class="hover:bg-slate-50 transition-colors">
                <td class="px-4 py-3 text-xs text-slate-500 whitespace-nowrap">
                    {{ $log->created_at->format('d M Y') }}<br>
                    <span class="font-mono">{{ $log->created_at->format('H:i:s') }}</span>
                </td>
                <td class="px-4 py-3">
                    @php
                        $evClass = match($log->event) {
                            'created' => 'bg-emerald-100 text-emerald-800',
                            'updated' => 'bg-blue-100 text-blue-800',
                            'deleted' => 'bg-red-100 text-red-800',
                            'login'   => 'bg-indigo-100 text-indigo-800',
                            'logout'  => 'bg-slate-100 text-slate-700',
                            default   => 'bg-slate-100 text-slate-700',
                        };
                    @endphp
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $evClass }}">
                        {{ ucfirst($log->event) }}
                    </span>
                </td>
                <td class="px-4 py-3 text-sm text-slate-700">{{ $log->auditable_type ?? '—' }}</td>
                <td class="px-4 py-3 text-sm font-mono text-slate-500">{{ $log->auditable_id ?? '—' }}</td>
                <td class="px-4 py-3 text-sm text-slate-700">{{ $log->performer->name ?? 'System' }}</td>
                <td class="px-4 py-3 text-xs font-mono text-slate-500">{{ $log->ip_address ?? '—' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-4 py-12 text-center text-sm text-slate-400">No audit records found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($logs->hasPages())
<div class="mt-4">{{ $logs->links() }}</div>
@endif

@endsection
