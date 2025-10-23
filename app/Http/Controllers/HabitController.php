<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Habit;
use App\Models\HabitTracking;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class HabitController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    protected array $defaultIcons = [
        'sleep'    => 'https://www.flaticon.com/free-icon/sleep_10303407?term=sleep&page=1&position=4&origin=search&related_id=10303407',
        'sport'    => 'https://www.flaticon.com/free-icon/sports_3311579?term=sport&page=1&position=3&origin=search&related_id=3311579',
        'study'    => 'https://www.flaticon.com/free-icon/reading_8750754?term=study&page=1&position=1&origin=search&related_id=8750754',
        'reading'  => 'https://www.flaticon.com/free-icon/reading-book_4072217?term=reading&page=1&position=1&origin=search&related_id=4072217',
        'nutrition'=> 'https://www.flaticon.com/free-icon/nutrition-plan_9756984?term=nutrition&page=1&position=1&origin=search&related_id=9756984',
    ];

    public function index()
    {
        $habits = Habit::where('user_id', Auth::id())->get();
        return view('habits.index', compact('habits'));
    }

    public function create()
    {
        return view('habits.create', ['defaultIcons' => $this->defaultIcons]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'type'     => 'required|in:sleep,sport,study,reading,nutrition',
            'duration' => 'nullable|integer|min:1',
            'icon'     => 'nullable|string|max:255',
        ]);

        $icon = $request->icon ?: ($this->defaultIcons[$request->type] ?? 'https://via.placeholder.com/40');

        Habit::create([
            'name'     => $request->name,
            'type'     => $request->type,
            'duration' => $request->duration,
            'icon'     => $icon,
            'user_id'  => Auth::id(),
            'schedule_time' => $request->schedule_time,
            'description' => $request->description,
        ]);

        return redirect()->route('habits.index')->with('success', 'Habitude ajoutée avec succès.');
    }

    public function edit(Habit $habit)
    {
        $this->authorize('update', $habit);
        return view('habits.edit', [
            'habit' => $habit,
            'defaultIcons' => $this->defaultIcons,
        ]);
    }

    public function update(Request $request, Habit $habit)
    {
        $this->authorize('update', $habit);

        $request->validate([
            'name'     => 'required|string|max:255',
            'type'     => 'required|in:sleep,sport,study,reading,nutrition',
            'duration' => 'nullable|integer|min:1',
            'icon'     => 'nullable|string|max:255',
        ]);

        $icon = $request->icon ?: ($this->defaultIcons[$request->type] ?? $habit->icon);

        $habit->update([
            'name'     => $request->name,
            'type'     => $request->type,
            'duration' => $request->duration,
            'icon'     => $icon,
            'schedule_time' => $request->schedule_time,
            'description' => $request->description,
        ]);

        return redirect()->route('habits.index')->with('success', 'Habitude mise à jour avec succès.');
    }

    public function destroy(Habit $habit)
    {
        $this->authorize('delete', $habit);
        $habit->delete();

        return redirect()->route('habits.index')->with('success', 'Habitude supprimée avec succès.');
    }

    public function start(Habit $habit)
    {
        $userId = Auth::id();
        $today = Carbon::today()->toDateString();

        $tracking = HabitTracking::firstOrCreate(
            ['habit_id' => $habit->id, 'user_id' => $userId, 'date' => $today],
            [
                'progress' => 0,
                'state' => $habit->duration ? 'in_progress' : 'not_started',
                'started_at' => now(),
            ]
        );

        if ($tracking->started_at === null && $habit->duration) {
            $tracking->started_at = now();
            $tracking->save();
        }

        return response()->json(['tracking_id' => $tracking->id]);
    }
}

