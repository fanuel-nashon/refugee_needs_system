@extends('layouts.app')

@section('title', 'Request a Need — Refugee Needs System')

@section('content')

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Request a Need</h1>
        <p class="text-slate-500 text-sm mt-1">Describe what you need and an aid worker will review your request</p>
    </div>
    <a href="{{ route('refugee.home') }}"
       class="inline-flex items-center gap-2 bg-white hover:bg-slate-50 text-slate-700 text-sm font-medium px-4 py-2 rounded-lg ring-1 ring-slate-300 transition-colors">
        ← Back
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Main form --}}
    <div class="lg:col-span-2">
        <form method="POST" action="{{ route('refugee.needs.store') }}"
              class="bg-white rounded-xl shadow-sm ring-1 ring-slate-200 p-6 space-y-5">
            @csrf

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="category" class="block text-sm font-medium text-slate-700 mb-1.5">Category *</label>
                    <select id="category" name="category" required
                        class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 @error('category') border-red-400 @enderror">
                        <option value="">-- Select --</option>
                        @foreach(\App\Models\Need::CATEGORIES as $cat)
                            <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>
                                {{ ucfirst($cat) }}
                            </option>
                        @endforeach
                    </select>
                    @error('category') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="urgency_level" class="block text-sm font-medium text-slate-700 mb-1.5">Urgency Level *</label>
                    <select id="urgency_level" name="urgency_level" required
                        class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 @error('urgency_level') border-red-400 @enderror">
                        <option value="">-- Select --</option>
                        @foreach([1=>'Low', 2=>'Moderate', 3=>'High', 4=>'Very High', 5=>'Critical'] as $val => $label)
                            <option value="{{ $val }}" {{ old('urgency_level') == $val ? 'selected' : '' }}>
                                {{ $val }} — {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('urgency_level') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-slate-700 mb-1.5">Description *</label>
                <textarea id="description" name="description" required maxlength="1000" rows="4"
                    placeholder="Describe your need in detail…"
                    class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 resize-none @error('description') border-red-400 @enderror">{{ old('description') }}</textarea>
                @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="family_size" class="block text-sm font-medium text-slate-700 mb-1.5">Number of people in your household *</label>
                <input type="number" id="family_size" name="family_size" min="1" max="50"
                    value="{{ old('family_size', 1) }}" required
                    class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 @error('family_size') border-red-400 @enderror">
                @error('family_size') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <button type="submit"
                class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-2.5 rounded-lg text-sm transition-colors shadow-sm">
                Submit Request
            </button>
        </form>
    </div>

    {{-- Vulnerability sidebar --}}
    <div class="space-y-4">
        <div class="bg-white rounded-xl shadow-sm ring-1 ring-slate-200 p-6">
            <h2 class="text-sm font-semibold text-slate-800 mb-1">Vulnerability Information</h2>
            <p class="text-xs text-slate-500 mb-4">These factors help prioritise your request. Tick all that apply.</p>

            <div class="space-y-3">
                @foreach([
                    ['has_disability',     'I have a disability'],
                    ['is_pregnant',        'I am pregnant'],
                    ['has_critical_health','I have a critical health condition'],
                ] as [$field, $label])
                <label class="flex items-start gap-3 p-3 rounded-lg border border-slate-200 hover:bg-slate-50 cursor-pointer transition-colors">
                    <input type="checkbox" name="{{ $field }}" value="1"
                        {{ old($field) ? 'checked' : '' }}
                        class="mt-0.5 w-4 h-4 text-emerald-600 border-slate-300 rounded focus:ring-emerald-500">
                    <span class="text-sm text-slate-700">{{ $label }}</span>
                </label>
                @endforeach
            </div>
        </div>

        <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 text-sm text-emerald-800">
            <p class="font-semibold mb-1">What happens next?</p>
            <p class="text-xs text-emerald-700 leading-relaxed">
                After you submit, an aid worker will review your request and assign it a priority score. You can track the status from your home page.
            </p>
        </div>
    </div>

</div>
@endsection
