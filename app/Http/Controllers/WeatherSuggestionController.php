<?php

namespace App\Http\Controllers;

use App\Services\Weather\OpenMeteoClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WeatherSuggestionController extends Controller
{
    protected OpenMeteoClient $weatherClient;

    public function __construct(OpenMeteoClient $weatherClient)
    {
        $this->middleware('auth');
        $this->weatherClient = $weatherClient;
    }

    public function __invoke(Request $request)
    {
        $request->validate([
            'city' => 'required|string|max:255',
        ]);

        try {
            $data = $this->weatherClient->fetchByCity($request->input('city'));

            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
        } catch (\Throwable $e) {
            Log::warning('Weather suggestion failed', [
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }
}

