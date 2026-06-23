<?php

namespace App\Http\Controllers;

use App\Models\Need;
use App\Models\Refugee;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $stats = [
            'total_refugees' => Refugee::count(),
            'total_needs'    => Need::count(),
            'pending'        => Need::where('status', 'pending')->count(),
            'in_progress'    => Need::where('status', 'in_progress')->count(),
            'resolved'       => Need::where('status', 'resolved')->count(),
        ];

        $topPriority = Need::with('refugee')
            ->whereIn('status', ['pending', 'in_progress'])
            ->orderByDesc('priority_score')
            ->limit(10)
            ->get();

        $byCategory = Need::selectRaw('category, count(*) as total')
            ->groupBy('category')
            ->pluck('total', 'category');

        // Aid workers only see needs they recorded
        if ($user->hasRole('aid_worker')) {
            $stats['my_needs']     = Need::where('recorded_by', $user->id)->count();
            $stats['my_pending']   = Need::where('recorded_by', $user->id)->where('status', 'pending')->count();
            $topPriority = Need::with('refugee')
                ->where('recorded_by', $user->id)
                ->whereIn('status', ['pending', 'in_progress'])
                ->orderByDesc('priority_score')
                ->limit(10)
                ->get();
        }

        return view('pages.dashboard', compact('stats', 'topPriority', 'byCategory'));
    }
}
