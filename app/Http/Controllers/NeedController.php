<?php

namespace App\Http\Controllers;

use App\Models\Need;
use App\Models\Refugee;
use App\Services\AuditLogService;
use App\Services\PriorityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NeedController extends Controller
{
    public function index(Request $request)
    {
        $query = Need::with(['refugee', 'recorder'])
            ->orderByDesc('priority_score');

        if (Auth::user()->hasRole('aid_worker')) {
            $query->where('recorded_by', Auth::id());
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $needs = $query->paginate(20)->withQueryString();

        return view('needs.index', compact('needs'));
    }

    public function create()
    {
        $refugees = Refugee::orderBy('name')->get();
        return view('needs.create', compact('refugees'));
    }

    public function store(Request $request, PriorityService $priority, AuditLogService $audit)
    {
        $data = $request->validate([
            'refugee_id'         => 'required|exists:refugees,id',
            'category'           => 'required|in:food,shelter,healthcare,education,protection',
            'description'        => 'required|string|max:1000',
            'urgency_level'      => 'required|integer|min:1|max:5',
            'has_disability'     => 'boolean',
            'is_pregnant'        => 'boolean',
            'has_critical_health' => 'boolean',
            'family_size'        => 'required|integer|min:1',
        ]);

        $refugee = Refugee::findOrFail($data['refugee_id']);

        $data['has_disability']      = $request->boolean('has_disability');
        $data['is_pregnant']         = $request->boolean('is_pregnant');
        $data['has_critical_health'] = $request->boolean('has_critical_health');
        $data['recorded_by']         = Auth::id();
        $data['priority_score']      = $priority->calculate($data, $refugee);
        $data['status']              = 'pending';

        $need = Need::create($data);

        $audit->log('created', 'Need', $need->id, [], $need->toArray());

        return redirect()->route('needs.show', $need)
            ->with('success', 'Need recorded. Priority score: ' . $need->priority_score);
    }

    public function show(Need $need)
    {
        $need->load(['refugee', 'recorder']);
        return view('needs.show', compact('need'));
    }

    public function edit(Need $need)
    {
        $refugees = Refugee::orderBy('name')->get();
        return view('needs.edit', compact('need', 'refugees'));
    }

    public function update(Request $request, Need $need, PriorityService $priority, AuditLogService $audit)
    {
        $data = $request->validate([
            'category'           => 'required|in:food,shelter,healthcare,education,protection',
            'description'        => 'required|string|max:1000',
            'urgency_level'      => 'required|integer|min:1|max:5',
            'has_disability'     => 'boolean',
            'is_pregnant'        => 'boolean',
            'has_critical_health' => 'boolean',
            'family_size'        => 'required|integer|min:1',
            'status'             => 'required|in:pending,in_progress,resolved',
        ]);

        $data['has_disability']      = $request->boolean('has_disability');
        $data['is_pregnant']         = $request->boolean('is_pregnant');
        $data['has_critical_health'] = $request->boolean('has_critical_health');
        $data['priority_score']      = $priority->calculate($data, $need->refugee);

        $old = $need->toArray();
        $need->update($data);

        $audit->log('updated', 'Need', $need->id, $old, $need->fresh()->toArray());

        return redirect()->route('needs.show', $need)->with('success', 'Need updated.');
    }

    public function destroy(Need $need, AuditLogService $audit)
    {
        $audit->log('deleted', 'Need', $need->id, $need->toArray(), []);
        $need->delete();

        return redirect()->route('needs.index')->with('success', 'Need removed.');
    }
}
