<?php
namespace App\Http\Controllers;

use App\Models\Challenge;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ChallengeController extends Controller
{
    // Dashboard / List Challenges
    public function index()
    {
        $timetableChallenges = Challenge::select('id', 'name as title', 'start_date as start', 'end_date as end')
            ->withCount('participations')
            ->get()
            ->toArray();

        $totalChallenges = Challenge::count();
        $totalParticipations = Challenge::withCount('participations')->get()->sum('participations_count');
        $approved = Challenge::withCount(['participations' => fn($q) => $q->where('status', 'approved')])->get()->sum('participations_count');
        $pending = Challenge::withCount(['participations' => fn($q) => $q->where('status', 'pending')])->get()->sum('participations_count');

        $months = Challenge::selectRaw("DATE_FORMAT(start_date,'%b %Y') as month")
            ->distinct()
            ->pluck('month');
        $counts = $months->map(fn($m) => Challenge::whereRaw("DATE_FORMAT(start_date,'%b %Y') = ?", [$m])->count());

        $topChallenges = Challenge::withCount('participations')
            ->orderBy('participations_count', 'desc')
            ->limit(5)
            ->get();

        return view('challenges.index', compact(
            'timetableChallenges',
            'totalChallenges',
            'totalParticipations',
            'approved',
            'pending',
            'months',
            'counts',
            'topChallenges'
        ));
    }

    // Show a single challenge (fix for your error)
    public function show($id)
    {
        $challenge = Challenge::with('participations')->findOrFail($id);
        return view('challenges.show', compact('challenge'));
    }

    // Store new challenge (AJAX)
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $challenge = Challenge::create([
            'name' => $data['title'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date']
        ]);

        return response()->json([
            'id' => $challenge->id,
            'title' => $challenge->name,
            'start_date' => $challenge->start_date,
            'end_date' => $challenge->end_date
        ]);
    }

    // Update challenge (AJAX)
    public function update(Request $request, Challenge $challenge)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $challenge->update([
            'name' => $data['title'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date']
        ]);

        return response()->json([
            'id' => $challenge->id,
            'title' => $challenge->name,
            'start_date' => $challenge->start_date,
            'end_date' => $challenge->end_date
        ]);
    }

    // Delete challenge (AJAX)
    public function destroy(Challenge $challenge)
    {
        $challenge->delete();
        return response()->json(['success' => true]);
    }

    // Export challenges to PDF
    public function exportPdf()
    {
        $challenges = Challenge::all();
        $pdf = Pdf::loadView('challenges.pdf', compact('challenges'));
        return $pdf->download('challenges.pdf');
    }
}
