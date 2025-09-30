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

    protected $defaultIcons = [
        'sleep'    => 'https://cdn-icons-png.flaticon.com/512/681/681494.png',
        'sport'    => 'https://cdn-icons-png.flaticon.com/512/2917/2917251.png',
        'study'    => 'https://cdn-icons-png.flaticon.com/512/3135/3135715.png',
        'reading'  => 'https://cdn-icons-png.flaticon.com/512/1055/1055646.png',
        'nutrition'=> 'https://cdn-icons-png.flaticon.com/512/1046/1046784.png',
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
            'defaultIcons' => $this->defaultIcons
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

    // Ajoutez cette méthode à la classe HabitController
    public function start(Habit $habit)
    {
        $userId = Auth::id();
        $today = Carbon::today()->toDateString();

        $tracking = HabitTracking::firstOrCreate(
            ['habit_id' => $habit->id, 'user_id' => $userId, 'date' => $today],
            [
                'progress' => 0,
                'state' => $habit->duration ? 'in_progress' : 'not_started',
                'started_at' => now() // <-- Ajoutez cette ligne
            ]
        );

        // Si le tracking existait déjà mais n'avait pas started_at, on le met à jour
        if ($tracking->started_at === null && $habit->duration) {
            $tracking->started_at = now();
            $tracking->save();
        }

        return response()->json(['tracking_id' => $tracking->id]);
    }
}
