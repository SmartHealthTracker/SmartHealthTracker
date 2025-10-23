<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Prediction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReportController extends Controller
{
    // Rapports personnalisés
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

    // Prédictions basées sur tendances
    public function predictions()
    {
        $userId = Auth::id();

        // Calculs simples : Moyenne sur 4 dernières semaines
        $now = Carbon::now();
        $fourWeeksAgo = $now->copy()->subWeeks(4);

        $historicalLogs = ActivityLog::where('user_id', $userId)
            ->where('date', '>=', $fourWeeksAgo)
            ->get();

        $weeklyAverages = [];
        for ($i = 0; $i < 4; $i++) {
            $weekStart = $fourWeeksAgo->copy()->addWeeks($i)->startOfWeek();
            $weekEnd = $weekStart->copy()->endOfWeek();
            $weeklyCalories = $historicalLogs->whereBetween('date', [$weekStart, $weekEnd])->sum('calories_burned');
            $weeklyAverages[] = $weeklyCalories;
        }

        $averageCalories = count($weeklyAverages) > 0 ? array_sum($weeklyAverages) / count($weeklyAverages) : 0;

        // Prédiction pour la semaine suivante (simple : même que la moyenne)
        $predictedCalories = round($averageCalories);

        // Détection de baisse : Si la dernière semaine < moyenne - 20%
        $lastWeekCalories = end($weeklyAverages);
        $trendAlert = ($lastWeekCalories < $averageCalories * 0.8) ? 'Baisse d\'activité détectée : Moins de 3 logs ou calories en baisse.' : 'Tendance stable.';

        // Stocker la prédiction
        $nextWeekStart = $now->addWeek()->startOfWeek();
        $nextWeekEnd = $nextWeekStart->copy()->endOfWeek();
        Prediction::updateOrCreate(
            ['user_id' => $userId, 'period_start' => $nextWeekStart],
            [
                'predicted_calories' => $predictedCalories,
                'trend_alert' => $trendAlert,
                'period_end' => $nextWeekEnd,
            ]
        );

        // Récupérer les prédictions stockées
        $predictions = Prediction::where('user_id', $userId)->latest()->get();

        // Données pour le graphique des prédictions
        $chartLabelsPred = $predictions->pluck('period_start')->map(function ($date) {
            return \Carbon\Carbon::parse($date)->format('d/m/Y');
        });
        $chartDataPred = $predictions->pluck('predicted_calories');

        return view('pages.reports.predictions', compact('predictedCalories', 'trendAlert', 'predictions', 'chartLabelsPred', 'chartDataPred'));
    }
}