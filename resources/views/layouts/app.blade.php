<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Refugee Needs System')</title>
    @vite('resources/css/app.css')
</head>
<body class="h-full font-sans antialiased">

@if(Auth::check() || session()->has('refugee_id'))
<nav class="bg-slate-900 shadow-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">

            <div class="flex items-center gap-8">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2 text-white font-bold text-lg tracking-tight">
                    <svg class="w-7 h-7 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064"/>
                    </svg>
                    RNS
                </a>

                <div class="hidden sm:flex items-center gap-1">
                    @auth
                        <a href="{{ route('dashboard') }}"
                           class="text-slate-300 hover:text-white hover:bg-slate-700 px-3 py-2 rounded-md text-sm font-medium transition-colors
                                  {{ request()->routeIs('dashboard') ? 'bg-slate-700 text-white' : '' }}">
                            Dashboard
                        </a>
                        @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('aid_worker'))
                            <a href="{{ route('needs.index') }}"
                               class="text-slate-300 hover:text-white hover:bg-slate-700 px-3 py-2 rounded-md text-sm font-medium transition-colors
                                      {{ request()->routeIs('needs.*') ? 'bg-slate-700 text-white' : '' }}">
                                Needs
                            </a>
                            <a href="{{ route('reports.index') }}"
                               class="text-slate-300 hover:text-white hover:bg-slate-700 px-3 py-2 rounded-md text-sm font-medium transition-colors
                                      {{ request()->routeIs('reports.*') ? 'bg-slate-700 text-white' : '' }}">
                                Reports
                            </a>
                        @endif
                        @if(Auth::user()->hasRole('admin'))
                            <a href="{{ route('audit-logs.index') }}"
                               class="text-slate-300 hover:text-white hover:bg-slate-700 px-3 py-2 rounded-md text-sm font-medium transition-colors
                                      {{ request()->routeIs('audit-logs.*') ? 'bg-slate-700 text-white' : '' }}">
                                Audit Logs
                            </a>
                            <a href="{{ route('admin.users.index') }}"
                               class="text-slate-300 hover:text-white hover:bg-slate-700 px-3 py-2 rounded-md text-sm font-medium transition-colors
                                      {{ request()->routeIs('admin.users.*') ? 'bg-slate-700 text-white' : '' }}">
                                Users
                            </a>
                        @endif
                    @endauth
                </div>
            </div>

            @auth
            <div class="flex items-center gap-3">
                <div class="hidden sm:flex flex-col items-end">
                    <span class="text-white text-sm font-medium leading-tight">{{ Auth::user()->name }}</span>
                    <span class="text-slate-400 text-xs capitalize">{{ Auth::user()->roles->first()?->name ?? 'staff' }}</span>
                </div>
                <form method="POST" action="{{ route('staff.logout') }}">
                    @csrf
                    <button type="submit"
                        class="flex items-center gap-1.5 text-slate-400 hover:text-white text-sm px-3 py-1.5 rounded-md border border-slate-600 hover:border-slate-400 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
            @endauth
        </div>
    </div>
</nav>
@endif

<main class="min-h-screen bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        @if(session('success'))
        <div class="mb-6 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl px-4 py-3 text-sm shadow-sm">
            <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="mb-6 flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 rounded-xl px-4 py-3 text-sm shadow-sm">
            <svg class="w-5 h-5 text-red-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-9h2v4H9V9zm0-4h2v2H9V5z" clip-rule="evenodd"/>
            </svg>
            {{ session('error') }}
        </div>
        @endif

        @yield('content')
    </div>
</main>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@stack('script')
</body>
</html>
