@extends('layouts.app')

@section('title', 'Register — Refugee Needs System')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center py-8">
    <div class="w-full max-w-lg">

        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-emerald-100 rounded-2xl mb-4">
                <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-slate-800">Create Your Account</h1>
            <p class="text-slate-500 text-sm mt-1">Register to access refugee support services</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm ring-1 ring-slate-200 p-8">

            {{-- Step 1: Registration form --}}
            <form id="registerForm" class="space-y-4">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700 mb-1.5">Full Name</label>
                    <input type="text" name="name" id="name" placeholder="Enter your full name" required
                        class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition">
                    <p class="error-message text-red-500 text-xs mt-1 hidden" id="error-name"></p>
                </div>

                <div>
                    <label for="phone_no" class="block text-sm font-medium text-slate-700 mb-1.5">Phone Number</label>
                    <input type="text" name="phone_no" id="phone_no" placeholder="e.g. 0712345678" required
                        class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition">
                    <p class="error-message text-red-500 text-xs mt-1 hidden" id="error-phone_no"></p>
                </div>

                <div>
                    <label for="date_of_birth" class="block text-sm font-medium text-slate-700 mb-1.5">Date of Birth</label>
                    <input type="date" name="date_of_birth" id="date_of_birth"
                        min="1900-01-01" max="{{ now()->subYears(18)->format('Y-m-d') }}" required
                        class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition">
                    <p class="error-message text-red-500 text-xs mt-1 hidden" id="error-date_of_birth"></p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="selectOrigin" class="block text-sm font-medium text-slate-700 mb-1.5">Country of Origin</label>
                        <select id="selectOrigin" name="country_of_origin" required
                            class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition">
                            <option value="">-- Select --</option>
                        </select>
                        <p class="error-message text-red-500 text-xs mt-1 hidden" id="error-country_of_origin"></p>
                    </div>
                    <div>
                        <label for="selectHost" class="block text-sm font-medium text-slate-700 mb-1.5">Host Country</label>
                        <select id="selectHost" name="host_country" required
                            class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition">
                            <option value="">-- Select --</option>
                        </select>
                        <p class="error-message text-red-500 text-xs mt-1 hidden" id="error-host_country"></p>
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700 mb-1.5">Password</label>
                    <div class="relative">
                        <input type="password" name="password" id="password" placeholder="Create a strong password" required
                            class="w-full border border-slate-300 rounded-lg px-3 py-2.5 pr-10 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition">
                        <button type="button" id="togglePassword"
                            class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-600">
                            <svg id="eyeIconReg" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                    {{-- Strength meter --}}
                    <div class="mt-2 space-y-1" id="strengthMeter">
                        <div class="flex gap-1">
                            <div class="strength-bar h-1 flex-1 rounded-full bg-slate-200" id="bar1"></div>
                            <div class="strength-bar h-1 flex-1 rounded-full bg-slate-200" id="bar2"></div>
                            <div class="strength-bar h-1 flex-1 rounded-full bg-slate-200" id="bar3"></div>
                            <div class="strength-bar h-1 flex-1 rounded-full bg-slate-200" id="bar4"></div>
                        </div>
                        <div class="flex items-center justify-between">
                            <p id="strengthLabel" class="text-xs text-slate-400"></p>
                            <ul class="text-xs text-slate-400 space-y-0.5 text-right" id="strengthHints">
                                <li id="hint-len"  class="hint-item">✗ At least 8 characters</li>
                                <li id="hint-upper" class="hint-item">✗ Uppercase letter</li>
                                <li id="hint-lower" class="hint-item">✗ Lowercase letter</li>
                                <li id="hint-num"   class="hint-item">✗ Number</li>
                                <li id="hint-sym"   class="hint-item">✗ Special character</li>
                            </ul>
                        </div>
                    </div>
                    <p class="error-message text-red-500 text-xs mt-1 hidden" id="error-password"></p>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-1.5">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Re-enter your password" required
                        class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition">
                    <p class="error-message text-red-500 text-xs mt-1 hidden" id="error-password_confirmation"></p>
                </div>

                <button type="submit"
                    class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-2.5 rounded-lg text-sm transition-colors shadow-sm mt-2">
                    Register
                </button>
                <p class="error-message text-red-500 text-xs text-center hidden" id="error-general"></p>
            </form>

            {{-- Step 2: OTP --}}
            <div id="otpSection" class="hidden space-y-5">
                <div class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg px-4 py-3 text-sm">
                    <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    A 6-digit code has been sent to your phone number.
                </div>

                <div id="dev-otp-notice" class="hidden bg-amber-50 border border-amber-300 text-amber-800 rounded-lg px-4 py-2 text-xs font-mono"></div>

                <div>
                    <label for="otp" class="block text-sm font-medium text-slate-700 mb-1.5">Verification Code</label>
                    <input type="text" id="otp" name="otp" placeholder="Enter 6-digit code" maxlength="6" inputmode="numeric" pattern="\d{6}"
                        class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-center text-lg font-mono tracking-widest focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition">
                    <p class="error-message text-red-500 text-xs mt-1 hidden" id="error-otp"></p>
                </div>

                <button type="button" id="verifyOtpBtn"
                    class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-2.5 rounded-lg text-sm transition-colors shadow-sm">
                    Verify & Complete Registration
                </button>

                <div class="flex items-center justify-between">
                    <button type="button" id="resendOtpBtn" disabled
                        class="text-sm text-emerald-600 hover:text-emerald-700 disabled:text-slate-400 disabled:cursor-not-allowed font-medium transition-colors">
                        Resend Code
                    </button>
                    <span id="resendCountdown" class="text-xs text-slate-500"></span>
                </div>
            </div>

        </div>

        <p class="text-center text-sm text-slate-500 mt-6">
            Already have an account?
            <a href="{{ route('login.view') }}" class="text-emerald-600 hover:text-emerald-700 font-medium">Login here</a>
        </p>

    </div>
