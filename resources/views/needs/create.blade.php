@extends('layouts.app')

@section('title', 'Record Need — Refugee Needs System')

@section('content')

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Record a Need</h1>
        <p class="text-slate-500 text-sm mt-1">The priority score is calculated automatically</p>
    </div>
    <a href="{{ route('needs.index') }}"
       class="inline-flex items-center gap-2 bg-white hover:bg-slate-50 text-slate-700 text-sm font-medium px-4 py-2 rounded-lg ring-1 ring-slate-300 transition-colors">
        ← Back
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Main form --}}
    <div class="lg:col-span-2">
        <form method="POST" action="{{ route('needs.store') }}" class="bg-white rounded-xl shadow-sm ring-1 ring-slate-200 p-6 space-y-5">
            @csrf

            <div>
                <label for="refugee_id" class="block text-sm font-medium text-slate-700 mb-1.5">Refugee *</label>
                <select id="refugee_id" name="refugee_id" required
                    class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    <option value="">— Select Refugee —</option>
                    @foreach($refugees as $refugee)
                        <option value="{{ $refugee->id }}" {{ old('refugee_id') == $refugee->id ? 'selected' : '' }}>
                            {{ $refugee->name }} — {{ $refugee->phone_no }}
                        </option>
                    @endforeach
                </select>
                @error('refugee_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="category" class="block text-sm font-medium text-slate-700 mb-1.5">Category *</label>
                    <select id="category" name="category" required
                        class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="">— Select —</option>
                        @foreach(\App\Models\Need::CATEGORIES as $cat)
                            <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>{{ ucfirst($cat) }}</option>
                        @endforeach
                    </select>
                    @error('category') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="urgency_level" class="block text-sm font-medium text-slate-700 mb-1.5">Urgency Level * <span class="font-normal text-slate-400">(1=Low, 5=Critical)</span></label>
                    <select id="urgency_level" name="urgency_level" required
                        class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="">— Select —</option>
                        @for($i = 1; $i <= 5; $i++)
                            <option value="{{ $i }}" {{ old('urgency_level') == $i ? 'selected' : '' }}>
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
                    placeholder="Describe the nature and context of this need..."
                    class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent resize-none">{{ old('description') }}</textarea>
                @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="family_size" class="block text-sm font-medium text-slate-700 mb-1.5">Family Size *</label>
                <input type="number" id="family_size" name="family_size" min="1" value="{{ old('family_size', 1) }}" required
                    class="w-32 border border-slate-300 rounded-lg px-3 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                @error('family_size') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <button type="submit"
                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 rounded-lg text-sm transition-colors shadow-sm">
                Save Need & Calculate Priority Score
            </button>
        </form>
    </div>

    {{-- Vulnerability sidebar --}}
    <div class="space-y-4">
        <div class="bg-white rounded-xl shadow-sm ring-1 ring-slate-200 p-6">
            <h2 class="text-sm font-semibold text-slate-800 mb-4">Vulnerability Indicators</h2>
            <p class="text-xs text-slate-500 mb-4">Each checked indicator increases the priority score.</p>

            <div class="space-y-3">
                @foreach([
                    ['has_disability',     'Has a disability',              '+15 pts'],
                    ['is_pregnant',        'Is pregnant',                   '+20 pts'],
                    ['has_critical_health','Has a critical health condition','+30 pts'],
                ] as [$field, $label, $bonus])
                <label class="flex items-start gap-3 p-3 rounded-lg border border-slate-200 hover:bg-slate-50 cursor-pointer transition-colors">
                    <input type="checkbox" name="{{ $field }}" value="1" {{ old($field) ? 'checked' : '' }}
                        form="needForm"
                        class="mt-0.5 w-4 h-4 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500">
                    <div>
                        <span class="text-sm text-slate-700 font-medium">{{ $label }}</span>
                        <span class="block text-xs text-indigo-600 font-semibold mt-0.5">{{ $bonus }}</span>
                    </div>
                </label>
                @endforeach
            </div>
        </div>

        <div class="bg-indigo-50 rounded-xl ring-1 ring-indigo-200 p-5">
            <h3 class="text-sm font-semibold text-indigo-800 mb-2">Scoring Formula</h3>
            <p class="text-xs text-indigo-700 leading-relaxed">
                <strong>(Urgency × 20 + Vulnerability bonus) × Category weight</strong><br><br>
                Category weights: Healthcare 1.5× · Protection 1.4× · Food 1.3× · Shelter 1.2× · Education 1.0×<br><br>
                Age bonus: Children &lt;5 or elderly &gt;65 add +20 pts.<br>
                Family size &gt;5 adds +10 pts.<br><br>
                Max possible score: <strong>292.50</strong>
            </p>
        </div>
    </div>

</div>

@push('script')
<script>
    // move checkboxes into the main form
    document.querySelectorAll('input[form="needForm"]').forEach(el => {
        el.removeAttribute('form');
        document.querySelector('form').appendChild(el);
    });
</script>
@endpush
@endsection
