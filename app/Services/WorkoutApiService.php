<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WorkoutApiService
{
    protected $key;
    protected $host;

    public function __construct()
    {
        $this->key = env('RAPIDAPI_KEY');
        $this->host = env('RAPIDAPI_HOST');
    }

    public function generateWorkoutPlan(array $data)
    {
$response = Http::timeout(60)->withHeaders([
            'x-rapidapi-key' => $this->key,
            'x-rapidapi-host' => $this->host,
            'Content-Type' => 'application/json'
        ])->post("https://{$this->host}/generateWorkoutPlan?noqueue=1", $data);

        // Debug : affiche la réponse complète si échec
        if ($response->failed()) {
            dd($response->status(), $response->body());
            throw new \Exception('Erreur API Workout Planner');
        }

        return $response->json();
    }
}
