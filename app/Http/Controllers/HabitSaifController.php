<?php

namespace App\Http\Controllers;

use App\Models\HabitSaif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use PDF; // facade barryvdh/laravel-dompdf
use Illuminate\Support\Str;


class HabitSaifController extends Controller
{
    public function index() { // Récupération des habitudes avec pagination 
        $habits = HabitSaif::with('user')
         ->orderBy('created_at', 'desc')
         ->paginate(8); 
        // Données de démonstration si la base est vide
         return view('habitssaif.index', compact('habits')); }

    public function create()
    {
        return view('habitssaif.create');
    }


    public function update(Request $request, HabitSaif $habit)
    {
        // Your update logic
        return redirect()->route('habitssaif.index')->with('success', 'Habit mis à jour avec succès.');
    }


 public function edit(HabitSaif $habit)
    {
        return view('habitssaif.edit', compact('habit'));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'target_value' => 'nullable|integer',
            'unit' => 'nullable|string|max:50',
        ]);

        $validated['user_id'] = auth()->id();
        HabitSaif::create($validated);

        return redirect()->route('habitssaif.index')->with('success', 'Habit créé avec succès.');
    }

    public function show(HabitSaif $habit)
    {
        // QR code
        $qrText = "Titre : {$habit->title}\nDescription : {$habit->description}";
        $qrCodeUrl = "https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=" . urlencode($qrText);

        // Stats dynamiques depuis logs
        $logs = $habit->logs()->orderBy('logged_at')->get();
        $dates = $logs->pluck('logged_at')->map(fn($d) => Carbon::parse($d)->format('Y-m-d'))->unique()->values();

        $daysInRow = 0;
        if ($dates->isNotEmpty()) {
            $daysInRow = 1;
            for ($i = $dates->count() - 1; $i > 0; $i--) {
                if (Carbon::parse($dates[$i])->diffInDays(Carbon::parse($dates[$i-1])) === 1) $daysInRow++;
                else break;
            }
        }

        $totalValue = $logs->sum('value');
        $progress = 0;
        if ($habit->target_value > 0) {
            $progress = round(($totalValue / $habit->target_value) * 100);
            $progress = min($progress, 100); // limite à 100%
        }
        
        $expectedDays = $dates->count() > 0 ? Carbon::parse($dates->first())->diffInDays(now()) + 1 : 1;
        $successRate = round(($dates->count() / $expectedDays) * 100);

        $stats = [
            ['value' => $daysInRow, 'label' => 'Jours consécutifs'],
            ['value' => "{$successRate}%", 'label' => 'Taux de réussite'],
            ['value' => $totalValue, 'label' => 'Valeur totale'],
        ];

        // Recommandation IA Gemini
        $advice = $this->getGeminiAdvice($habit, $progress, $stats);

        return view('habitssaif.show', compact('habit', 'qrCodeUrl', 'stats', 'progress', 'advice'));
    }

    private function getGeminiAdvice($habit, $progress, $stats)
    {
        $prompt = "Génère une recommandation santé et motivation personnalisée pour l'habitude '{$habit->title}', avec progression {$progress}% et statistiques : " . json_encode($stats);

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->timeout(15)->post("https://generativelanguage.googleapis.com/v1/models/gemini-pro:generateContent?key=" . env('GEMINI_API_KEY'), [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ]
            ]);

            return $response->json('candidates.0.content.parts.0.text') ?? 'Continuez vos efforts ! Vous progressez bien.';
        } catch (\Exception $e) {
            return 'Conseil du jour : La régularité est la clé du succès. Continuez votre excellent travail !';
        }
    }

    public function fetchGeminiAdvice(HabitSaif $habit)
    {
        try {
            $key = env('GEMINI_API_KEY');
    
            // Si pas de clé API, retourner directement un conseil par défaut
            if (!$key) {
                return response()->json([
                    'success' => true,
                    'advice' => $this->getSimpleAdvice($habit),
                    'progress' => 0,
                    'model' => 'default'
                ]);
            }
    
            // Récupérer les logs
            $logs = $habit->logs()->get();
            $totalValue = $logs->sum('value');
            $progress = $habit->target_value > 0 
                ? round(($totalValue / $habit->target_value) * 100) 
                : 0;
    
            // Construire le prompt
            $prompt = "Donne un conseil sportif court et motivant pour l'habitude '{$habit->title}' avec {$progress}% de progression. Sois positif et concret.";
    
            // Requête API Gemini
            $response = Http::timeout(15)
                ->post("https://generativelanguage.googleapis.com/v1/models/gemini-2.5-flash:generateContent?key={$key}", [
                    'contents' => [
                        ['parts' => [['text' => $prompt]]]
                    ]
                ]);
    
            // Vérifier si la réponse est valide et contient du texte
            if ($response->successful()) {
                $data = $response->json();
                $advice = $data['candidates'][0]['content']['parts'][0]['text'] 
                    ?? $this->getSimpleAdvice($habit);
    
                return response()->json([
                    'success' => true,
                    'advice' => $advice,
                    'progress' => $progress,
                    'model' => 'gemini-pro'
                ]);
            }
    
            // Si échec de l'API, retourner conseil simple
            return response()->json([
                'success' => true,
                'advice' => $this->getSimpleAdvice($habit),
                'progress' => $progress,
                'model' => 'default'
            ]);
    
        } catch (\Throwable $e) {
            // Log de l'erreur pour débogage
            \Log::error("Erreur fetchGeminiAdvice : " . $e->getMessage(), [
                'habit_id' => $habit->id,
            ]);
    
            return response()->json([
                'success' => true,
                'advice' => $this->getSimpleAdvice($habit),
                'progress' => 0,
                'model' => 'default'
            ]);
        }
    }
    
    private function getSimpleAdvice(HabitSaif $habit)
    {
        $logs = $habit->logs()->get();
        $totalValue = $logs->sum('value');
        $progress = $habit->target_value > 0 
            ? round(($totalValue / $habit->target_value) * 100) 
            : 0;
    
        return "🎯 PROGRESSION : {$progress}% atteints\n\n💡 CONSEIL : Continuez votre excellent travail ! Votre régularité est la clé du succès.\n\n🌟 MOTIVATION : Chaque effort vous rapproche de vos objectifs !";
    }
    
    public function destroy(HabitSaif $habit)
    {
        $habit->delete();
        return redirect()->route('habitssaif.index')->with('success', 'Habit supprimé avec succès.');
    }



    public function downloadReport($id)
    {
        $habit = HabitSaif::with('logs')->findOrFail($id);
        $logs = $habit->logs()->orderBy('logged_at', 'desc')->get();

        // Données de base
        $totalValue = $logs->sum('value');
        $progress = $habit->target_value > 0 
            ? min(round(($totalValue / $habit->target_value) * 100), 100)
            : 0;

        // Générer toutes les données
        $data = $this->generateReportData($habit, $logs, $progress);
        
        return Pdf::loadView('habitssaif.reports', array_merge([
            'habit' => $habit,
            'logs' => $logs,
            'totalValue' => $totalValue,
            'progress' => $progress,
        ], $data))->download('Analyse_' . $habit->title . '.pdf');
    }

  

    private function generateRecommendations($habit, $logs, $progress, $consistency)
    {
        $recommendations = [];
        $totalSessions = $logs->count();
        $bestValue = $logs->max('value') ?? 0;

        // Basé sur la progression
        if ($progress < 30) {
            $recommendations[] = [
                'title' => 'Focus sur la régularité',
                'description' => 'Concentrez-vous sur la pratique quotidienne plutôt que l\'intensité pour établir une routine solide.'
            ];
        } elseif ($progress < 70) {
            $recommendations[] = [
                'title' => 'Augmentation progressive',
                'description' => 'Augmentez légèrement l\'intensité de vos sessions pour accélérer votre progression.'
            ];
        } else {
            $recommendations[] = [
                'title' => 'Maintien de l\'excellence',
                'description' => 'Maintenez ce niveau et variez vos exercices pour continuer à progresser.'
            ];
        }

        // Basé sur la consistance
        if ($consistency < 50) {
            $recommendations[] = [
                'title' => 'Amélioration de la régularité',
                'description' => 'Essayez de pratiquer au moins 4 fois par semaine pour améliorer votre consistance.'
            ];
        }

        // Basé sur le nombre de sessions
        if ($totalSessions < 10) {
            $recommendations[] = [
                'title' => 'Accumulation d\'expérience',
                'description' => 'Continuez à accumuler des sessions pour renforcer votre habitude.'
            ];
        }

        return $recommendations;
    }

    private function generateDetailedAnalysis($habit, $logs, $progress, $consistency, $trend)
    {
        $totalSessions = $logs->count();
        $bestValue = $logs->max('value') ?? 0;
        $averageValue = $totalSessions > 0 ? round($logs->avg('value'), 1) : 0;

        return "ANALYSE COMPLÈTE DE VOS PERFORMANCES

📊 **RÉSUMÉ GÉNÉRAL**
• Habitude analysée : {$habit->title}
• Période : 14 derniers jours
• Sessions totales : {$totalSessions}
• Progression objectif : {$progress}%
• Régularité : {$consistency}% de jours actifs
• Tendance : {$trend}

🎯 **PERFORMANCES**
• Valeur moyenne par session : {$averageValue}
• Meilleure performance : {$bestValue}
• Calories totales dépensées : " . ($logs->sum('value')) . " kCal

💪 **POINTS FORTS**
" . ($consistency >= 70 ? "• Excellente régularité dans votre pratique\n" : "") .
($progress >= 50 ? "• Bonne progression vers vos objectifs\n" : "") .
($bestValue >= 20 ? "• Performances élevées atteintes\n" : "") .

"🔧 **AXES D'AMÉLIORATION**
" . ($consistency < 50 ? "• Augmenter la fréquence des sessions\n" : "") .
($progress < 30 ? "• Accélérer le rythme de progression\n" : "") .

"📈 **RECOMMANDATION GLOBALE**
" . ($trend === '📈 En amélioration' ? 
    "Continuez sur cette excellente dynamique ! Votre progression est constante." :
    ($trend === '📉 En baisse' ? 
    "Redoublez d'efforts pour inverser la tendance. La régularité est clé." :
    "Maintenez votre rythme actuel tout en visant une légère amélioration."));
    }

    private function generateReportData($habit, $logs, $progress)
{
    // Données par jour (7 derniers jours)
    $logsByDate = $logs->groupBy(function($log) {
        return Carbon::parse($log->logged_at)->format('Y-m-d');
    });

    $dailyDetails = [];
    $totalCalories = 0;
    
    for ($i = 6; $i >= 0; $i--) {
        $date = Carbon::now()->subDays($i);
        $dateKey = $date->format('Y-m-d');
        $dayLabel = $date->format('d/m');
        
        $dayLogs = $logsByDate[$dateKey] ?? collect();
        $habitsCount = $dayLogs->count();
        $dayCalories = $dayLogs->sum('value');
        $averageValue = $habitsCount > 0 ? round($dayLogs->avg('value'), 1) : 0;
        
        $totalCalories += $dayCalories;
        
        $dailyDetails[] = [
            'date' => $dayLabel,
            'habits_count' => $habitsCount,
            'calories' => $dayCalories,
            'average_value' => $averageValue
        ];
    }

    // Statistiques globales pour les 6 cartes
    $totalSessions = $logs->count();
    $activeDays = count(array_filter($dailyDetails, function($day) {
        return $day['habits_count'] > 0;
    }));
    $averagePerDay = $activeDays > 0 ? round($totalSessions / $activeDays, 1) : 0;
    $averageValue = $totalSessions > 0 ? round($logs->avg('value'), 1) : 0;
    $bestValue = $logs->max('value') ?? 0;
    $consistency = round(($activeDays / 7) * 100);
    
    // Période analysée
    $periodStart = Carbon::now()->subDays(6)->format('d/m/Y');
    $periodEnd = Carbon::now()->format('d/m/Y');
    
    // Tendance
    $firstDays = array_slice($dailyDetails, 0, 3);
    $lastDays = array_slice($dailyDetails, 4, 3);
    $firstDaysTotal = array_sum(array_column($firstDays, 'habits_count'));
    $lastDaysTotal = array_sum(array_column($lastDays, 'habits_count'));
    
    $trend = $lastDaysTotal > $firstDaysTotal ? '📈 En amélioration' : 
            ($lastDaysTotal < $firstDaysTotal ? '📉 En baisse' : '➡️ Stable');

    // Recommandations personnalisées
    $recommendations = $this->generateRecommendations($habit, $logs, $progress, $consistency);
    
    // Analyse détaillée
    $detailedAnalysis = $this->generateDetailedAnalysis($habit, $logs, $progress, $consistency, $trend, $totalSessions, $averageValue);

    return [
        'dailyDetails' => $dailyDetails,
        'totalSessions' => $totalSessions,
        'totalDays' => $activeDays,
        'averagePerDay' => $averagePerDay,
        'averageValue' => $averageValue,
        'bestValue' => $bestValue,
        'totalCalories' => $totalCalories,
        'consistency' => $consistency,
        'periodStart' => $periodStart,
        'periodEnd' => $periodEnd,
        'trend' => $trend,
        'recommendations' => $recommendations,
        'detailedAnalysis' => $detailedAnalysis
    ];
}
}