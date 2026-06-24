<?php

namespace App\Http\Controllers;

use App\Models\Need;
use App\Models\Refugee;
use App\Services\PriorityService;
use Illuminate\Http\Request;

class RefugeeController extends Controller
{
    public function home()
    {
        $refugee = Refugee::findOrFail(session('refugee_id'));

        $needs = Need::where('refugee_id', $refugee->id)
            ->latest()
            ->get();

        return view('refugee.home', compact('refugee', 'needs'));
    }

    public function createNeed()
    {
        return view('refugee.request-need');
    }

    public function storeNeed(Request $request, PriorityService $priorityService)
    {
        $validated = $request->validate([
            'category'            => 'required|in:' . implode(',', Need::CATEGORIES),
            'description'         => 'required|string|max:1000',
            'urgency_level'       => 'required|integer|between:1,5',
            'has_disability'      => 'boolean',
            'is_pregnant'         => 'boolean',
            'has_critical_health' => 'boolean',
            'family_size'         => 'required|integer|min:1',
        ]);

        $refugee = Refugee::findOrFail(session('refugee_id'));

        $validated['has_disability']      = $request->boolean('has_disability');
        $validated['is_pregnant']         = $request->boolean('is_pregnant');
        $validated['has_critical_health'] = $request->boolean('has_critical_health');

        $validated['refugee_id']    = $refugee->id;
        $validated['recorded_by']   = null;
        $validated['status']        = 'pending';
        $validated['priority_score'] = $priorityService->calculate($validated, $refugee);

        Need::create($validated);

        return redirect()->route('refugee.home')
            ->with('success', 'Your need has been submitted. An aid worker will review it shortly.');
    }

    public function logout(Request $request)
    {
        $request->session()->forget(['refugee_id', 'refugee_phone', 'refugee_name']);
        $request->session()->regenerate();

        return redirect()->route('login')->with('success', 'You have been logged out.');
    }
}
