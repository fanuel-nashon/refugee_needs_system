@extends('layouts.app')

@section('title', 'Edit Need — Refugee Needs System')

@section('content')

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Edit Need</h1>
        <p class="text-slate-500 text-sm mt-1">Priority score will be recalculated on save</p>
    </div>
    <a href="{{ route('needs.show', $need) }}"
       class="inline-flex items-center gap-2 bg-white hover:bg-slate-50 text-slate-700 text-sm font-medium px-4 py-2 rounded-lg ring-1 ring-slate-300 transition-colors">
        ← Cancel
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <div class="lg:col-span-2">
        <form method="POST" action="{{ route('needs.update', $need) }}" id="editNeedForm"
              class="bg-white rounded-xl shadow-sm ring-1 ring-slate-200 p-6 space-y-5">
            @csrf @method('PUT')

            <div class="bg-slate-50 rounded-lg px-4 py-3">
                <p class="text-xs text-slate-500 font-medium">Refugee</p>
                <p class="text-sm text-slate-800 font-semibold mt-0.5">{{ $need->refugee->name }} — {{ $need->refugee->phone_no }}</p>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="category" class="block text-sm font-medium text-slate-700 mb-1.5">Category *</label>
                    <select id="category" name="category" required
                        class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        @foreach(\App\Models\Need::CATEGORIES as $cat)
                            <option value="{{ $cat }}" {{ old('category', $need->category) == $cat ? 'selected' : '' }}>{{ ucfirst($cat) }}</option>
                        @endforeach
                    </select>
                    @error('category') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="urgency_level" class="block text-sm font-medium text-slate-700 mb-1.5">Urgency Level *</label>
                    <select id="urgency_level" name="urgency_level" required
                        class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        @for($i = 1; $i <= 5; $i++)
                            <option value="{{ $i }}" {{ old('urgency_level', $need->urgency_level) == $i ? 'selected' : '' }}>
                                {{ $i }} — {{ ['', 'Low', 'Moderate', 'High', 'Very High', 'Critical'][$i] }}
                            </option>
                        @endfor
                    </select>
                    @error('urgency_level') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-slate-700 mb-1.5">Description *</label>
                <textarea id="description" name="description" required maxlength="1000" rows="4"
                    class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 resize-none">{{ old('description', $need->description) }}</textarea>
                @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="family_size" class="block text-sm font-medium text-slate-700 mb-1.5">Family Size *</label>
                    <input type="number" id="family_size" name="family_size" min="1" value="{{ old('family_size', $need->family_size) }}" required
                        class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    @error('family_size') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-slate-700 mb-1.5">Status *</label>
                    <select id="status" name="status" required
                        class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        @foreach(\App\Models\Need::STATUSES as $st)
                            <option value="{{ $st }}" {{ old('status', $need->status) == $st ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_', ' ', $st)) }}
                            </option>
                        @endforeach
                    </select>
                    @error('status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <button type="submit"
                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 rounded-lg text-sm transition-colors shadow-sm">
                Update Need
            </button>
        </form>
    </div>

    <div class="space-y-4">
        <div class="bg-white rounded-xl shadow-sm ring-1 ring-slate-200 p-6">
            <h2 class="text-sm font-semibold text-slate-800 mb-4">Vulnerability Indicators</h2>
            <div class="space-y-3">
                @foreach([
                    ['has_disability',     'Has a disability',              '+15 pts', $need->has_disability],
                    ['is_pregnant',        'Is pregnant',                   '+20 pts', $need->is_pregnant],
                    ['has_critical_health','Has a critical health condition','+30 pts', $need->has_critical_health],
                ] as [$field, $label, $bonus, $checked])
                <label class="flex items-start gap-3 p-3 rounded-lg border border-slate-200 hover:bg-slate-50 cursor-pointer transition-colors">
                    <input type="checkbox" name="{{ $field }}" form="editNeedForm" value="1" {{ old($field, $checked) ? 'checked' : '' }}
                        class="mt-0.5 w-4 h-4 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500">
                    <div>
                        <span class="text-sm text-slate-700 font-medium">{{ $label }}</span>
                        <span class="block text-xs text-indigo-600 font-semibold mt-0.5">{{ $bonus }}</span>
                    </div>
                </label>
                @endforeach
            </div>
        </div>

        @php $s = $need->priority_score; @endphp
        <div class="bg-white rounded-xl shadow-sm ring-1 ring-slate-200 p-5 text-center">
            <p class="text-xs text-slate-500 mb-1">Current Score</p>
            <p class="text-4xl font-extrabold {{ $s >= 200 ? 'text-red-600' : ($s >= 100 ? 'text-amber-600' : 'text-emerald-600') }}">{{ $s }}</p>
            <p class="text-xs text-slate-400 mt-1">Will recalculate on save</p>
        </div>
    </div>

</div>
@endsection
