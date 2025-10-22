<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class HealthAssistantController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Récupérer les statistiques de l'utilisateur
        $stats = $this->getUserStats($user->id);
        
        return view('health-assistant.index', compact('stats'));
    }

    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        $user = Auth::user();
        $userMessage = $request->message;

        // Récupérer l'historique des activités de l'utilisateur
        $userContext = $this->buildUserContext($user->id);

        // Appeler l'API gratuite
        $aiResponse = $this->callFreeAI($userMessage, $userContext);

        return response()->json([
            'success' => true,
            'response' => $aiResponse,
            'timestamp' => now()->format('H:i')
        ]);
    }

    private function getUserStats($userId)
    {
        $logs = ActivityLog::where('user_id', $userId)
            ->with('activity')
            ->orderBy('date', 'desc')
            ->get();

        return [
            'total_activities' => $logs->count(),
            'total_calories' => $logs->sum('calories_burned'),
            'total_duration' => $logs->sum('duration'),
            'last_activity' => $logs->first(),
            'recent_logs' => $logs->take(10)
        ];
    }

    private function buildUserContext($userId)
    {
        $logs = ActivityLog::where('user_id', $userId)
            ->with('activity')
            ->orderBy('date', 'desc')
            ->take(30)
            ->get();

        $context = [
            'total_activities' => $logs->count(),
            'total_calories' => $logs->sum('calories_burned'),
            'total_duration' => $logs->sum('duration'),
            'activities' => []
        ];

        // Grouper par activité
        $grouped = $logs->groupBy('activity.name');
        foreach ($grouped as $activityName => $activityLogs) {
            $context['activities'][] = [
                'name' => $activityName,
                'count' => $activityLogs->count(),
                'total_calories' => $activityLogs->sum('calories_burned'),
                'total_duration' => $activityLogs->sum('duration')
            ];
        }

        // Activité la plus pratiquée
        $context['most_practiced'] = $grouped->sortByDesc(fn($group) => $group->count())->keys()->first() ?? 'Aucune';

        // Dernières activités
        $context['recent'] = $logs->take(5)->map(function($log) {
            return [
                'activity' => $log->activity->name,
                'date' => $log->date,
                'duration' => $log->duration,
                'calories' => $log->calories_burned
            ];
        })->toArray();

        return $context;
    }

    private function callFreeAI($userMessage, $userContext)
    {
        // Option 1 : Hugging Face API (GRATUIT et ILLIMITÉ)
        try {
            return $this->callHuggingFace($userMessage, $userContext);
        } catch (\Exception $e) {
            // Fallback vers Google Gemini
            try {
                return $this->callGemini($userMessage, $userContext);
            } catch (\Exception $e2) {
                // Fallback vers l'analyse locale
                return $this->generateLocalResponse($userMessage, $userContext);
            }
        }
    }

    private function callHuggingFace($userMessage, $userContext)
    {
        $apiKey = env('HUGGINGFACE_API_KEY'); // GRATUIT sur huggingface.co
        
        $prompt = $this->buildPrompt($userMessage, $userContext);

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
        ])->timeout(30)->post('https://api-inference.huggingface.co/models/mistralai/Mistral-7B-Instruct-v0.2', [
            'inputs' => $prompt,
            'parameters' => [
                'max_new_tokens' => 500,
                'temperature' => 0.7,
                'top_p' => 0.95,
                'return_full_text' => false
            ]
        ]);

        if ($response->successful()) {
            $data = $response->json();
            $generatedText = $data[0]['generated_text'] ?? '';
            return $this->cleanResponse($generatedText);
        }

        throw new \Exception('Hugging Face API failed');
    }

    private function callGemini($userMessage, $userContext)
    {
        $apiKey = env('GEMINI_API_KEY'); // GRATUIT sur ai.google.dev
        
        $prompt = $this->buildPrompt($userMessage, $userContext);

        $response = Http::timeout(30)->post(
            "https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent?key={$apiKey}",
            [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.7,
                    'maxOutputTokens' => 500
                ]
            ]
        );

        if ($response->successful()) {
            $data = $response->json();
            $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';
            return $this->cleanResponse($text);
        }

        throw new \Exception('Gemini API failed');
    }

    private function buildPrompt($userMessage, $userContext)
    {
        $prompt = "Tu es un coach sportif et assistant de santé bienveillant. ";
        $prompt .= "Analyse les données suivantes et réponds en français de manière concise et encourageante.\n\n";
        
        $prompt .= "📊 DONNÉES DE L'UTILISATEUR:\n";
        $prompt .= "- Nombre total d'activités: {$userContext['total_activities']}\n";
        $prompt .= "- Calories brûlées: " . number_format($userContext['total_calories']) . " kcal\n";
        $prompt .= "- Durée totale: {$userContext['total_duration']} minutes\n";
        $prompt .= "- Activité préférée: {$userContext['most_practiced']}\n\n";

        if (!empty($userContext['activities'])) {
            $prompt .= "📋 ACTIVITÉS PRATIQUÉES:\n";
            foreach ($userContext['activities'] as $activity) {
                $prompt .= "- {$activity['name']}: {$activity['count']} fois, {$activity['total_duration']} min, {$activity['total_calories']} kcal\n";
            }
            $prompt .= "\n";
        }

        if (!empty($userContext['recent'])) {
            $prompt .= "🕐 DERNIÈRES ACTIVITÉS:\n";
            foreach ($userContext['recent'] as $recent) {
                $prompt .= "- {$recent['date']}: {$recent['activity']} ({$recent['duration']} min, {$recent['calories']} kcal)\n";
            }
            $prompt .= "\n";
        }

        $prompt .= "❓ QUESTION: {$userMessage}\n\n";
        $prompt .= "Réponds de manière personnalisée en te basant sur ces données. Sois encourageant, précis et donne des conseils pratiques. Maximum 200 mots.";

        return $prompt;
    }

    private function generateLocalResponse($userMessage, $userContext)
    {
        // Système de réponse locale basé sur des règles (fallback si toutes les API échouent)
        
        $message = strtolower($userMessage);
        $response = "";

        // Analyse des mots-clés
        if (str_contains($message, 'recommand') || str_contains($message, 'conseil') || str_contains($message, 'activité')) {
            $response = $this->getRecommendations($userContext);
        } 
        elseif (str_contains($message, 'analyse') || str_contains($message, 'statistique') || str_contains($message, 'habitude')) {
            $response = $this->getAnalysis($userContext);
        }
        elseif (str_contains($message, 'améliorer') || str_contains($message, 'routine') || str_contains($message, 'progrès')) {
            $response = $this->getImprovementTips($userContext);
        }
        elseif (str_contains($message, 'objectif') || str_contains($message, 'but')) {
            $response = $this->getGoalAdvice($userContext);
        }
        else {
            $response = $this->getGeneralAdvice($userContext);
        }

        return $response;
    }

    private function getRecommendations($context)
    {
        $response = "🎯 **Recommandations personnalisées**\n\n";
        
        if ($context['total_activities'] < 5) {
            $response .= "Vous débutez, c'est parfait ! Je vous recommande :\n";
            $response .= "• Commencez par 20-30 minutes de marche rapide 3 fois par semaine\n";
            $response .= "• Ajoutez du vélo ou de la natation progressivement\n";
            $response .= "• Écoutez votre corps et augmentez l'intensité graduellement\n";
        } else {
            $mostPracticed = $context['most_practiced'];
            $avgCalories = $context['total_activities'] > 0 ? $context['total_calories'] / $context['total_activities'] : 0;
            
            $response .= "Basé sur votre historique ({$context['total_activities']} activités) :\n";
            $response .= "• Continuez {$mostPracticed}, vous y êtes engagé(e) !\n";
            $response .= "• Pour varier : essayez la course à pied ou la musculation\n";
            $response .= "• Objectif : " . number_format($avgCalories * 1.2) . " kcal par session pour progresser\n";
        }
        
        $response .= "\n💪 L'important est la régularité !";
        return $response;
    }

    private function getAnalysis($context)
    {
        $response = "📊 **Analyse de vos habitudes**\n\n";
        
        $avgDuration = $context['total_activities'] > 0 ? $context['total_duration'] / $context['total_activities'] : 0;
        $avgCalories = $context['total_activities'] > 0 ? $context['total_calories'] / $context['total_activities'] : 0;
        
        $response .= "✅ **Bilan global :**\n";
        $response .= "• {$context['total_activities']} activités enregistrées\n";
        $response .= "• " . number_format($context['total_calories']) . " calories brûlées au total\n";
        $response .= "• Durée moyenne : " . round($avgDuration) . " min par session\n";
        $response .= "• Activité favorite : {$context['most_practiced']}\n\n";
        
        if ($avgDuration < 30) {
            $response .= "💡 Conseil : Essayez d'augmenter vos sessions à 30-45 min pour plus d'efficacité.";
        } elseif ($avgDuration > 90) {
            $response .= "💡 Attention à ne pas surcharger ! Pensez à la récupération.";
        } else {
            $response .= "💡 Excellente durée d'entraînement ! Continuez ainsi.";
        }
        
        return $response;
    }

    private function getImprovementTips($context)
    {
        $response = "🚀 **Comment améliorer votre routine**\n\n";
        
        $activitiesCount = count($context['activities']);
        
        if ($activitiesCount <= 1) {
            $response .= "**Diversifiez vos activités :**\n";
            $response .= "• Ajoutez 2-3 types d'exercices différents\n";
            $response .= "• Alternez cardio et renforcement musculaire\n";
            $response .= "• Essayez yoga ou stretching pour la récupération\n\n";
        }
        
        $response .= "**Optimisez vos performances :**\n";
        $response .= "• Fixez des objectifs hebdomadaires clairs\n";
        $response .= "• Augmentez progressivement l'intensité (10% par semaine)\n";
        $response .= "• Hydratez-vous bien avant/pendant/après l'effort\n";
        $response .= "• Dormez 7-8h pour une bonne récupération\n\n";
        
        $response .= "✨ La constance bat l'intensité !";
        
        return $response;
    }

    private function getGoalAdvice($context)
    {
        $weeklyCalories = $context['total_calories'];
        
        $response = "🎯 **Définir vos objectifs**\n\n";
        $response .= "Basé sur votre activité actuelle :\n\n";
        $response .= "**Objectif débutant :** 1500 kcal/semaine\n";
        $response .= "**Objectif intermédiaire :** 2500 kcal/semaine\n";
        $response .= "**Objectif avancé :** 3500+ kcal/semaine\n\n";
        
        if ($weeklyCalories < 1500) {
            $response .= "💡 Commencez par 3 sessions de 30 min cette semaine !";
        } elseif ($weeklyCalories < 2500) {
            $response .= "💡 Vous êtes sur la bonne voie ! Visez 4-5 sessions par semaine.";
        } else {
            $response .= "💡 Excellent niveau ! Pensez à intégrer des séances de récupération active.";
        }
        
        return $response;
    }

    private function getGeneralAdvice($context)
    {
        $responses = [
            "Excellente question ! Basé sur vos {$context['total_activities']} activités, vous êtes sur la bonne voie. Continuez à être régulier(e) et les résultats suivront ! 💪",
            
            "Avec {$context['total_calories']} calories brûlées, vous faites un excellent travail ! L'important est de rester constant et d'écouter votre corps. 🎯",
            
            "Votre activité préférée est {$context['most_practiced']} ? C'est génial ! N'hésitez pas à varier pour travailler différents groupes musculaires. 🏃‍♂️",
            
            "Chaque session compte ! Vous avez passé {$context['total_duration']} minutes à prendre soin de votre santé. Félicitations ! ✨"
        ];
        
        return $responses[array_rand($responses)];
    }

    private function cleanResponse($text)
    {
        // Nettoyer la réponse
        $text = trim($text);
        $text = preg_replace('/\[INST\].*?\[\/INST\]/s', '', $text);
        $text = preg_replace('/^(Assistant:|AI:|Response:)/i', '', $text);
        return trim($text);
    }
}