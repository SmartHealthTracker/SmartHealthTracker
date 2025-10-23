<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HabitTracking;
use App\Models\Habit;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class HabitTrackingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Start habit: crée ou récupère le tracking
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

        if ($tracking->started_at === null && $habit->duration) {
            $tracking->started_at = now();
            $tracking->save();
        }

        return response()->json(['tracking_id' => $tracking->id]);
    }

    // Update progress
    public function updateProgress(Request $request, HabitTracking $tracking)
    {
        $habit = $tracking->habit;

        // Calcul dynamique du progrès basé sur le temps écoulé depuis started_at
        if ($tracking->state === 'in_progress' && $habit->duration && $tracking->started_at) {
            $elapsed = \Carbon\Carbon::parse($tracking->started_at)->diffInMinutes(now());
            $progress = min(100, round(($elapsed / $habit->duration) * 100));
            $tracking->progress = $progress;
            if ($progress >= 100) {
                $tracking->state = 'completed';
            }
            $tracking->save();
        }

        return response()->json([
            'success' => true,
            'progress' => $tracking->progress,
            'state' => $tracking->state
        ]);
    }

    // Finish habit
    public function finish(HabitTracking $tracking)
    {
        $tracking->progress = 100;
        $tracking->state = 'completed';
        $tracking->save();

        return response()->json(['success' => true]);
    }
}
