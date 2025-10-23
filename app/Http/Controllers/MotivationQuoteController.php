<?php

namespace App\Http\Controllers;

use App\Services\Motivation\ZenQuotesClient;
use Illuminate\Support\Facades\Log;

class MotivationQuoteController extends Controller
{
    protected ZenQuotesClient $quotesClient;

    public function __construct(ZenQuotesClient $quotesClient)
    {
        $this->middleware('auth');
        $this->quotesClient = $quotesClient;
    }

    public function __invoke()
    {
        try {
            $quote = $this->quotesClient->fetchToday();

            return response()->json([
                'success' => true,
                'data' => $quote,
            ]);
        } catch (\Throwable $e) {
            Log::warning('ZenQuotes fetch failed', ['message' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Could not retrieve motivation quote right now.',
            ], 422);
        }
    }
}

