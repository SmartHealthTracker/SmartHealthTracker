<?php

namespace App\Http\Controllers;

use App\Models\Challenge;
use App\Models\Participation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ChallengeParticipationController extends Controller
{
    /**
     * Display the Challenge & Participation Dashboard.
     */
    public function index(Request $request)
    {
        // --- Statistics ---
        $totalChallenges = Challenge::count();
        $totalParticipations = Participation::count();
        $approved = Participation::where('status', 'approved')->count();
        $rejected = Participation::where('status', 'rejected')->count(); // <-- changed

        // --- Challenges Created Per Month ---
        $challengesPerMonth = Challenge::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $months = $challengesPerMonth->pluck('month')
            ->map(fn($m) => Carbon::create()->month($m)->format('F'))
            ->toArray();

        $counts = $challengesPerMonth->pluck('total')->toArray();

        // --- Top 5 Challenges by Participation ---
        $topChallenges = Challenge::withCount('participations')
            ->orderByDesc('participations_count')
            ->take(5)
            ->get()
            ->map(fn($c) => [
                'name' => $c->name,
                'participations_count' => $c->participations_count
            ])
            ->toArray();

        // --- Timetable Challenges (with optional search & sort) ---
        $query = Challenge::query();

        // Search filter
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Sort order
        $sort = $request->get('sort', 'asc'); // Default A-Z
        $query->orderBy('name', $sort);

        $timetableChallenges = $query->get()->map(function ($c) {
            return [
                'title' => $c->name,
                'start' => $c->start_date,
                'end' => $c->end_date ?? $c->start_date,
            ];
        });

        // --- Return Dashboard View ---
        return view('cha-parti-dashboard', compact(
            'totalChallenges',
            'totalParticipations',
            'approved',
            'rejected',
            'months',
            'counts',
            'topChallenges',
            'timetableChallenges',
            'sort'
        ));
    }

    /**
     * Export all challenges to PDF.
     */
  public function exportPdf()
{
    $challenges = Challenge::all();
    $pdf = \PDF::loadView('challenges.pdf', compact('challenges'));
    return $pdf->download('challenges.pdf');
}

}
