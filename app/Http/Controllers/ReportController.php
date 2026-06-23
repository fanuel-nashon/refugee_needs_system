<?php

namespace App\Http\Controllers;

use App\Models\Need;
use App\Models\Refugee;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        $needsByCategory = Need::selectRaw('category, count(*) as total, avg(priority_score) as avg_score')
            ->groupBy('category')
            ->orderByDesc('total')
            ->get();

        $needsByStatus = Need::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $topPriorityCases = Need::with('refugee')
            ->whereIn('status', ['pending', 'in_progress'])
            ->orderByDesc('priority_score')
            ->limit(20)
            ->get();

        $needsByUrgency = Need::selectRaw('urgency_level, count(*) as total')
            ->groupBy('urgency_level')
            ->orderBy('urgency_level')
            ->pluck('total', 'urgency_level');

        $recentNeeds = Need::with(['refugee', 'recorder'])
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        $totalRefugees  = Refugee::count();
        $refugeesWithNeeds = Need::distinct('refugee_id')->count('refugee_id');
        $refugeesWithoutNeeds = $totalRefugees - $refugeesWithNeeds;

        return view('reports.index', compact(
            'needsByCategory',
            'needsByStatus',
            'topPriorityCases',
            'needsByUrgency',
            'recentNeeds',
            'totalRefugees',
            'refugeesWithNeeds',
            'refugeesWithoutNeeds'
        ));
    }
}
