@extends('layouts.app')

@section('title', 'Login — Refugee Needs System')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center">
    <div class="w-full max-w-md">

        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-2xl mb-4">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-slate-800">Welcome Back</h1>
            <p class="text-slate-500 text-sm mt-1">Sign in with your phone number and password</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm ring-1 ring-slate-200 p-8">

            <form id="loginForm" class="space-y-5">
                @csrf

                <div>
                    <label for="phone_no" class="block text-sm font-medium text-slate-700 mb-1.5">Phone Number</label>
                    <input type="text" id="phone_no" name="phone_no" placeholder="e.g. 0712345678" required autofocus
                        class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    <p class="error-message text-red-500 text-xs mt-1 hidden" id="error-phone_no"></p>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700 mb-1.5">Password</label>
                    <div class="relative">
                        <input type="password" id="password" name="password" placeholder="Enter your password" required
                            class="w-full border border-slate-300 rounded-lg px-3 py-2.5 pr-10 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                        <button type="button" id="togglePassword"
                            class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-600">
                            <svg id="eyeIcon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                    <p class="error-message text-red-500 text-xs mt-1 hidden" id="error-password"></p>
                </div>

                <button type="submit" id="submitBtn"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-lg text-sm transition-colors shadow-sm">
                    Sign In
                </button>

                <p class="error-message text-red-500 text-xs text-center hidden" id="error-general"></p>
            </form>

        </div>

        <p class="text-center text-sm text-slate-500 mt-6">
            Don't have an account?
            <a href="{{ route('register.create') }}" class="text-blue-600 hover:text-blue-700 font-medium">Register here</a>
        </p>
        <p class="text-center text-sm text-slate-500 mt-2">
            Staff member?
            <a href="{{ route('staff.login') }}" class="text-slate-600 hover:text-slate-700 font-medium">Staff login</a>
        </p>

    </div>
</div>

@push('script')
<script>
    const csrfToken  = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const loginUrl   = "{{ route('login') }}";
    const dashUrl    = "{{ route('dashboard') }}";

    function showError(id, msg) {
        const el = document.getElementById(id);
        el.textContent = msg;
        el.classList.remove('hidden');
    }
    function clearErrors() {
        document.querySelectorAll('.error-message').forEach(el => {
            el.textContent = '';
            el.classList.add('hidden');
        });
    }

    // Password visibility toggle
    document.getElementById('togglePassword').addEventListener('click', function() {
        const input = document.getElementById('password');
        const isText = input.type === 'text';
        input.type = isText ? 'password' : 'text';
        document.getElementById('eyeIcon').innerHTML = isText
            ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>'
            : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>';
    });

    // Login submit
    $('#loginForm').on('submit', function(e) {
        e.preventDefault();
        clearErrors();
        const $btn = $('#submitBtn');
        $btn.prop('disabled', true).text('Signing in...');

        $.ajax({
            url: loginUrl,
            method: 'POST',
            data: {
                _token:   csrfToken,
                phone_no: $('#phone_no').val(),
                password: $('#password').val(),
            },
            success: function(response) {
                if (response.status === 'success') {
                    window.location.href = dashUrl;
                }
            },
            error: function(xhr) {
                $btn.prop('disabled', false).text('Sign In');
                const data = xhr.responseJSON || {};
                if (xhr.status === 422) {
                    if (data.errors) {
                        Object.entries(data.errors).forEach(([field, msgs]) => {
                            showError('error-' + field, msgs[0]);
                        });
                    }
                } else {
                    showError('error-general', data.message || 'Invalid phone number or password.');
                }
            }
        });
    });
</script>
@endpush
@endsection
