<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRefugeeAuthenticated
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!session()->has('refugee_id')) {
            return redirect()->route('login')
                ->with('error', 'Please log in to continue.');
        }

        return $next($request);
    }
}
