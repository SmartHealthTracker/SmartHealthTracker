<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Prediction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReportController extends Controller
{
    // Rapports personnalisés (inchangé)
    public function index(Request $request)
    {
        $userId = Auth::id(); // Utilisateur connecté (adaptez si multi-utilisateurs)

        // Filtres par défaut : dernière semaine
        $period = $request->get('period', 'weekly');
        $startDate = $request->get('start_date', Carbon::now()->startOfWeek()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->endOfWeek()->format('Y-m-d'));

        // Ajuster selon la période sélectionnée
        if ($period === 'monthly') {
            $startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
            $endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
        } elseif ($period === 'custom' && $request->filled('start_date') && $request->filled('end_date')) {
            $startDate = $request->start_date;
            $endDate = $request->end_date;
        }

        // Queries agrégées
        $logs = ActivityLog::where('user_id', $userId)
            ->whereBetween('date', [$startDate, $endDate])
            ->with('activity')
            ->get();

        $totalCalories = $logs->sum('calories_burned');
        $totalHours = $logs->sum('duration') / 60; // En heures
        $repartition = $logs->groupBy('activity.name')->map(function ($group) {
            return [
                'calories' => $group->sum('calories_burned'),
                'hours' => $group->sum('duration') / 60,
            ];
        });

        // Données pour Chart.js (labels et data pour bar chart)
        $chartLabels = $repartition->keys();
        $chartData = $repartition->pluck('calories');
        $chartDataHours = $repartition->pluck('hours');

        return view('pages.reports.index', compact(
            'totalCalories', 'totalHours', 'repartition', 'period', 'startDate', 'endDate', 'chartLabels', 'chartData', 'chartDataHours'
        ));
    }

    // Prédictions basées sur tendances (modifié pour valeurs variables)
    public function predictions()
    {
        $userId = Auth::id();
        $now = Carbon::now();

        // Récupérer les logs historiques (jusqu'à 1 an en arrière pour plus de données)
        $oneYearAgo = $now->copy()->subYear();
        $historicalLogs = ActivityLog::where('user_id', $userId)
            ->where('date', '>=', $oneYearAgo)
            ->orderBy('date')
            ->get();

        // Grouper par semaine pour obtenir les calories hebdomadaires
        $groupedByWeek = $historicalLogs->groupBy(function ($log) {
            return Carbon::parse($log->date)->startOfWeek()->format('Y-m-d');
        });

        $weeklyCalories = $groupedByWeek->map(function ($group) {
            return $group->sum('calories_burned');
        })->sortKeys();

        $weeks = $weeklyCalories->keys();
        $numWeeks = $weeklyCalories->count();

        // Fallback logique si peu de données (enlever constantes basses comme 2)
        if ($numWeeks < 2) {
            $averageCalories = $numWeeks > 0 ? max(2500, $weeklyCalories->average()) : 2500; // Valeur minimale logique
            $m = 10; // Légère hausse pour variabilité
            $b = $averageCalories;
        } else {
            // Calcul de régression linéaire simple
            $x = range(1, $numWeeks);
            $y = $weeklyCalories->values()->toArray();

            $sum_x = array_sum($x);
            $sum_y = array_sum($y);
            $sum_xy = 0;
            $sum_x2 = 0;

            foreach ($x as $i => $xi) {
                $sum_xy += $xi * $y[$i];
                $sum_x2 += $xi * $xi;
            }

            $n = $numWeeks;
            $denom = ($n * $sum_x2) - ($sum_x * $sum_x);

            if ($denom == 0) {
                $m = 10; // Minimum pour variabilité
                $b = $sum_y / $n;
            } else {
                $m = (($n * $sum_xy) - ($sum_x * $sum_y)) / $denom;
                $b = ($sum_y - ($m * $sum_x)) / $n;
            }
        }

        // Prédire pour les 12 prochaines semaines (3 mois)
        $futurePredictions = [];
        $predictedWeek = 0;
        $predictedMonth = 0; // Prochain mois (~4 semaines)
        $predicted3Months = 0; // Prochains 3 mois (~12 semaines)

        for ($i = 1; $i <= 12; $i++) {
            $predicted = max(500, round($m * ($numWeeks + $i) + $b)); // Min 500 pour logique, arrondi
            $futurePredictions[$i] = $predicted;

            // Cumuler pour les périodes
            if ($i == 1) {
                $predictedWeek = $predicted;
            }
            if ($i <= 4) {
                $predictedMonth += $predicted;
            }
            $predicted3Months += $predicted;

            // Déterminer l'alerte de tendance basée sur la pente (m)
            $trendAlert = ($m < -10) ? 'Baisse d\'activité détectée.' : (($m > 10) ? 'Hausse d\'activité détectée.' : 'Tendance stable.');

            // Stocker la prédiction hebdomadaire
            $weekStart = $now->copy()->addWeeks($i)->startOfWeek();
            $weekEnd = $weekStart->copy()->endOfWeek();
            Prediction::updateOrCreate(
                ['user_id' => $userId, 'period_start' => $weekStart],
                [
                    'predicted_calories' => $predicted,
                    'trend_alert' => $trendAlert,
                    'period_end' => $weekEnd,
                ]
            );
        }

        // Récupérer toutes les prédictions futures pour le graphique
        $predictions = Prediction::where('user_id', $userId)
            ->where('period_start', '>', $now)
            ->orderBy('period_start')
            ->get();

        // Données pour le graphique : Évolution hebdomadaire des prédictions
        $chartLabelsPred = $predictions->pluck('period_start')->map(function ($date) {
            return Carbon::parse($date)->format('d/m/Y');
        });
        $chartDataPred = $predictions->pluck('predicted_calories');

        // Passer les prédictions agrégées à la vue
        return view('pages.reports.predictions', compact(
            'predictedWeek', 'predictedMonth', 'predicted3Months', 'trendAlert',
            'predictions', 'chartLabelsPred', 'chartDataPred'
        ));
    }
}