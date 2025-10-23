<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HabitTracking;
use App\Models\Habit;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client;

class HabitTrackingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Démarrer une habitude
     */
    public function start(Habit $habit)
    {
        $userId = Auth::id();
        $today = Carbon::today()->toDateString();

        Log::info("🚀 Starting habit '{$habit->name}' (ID: {$habit->id}) for user {$userId}");

        $tracking = HabitTracking::firstOrCreate(
            ['habit_id' => $habit->id, 'user_id' => $userId, 'date' => $today],
            [
                'progress' => 0,
                'state' => 'not_started',
                'started_at' => null,
                'ended_at' => null,
            ]
        );

        // Respecter schedule_time
        if ($habit->schedule_time) {
            $scheduledStart = Carbon::parse($today . ' ' . $habit->schedule_time);
            if (Carbon::now()->lt($scheduledStart)) {
                return response()->json([
                    'message' => "Habit will start at {$habit->schedule_time}",
                    'tracking_id' => $tracking->id
                ]);
            }
        }

        // Démarrer l'activité
        if (!$tracking->started_at) {
            $tracking->started_at = now();
            $tracking->state = 'in_progress';

            if ($habit->duration) {
                $tracking->ended_at = now()->addMinutes($habit->duration);
                Log::info("⏰ Habit will end at {$tracking->ended_at}");
            }

            $tracking->save();
            Log::info("✅ Tracking started: ID {$tracking->id}");
        }

        return response()->json([
            'success' => true,
            'tracking_id' => $tracking->id,
            'started_at' => $tracking->started_at,
            'will_end_at' => $tracking->ended_at,
            'duration_minutes' => $habit->duration
        ]);
    }

public function show(HabitTracking $tracking)
{
    // Check if user owns this tracking
    if ($tracking->user_id !== Auth::id()) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    return response()->json([
        'id' => $tracking->id,
        'habit_id' => $tracking->habit_id,
        'progress' => $tracking->progress,
        'state' => $tracking->state,
        'started_at' => $tracking->started_at,
        'ended_at' => $tracking->ended_at,
        'date' => $tracking->date,
    ]);
}

    public function updateProgress(Request $request, HabitTracking $tracking)
    {
        $progress = $request->input('progress');

        // Validate progress
        if ($progress === null || $progress < 0 || $progress > 100) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid progress value'
            ], 400);
        }

        $tracking->progress = $progress;

    // ✅ AUTO-COMPLETE when progress reaches 100%
    if ($progress >= 100 && $tracking->state !== 'completed') {
        $tracking->state = 'completed';
        $tracking->progress = 100; // Ensure it's exactly 100

        Log::info("🎯 Auto-completed via progress: {$tracking->habit->name} (ID: {$tracking->id})");

        // Send SMS for completion
        try {
            $this->sendCompletionSms($tracking);
            Log::info("📱 SMS sent for auto-completion");
        } catch (\Exception $e) {
            Log::error("❌ SMS failed: {$e->getMessage()}");
        }
    }
    // ✅ Keep in_progress state if not completed yet
    elseif ($progress > 0 && $tracking->state === 'not_started') {
        $tracking->state = 'in_progress';
    }

    $tracking->save();

    Log::info("📊 Progress updated to {$progress}% for tracking {$tracking->id}, state: {$tracking->state}");

    return response()->json([
        'success' => true,
        'progress' => $tracking->progress,
        'state' => $tracking->state,
    ]);
}

    /**
     * Terminer manuellement une habitude
     */
    public function finish(HabitTracking $tracking)
    {
        if ($tracking->state !== 'completed') {
            $tracking->update([
                'progress' => 100,
                'state' => 'completed',
            ]);

            Log::info("✅ Manually completed: {$tracking->habit->name} (ID: {$tracking->id})");

            // Envoyer SMS
            try {
                $this->sendCompletionSms($tracking);
                Log::info("📱 SMS sent for manual completion");
            } catch (\Exception $e) {
                Log::error("❌ SMS failed: {$e->getMessage()}");
            }
        }

        return response()->json(['success' => true]);
    }

    /**
     * Envoyer SMS de complétion
     */
    protected function sendCompletionSms($tracking)
    {
        $twilio = new Client(
            env('TWILIO_SID'),
            env('TWILIO_AUTH_TOKEN')
        );

        $message = "Votre activité '{$tracking->habit->name}' est terminée ! 🎉";
        $phone = Auth::user()->phone ?? '+21623340490';

        $twilio->messages->create($phone, [
            'from' => env('TWILIO_FROM'),
            'body' => $message,
        ]);
    }
}