</div>

@push('script')
<script>
    const csrfToken    = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const countriesUrl = "{{ route('register.countries') }}";
    const storeUrl     = "{{ route('register.store') }}";
    const dashboardUrl = "{{ route('dashboard') }}";
    const otpUrl       = "{{ route('registration-otp') }}";
    const resendOtpUrl = "{{ route('register.resend-otp') }}";

    // Password show/hide
    document.getElementById('togglePassword').addEventListener('click', function() {
        const input = document.getElementById('password');
        const isText = input.type === 'text';
        input.type = isText ? 'password' : 'text';
        document.getElementById('eyeIconReg').innerHTML = isText
            ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>'
            : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>';
    });

    // Password strength meter
    const bars   = [document.getElementById('bar1'), document.getElementById('bar2'), document.getElementById('bar3'), document.getElementById('bar4')];
    const levels = ['', 'text-red-500', 'text-amber-500', 'text-blue-500', 'text-emerald-500'];
    const labels = ['', 'Weak', 'Fair', 'Good', 'Strong'];
    const barColors = ['', 'bg-red-400', 'bg-amber-400', 'bg-blue-400', 'bg-emerald-500'];

    function checkHint(id, passes) {
        const el = document.getElementById(id);
        el.textContent = (passes ? '✓ ' : '✗ ') + el.textContent.slice(2);
        el.className = passes ? 'hint-item text-emerald-600 font-medium' : 'hint-item text-slate-400';
    }

    document.getElementById('password').addEventListener('input', function() {
        const v = this.value;
        const checks = {
            len:   v.length >= 8,
            upper: /[A-Z]/.test(v),
            lower: /[a-z]/.test(v),
            num:   /\d/.test(v),
            sym:   /[^A-Za-z0-9]/.test(v),
        };
        Object.entries(checks).forEach(([k, ok]) => checkHint('hint-' + k, ok));
        const score = Object.values(checks).filter(Boolean).length;
        bars.forEach((b, i) => {
            b.className = 'strength-bar h-1 flex-1 rounded-full ' + (i < score ? barColors[score] : 'bg-slate-200');
        });
        const label = document.getElementById('strengthLabel');
        label.textContent = v.length ? labels[score] || labels[4] : '';
        label.className = 'text-xs ' + (levels[score] || '');
    });
</script>
<script src="{{ asset('assets/js/register.js') }}"></script>
@endpush
@endsection
