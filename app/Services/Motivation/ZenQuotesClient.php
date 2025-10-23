<?php

namespace App\Services\Motivation;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;

class ZenQuotesClient
{
    protected string $endpoint = 'https://zenquotes.io/api/today';
    protected int $cacheMinutes = 30;

    public function fetchToday(): array
    {
        return Cache::remember('zenquotes.today', now()->addMinutes($this->cacheMinutes), function () {
            $response = Http::timeout(6)->get($this->endpoint);

            if ($response->failed()) {
                throw new RuntimeException('Unable to fetch motivation quote at the moment.');
            }

            $data = $response->json();
            if (!is_array($data) || empty($data[0])) {
                throw new RuntimeException('Quote data unavailable.');
            }

            $quote = $data[0];

            return [
                'quote' => (string) Str::of($quote['q'] ?? 'Stay motivated and keep progressing!')->trim(),
                'author' => (string) Str::of($quote['a'] ?? 'Unknown')->trim(),
                'date' => now()->toDateString(),
            ];
        });
    }
}
