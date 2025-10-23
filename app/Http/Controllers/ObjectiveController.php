<?php

namespace App\Http\Controllers;

use App\Models\Objective;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ObjectiveController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $objectives = Objective::where('user_id', Auth::id())
            ->orderBy('start_at', 'desc')
            ->limit(10)
            ->get();

        return view('objectives.index', compact('objectives'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_at' => 'required|date',
            'end_at' => 'nullable|date|after_or_equal:start_at',
        ]);

        $objective = Objective::create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'start_at' => Carbon::parse($data['start_at']),
            'end_at' => isset($data['end_at']) ? Carbon::parse($data['end_at']) : null,
            'user_id' => Auth::id(),
        ]);

        return redirect()
            ->route('objectives.index')
            ->with('success', 'Objective created successfully.');
    }

    public function update(Request $request, Objective $objective)
    {
        $this->authorizeObjective($objective);

        $data = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|nullable|string',
            'start_at' => 'sometimes|required|date',
            'end_at' => 'sometimes|nullable|date|after_or_equal:start_at',
        ]);

        if (array_key_exists('start_at', $data)) {
            $data['start_at'] = Carbon::parse($data['start_at']);
        }
        if (array_key_exists('end_at', $data)) {
            $data['end_at'] = $data['end_at'] ? Carbon::parse($data['end_at']) : null;
        }

        $objective->update($data);

        return response()->json(['success' => true]);
    }

    public function destroy(Objective $objective)
    {
        $this->authorizeObjective($objective);
        $objective->delete();

        return redirect()
            ->route('objectives.index')
            ->with('success', 'Objective deleted successfully.');
    }

    public function events(Request $request)
    {
        $userId = Auth::id();
        $start = $request->query('start') ? Carbon::parse($request->query('start'))->startOfDay() : Carbon::now()->subMonth();
        $end = $request->query('end') ? Carbon::parse($request->query('end'))->endOfDay() : Carbon::now()->addMonth();

        $objectives = Objective::where('user_id', $userId)
            ->where(function ($query) use ($start, $end) {
                $query->whereBetween('start_at', [$start, $end])
                    ->orWhere(function ($sub) use ($start, $end) {
                        $sub->whereNotNull('end_at')
                            ->whereBetween('end_at', [$start, $end]);
                    })
                    ->orWhere(function ($sub) use ($start, $end) {
                        $sub->where('start_at', '<=', $start)
                            ->where(function ($inner) use ($end) {
                                $inner->whereNull('end_at')
                                    ->orWhere('end_at', '>=', $end);
                            });
                    });
            })
            ->get();

        $events = $objectives->map(function (Objective $objective) {
            $endAt = $objective->end_at ?? $objective->start_at->copy()->addHour();

            return [
                'id' => $objective->id,
                'title' => $objective->title,
                'start' => $objective->start_at->toIso8601String(),
                'end' => $endAt->toIso8601String(),
                'extendedProps' => [
                    'description' => $objective->description,
                ],
            ];
        });

        return response()->json($events->values());
    }

    protected function authorizeObjective(Objective $objective): void
    {
        if ($objective->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }
    }
}
