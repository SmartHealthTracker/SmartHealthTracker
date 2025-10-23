<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\HabitTracking;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client;

class CheckCompletedHabits extends Command
{
    protected $signature = 'habits:check-completed';
    protected $description = 'Check and complete habits that have reached their end time';

    public function handle()
    {
        $now = Carbon::now();

        // Trouver tous les trackings en cours dont ended_at est dépassé
        $trackings = HabitTracking::where('state', 'in_progress')
            ->whereNotNull('ended_at')
            ->where('ended_at', '<=', $now)
            ->with('habit', 'user')
            ->get();

        if ($trackings->isEmpty()) {
            $this->info("✅ No habits to complete");
            return 0;
        }

        $this->info("🔍 Found {$trackings->count()} habits to complete");
        Log::info("🔍 Checking habits - Found {$trackings->count()} to complete");

        foreach ($trackings as $tracking) {
            // Mettre à jour le tracking
            $tracking->progress = 100;
            $tracking->state = 'completed';
            $tracking->save();

            $this->info("✅ Completed: {$tracking->habit->name} (ID: {$tracking->id})");
            Log::info("✅ Auto-completed habit '{$tracking->habit->name}' (Tracking ID: {$tracking->id})");

            // Envoyer SMS via Twilio
            try {
                $this->sendSms($tracking);
                $this->info("📱 SMS sent for: {$tracking->habit->name}");
                Log::info("📱 SMS sent to user {$tracking->user_id} for habit '{$tracking->habit->name}'");
            } catch (\Exception $e) {
                $this->error("❌ SMS failed: {$e->getMessage()}");
                Log::error("❌ SMS failed for tracking {$tracking->id}: {$e->getMessage()}");
            }
        }

        $this->info("✨ Done! Completed {$trackings->count()} habits");
        return 0;
    }

    protected function sendSms($tracking)
    {
        $twilio = new Client(
            env('TWILIO_SID'),
            env('TWILIO_AUTH_TOKEN')
        );

        $message = "Votre activité '{$tracking->habit->name}' est terminée ! 🎉";
        $phone = $tracking->user->phone ?? '+21623340490';

        $twilio->messages->create($phone, [
            'from' => env('TWILIO_FROM'),
            'body' => $message,
        ]);
    }
}
