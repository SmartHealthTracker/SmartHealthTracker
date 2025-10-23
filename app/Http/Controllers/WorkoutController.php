<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\WorkoutApiService;

class WorkoutController extends Controller
{
    protected $service;

    public function __construct(WorkoutApiService $service)
    {
        $this->service = $service;
    }

    public function generate(Request $request)
    {
        $request->validate([
            'goal' => 'required|string',
            'fitness_level' => 'required|string',
            'preferences' => 'required|array',
            'days_per_week' => 'required|integer',
            'session_duration' => 'required|integer',
            'plan_duration_weeks' => 'required|integer',
        ]);

        $data = [
            'goal' => $request->goal,
            'fitness_level' => $request->fitness_level,
            'preferences' => $request->preferences,
            'health_conditions' => ['None'],
            'schedule' => [
                'days_per_week' => (int) $request->days_per_week,
                'session_duration' => (int) $request->session_duration,
            ],
            'plan_duration_weeks' => (int) $request->plan_duration_weeks,
            'lang' => 'en',
        ];

        // Appel au service API
        $plan = $this->service->generateWorkoutPlan($data);

        // Récupérer le résultat principal
        $planResult = $plan['result'] ?? [];

        // Envoyer à la vue Blade
        return view('workout.plan', ['plan' => $planResult]);
    }
}
