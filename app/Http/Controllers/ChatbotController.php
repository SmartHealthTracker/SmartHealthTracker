<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class ChatbotController extends Controller
{
    // Page du chatbot
    public function index()
    {
        return view('pages.chatbot.index');
    }

    // Réponse du chatbot
    public function getResponse(Request $request)
    {
        $question = strtolower($request->get('question'));
        $userId = Auth::id();

        $response = "Je n'ai pas compris votre question. 😕";

        // Gestion calories
        if (str_contains($question, 'calories')) {
            if (str_contains($question, 'semaine')) {
                $startOfWeek = now()->startOfWeek();
                $endOfWeek = now()->endOfWeek();
                $weeklyCalories = ActivityLog::where('user_id', $userId)
                    ->whereBetween('date', [$startOfWeek, $endOfWeek])
                    ->sum('calories_burned');
                $response = "Cette semaine, vous avez brûlé **$weeklyCalories** calories. 🔥";
            } elseif (str_contains($question, 'aujourd')) {
                $todayCalories = ActivityLog::where('user_id', $userId)
                    ->whereDate('date', now()->toDateString())
                    ->sum('calories_burned');
                $response = "Aujourd'hui, vous avez brûlé **$todayCalories** calories. 💪";
            } else {
                $totalCalories = ActivityLog::where('user_id', $userId)->sum('calories_burned');
                $response = "Vous avez brûlé un total de **$totalCalories** calories. 🏃‍♂️";
            }
        }

        // Gestion durée
        elseif (str_contains($question, 'durée') || str_contains($question, 'minutes')) {
            $totalDuration = ActivityLog::where('user_id', $userId)->sum('duration');
            $response = "Vous avez fait **$totalDuration minutes** d’activité au total. ⏱️";
        }

        // Statistiques générales
        elseif (str_contains($question, 'statistiques') || str_contains($question, 'résumé')) {
            $totalCalories = ActivityLog::where('user_id', $userId)->sum('calories_burned');
            $totalDuration = ActivityLog::where('user_id', $userId)->sum('duration');
            $response = "Résumé : **$totalCalories calories** brûlées et **$totalDuration minutes** d'activité. 📊";
        }

        // Accueil ou salutations
        elseif (str_contains($question, 'bonjour') || str_contains($question, 'salut')) {
            $response = "Bonjour ! Je suis votre assistant fitness 🤖. Posez-moi une question sur vos activités.";
        }

        return response()->json(['response' => $response]);
    }
}
