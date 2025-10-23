<?php

namespace App\Services\Weather;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;

class OpenMeteoClient
{
    protected string $geocodingEndpoint = 'https://geocoding-api.open-meteo.com/v1/search';
    protected string $weatherEndpoint = 'https://api.open-meteo.com/v1/forecast';

    public function fetchByCity(string $city): array
    {
        $city = trim($city);
        if ($city === '') {
            throw new RuntimeException('City is required.');
        }

        $location = $this->geocodeCity($city);

        $weatherResponse = Http::timeout(8)->get($this->weatherEndpoint, [
            'latitude' => $location['latitude'],
            'longitude' => $location['longitude'],
            'current' => 'temperature_2m,apparent_temperature,relative_humidity_2m,precipitation,weather_code,wind_speed_10m',
            'hourly' => 'precipitation_probability',
            'timezone' => 'auto',
        ]);

        if ($weatherResponse->failed()) {
            throw new RuntimeException('Unable to fetch weather data.');
        }

        $current = $weatherResponse->json('current');
        if (!$current) {
            throw new RuntimeException('Weather data unavailable for the selected city.');
        }

        return [
            'location' => $location,
            'current' => [
                'temperature' => Arr::get($current, 'temperature_2m'),
                'apparent_temperature' => Arr::get($current, 'apparent_temperature'),
                'humidity' => Arr::get($current, 'relative_humidity_2m'),
                'precipitation' => Arr::get($current, 'precipitation'),
                'wind_speed' => Arr::get($current, 'wind_speed_10m'),
                'weather_code' => Arr::get($current, 'weather_code'),
                'time' => Arr::get($current, 'time'),
            ],
            'suggestions' => $this->buildSuggestions($current),
        ];
    }

    protected function geocodeCity(string $city): array
    {
        $response = Http::timeout(8)->get($this->geocodingEndpoint, [
            'name' => $city,
            'count' => 1,
            'language' => 'en',
            'format' => 'json',
        ]);

        if ($response->failed()) {
            throw new RuntimeException('Unable to locate the city.');
        }

        $result = $response->json('results.0');
        if (!$result) {
            throw new RuntimeException('City not found. Try a nearby larger city.');
        }

        return [
            'name' => Arr::get($result, 'name'),
            'country' => Arr::get($result, 'country'),
            'timezone' => Arr::get($result, 'timezone'),
            'latitude' => Arr::get($result, 'latitude'),
            'longitude' => Arr::get($result, 'longitude'),
        ];
    }

    protected function buildSuggestions(array $current): array
    {
        $temperature = Arr::get($current, 'temperature_2m');
        $apparent = Arr::get($current, 'apparent_temperature', $temperature);
        $precip = Arr::get($current, 'precipitation', 0);
        $weatherCode = Arr::get($current, 'weather_code');
        $windSpeed = Arr::get($current, 'wind_speed_10m', 0);

        $activity = $this->activitySuggestion($weatherCode, $precip, $windSpeed, $apparent);
        $hydration = $this->hydrationSuggestion($apparent, $precip, Arr::get($current, 'relative_humidity_2m'));

        return [
            'activity' => $activity,
            'hydration' => $hydration,
        ];
    }

    protected function activitySuggestion($weatherCode, $precip, $windSpeed, $apparent): string
    {
        $code = (int) $weatherCode;
        if ($precip > 0.2 || in_array($code, [51, 53, 55, 61, 63, 65, 80, 81, 82, 95, 96, 99])) {
            return 'Wet conditions detected—consider an indoor routine, yoga, or a stretching session.';
        }

        if (in_array($code, [2, 3, 45, 48])) {
            return 'Cloudy or foggy skies—light outdoor walks are fine, but keep visibility in mind.';
        }

        if ($apparent !== null && $apparent > 28) {
            return 'Hot weather—schedule outdoor training early, or switch to indoor cardio to avoid heat stress.';
        }

        if ($windSpeed > 35) {
            return 'It is quite windy. Opt for sheltered outdoor spots or indoor cycling.';
        }

        return 'Great weather for outdoor activity—try a run, bike ride, or outdoor habit objective!';
    }

    protected function hydrationSuggestion($apparent, $precip, $humidity): string
    {
        if ($apparent !== null && $apparent > 30) {
            return 'High heat—add at least one extra bottle of water to today’s plan and schedule cool-down breaks.';
        }

        if ($humidity !== null && $humidity < 35) {
            return 'Dry air detected—keep a water bottle nearby and consider a quick hydration reminder objective.';
        }

        if ($precip > 0) {
            return 'Rainy weather—stick to your normal hydration, but keep a warm drink ready if it’s cool.';
        }

        return 'Moderate conditions—stay consistent with your usual hydration objectives.';
    }
}

