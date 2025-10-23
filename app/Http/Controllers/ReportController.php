<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Prediction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    /**
     * 📊 Affichage du rapport filtré (avec graphique web)
     */
    public function index(Request $request)
    {
        $userId = Auth::id();

        $period = $request->get('period', 'weekly');
        $startDate = $request->get('start_date', Carbon::now()->startOfWeek()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->endOfWeek()->format('Y-m-d'));

        if ($period === 'monthly') {
            $startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
            $endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
        } elseif ($period === 'custom' && $request->filled('start_date') && $request->filled('end_date')) {
            $startDate = $request->start_date;
            $endDate = $request->end_date;
        }

        $logs = ActivityLog::where('user_id', $userId)
            ->whereBetween('date', [$startDate, $endDate])
            ->with('activity')
            ->get();

        $totalCalories = $logs->sum('calories_burned');
        $totalHours = $logs->sum('duration') / 60;

        $repartition = $logs->groupBy('activity.name')->map(function ($group) {
            return [
                'calories' => $group->sum('calories_burned'),
                'hours' => $group->sum('duration') / 60,
            ];
        });

        $chartLabels = $repartition->keys();
        $chartData = $repartition->pluck('calories');
        $chartDataHours = $repartition->pluck('hours');

        return view('pages.reports.index', compact(
            'totalCalories', 'totalHours', 'repartition', 'period', 'startDate', 'endDate',
            'chartLabels', 'chartData', 'chartDataHours'
        ));
    }

    /**
     * 🧾 Télécharger le rapport PDF avec graphique
     */
    public function downloadPdf(Request $request)
    {
        $userId = Auth::id();

        $period = $request->get('period', 'weekly');
        $startDate = $request->get('start_date', Carbon::now()->startOfWeek()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->endOfWeek()->format('Y-m-d'));

        if ($period === 'monthly') {
            $startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
            $endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
        } elseif ($period === 'custom' && $request->filled('start_date') && $request->filled('end_date')) {
            $startDate = $request->start_date;
            $endDate = $request->end_date;
        }

        $logs = ActivityLog::where('user_id', $userId)
            ->whereBetween('date', [$startDate, $endDate])
            ->with('activity')
            ->get();

        $totalCalories = $logs->sum('calories_burned');
        $totalHours = $logs->sum('duration') / 60;

        $repartition = $logs->groupBy('activity.name')->map(function ($group) {
            return $group->sum('calories_burned');
        });

        $labels = $repartition->keys()->toArray();
        $data = $repartition->values()->toArray();

        // 🔹 Génération d’un lien d’image via QuickChart API
        $chartUrl = "https://quickchart.io/chart?c=" . urlencode(json_encode([
            "type" => "bar",
            "data" => [
                "labels" => $labels,
                "datasets" => [[
                    "label" => "Calories brûlées",
                    "data" => $data,
                    "backgroundColor" => "rgba(75, 192, 192, 0.6)"
                ]]
            ],
            "options" => [
                "plugins" => [
                    "title" => [
                        "display" => true,
                        "text" => "Répartition des Calories par Activité"
                    ]
                ]
            ]
        ]));

        $pdf = Pdf::loadView('pages.reports.pdf', compact(
            'logs', 'totalCalories', 'totalHours', 'startDate', 'endDate', 'chartUrl'
        ));

        return $pdf->download('rapport_activite.pdf');
    }

    /**
     * 🔮 Prédictions d'activité (basées sur les tendances)
     */
    public function predictions()
    {
        $userId = Auth::id();
        $now = Carbon::now();
        $oneYearAgo = $now->copy()->subYear();

        $historicalLogs = ActivityLog::where('user_id', $userId)
            ->where('date', '>=', $oneYearAgo)
            ->orderBy('date')
            ->get();

        $groupedByWeek = $historicalLogs->groupBy(function ($log) {
            return Carbon::parse($log->date)->startOfWeek()->format('Y-m-d');
        });

        $weeklyCalories = $groupedByWeek->map(function ($group) {
            return $group->sum('calories_burned');
        })->sortKeys();

        $weeks = $weeklyCalories->keys();
        $numWeeks = $weeklyCalories->count();

        if ($numWeeks < 2) {
            $averageCalories = $numWeeks > 0 ? max(2500, $weeklyCalories->average()) : 2500;
            $m = 10;
            $b = $averageCalories;
        } else {
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
                $m = 10;
                $b = $sum_y / $n;
            } else {
                $m = (($n * $sum_xy) - ($sum_x * $sum_y)) / $denom;
                $b = ($sum_y - ($m * $sum_x)) / $n;
            }
        }

        $futurePredictions = [];
        $predictedWeek = 0;
        $predictedMonth = 0;
        $predicted3Months = 0;

        for ($i = 1; $i <= 12; $i++) {
            $predicted = max(500, round($m * ($numWeeks + $i) + $b));
            $futurePredictions[$i] = $predicted;

            if ($i == 1) $predictedWeek = $predicted;
            if ($i <= 4) $predictedMonth += $predicted;
            $predicted3Months += $predicted;

            $trendAlert = ($m < -10) ? 'Baisse d\'activité détectée.' :
                (($m > 10) ? 'Hausse d\'activité détectée.' : 'Tendance stable.');

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

        $predictions = Prediction::where('user_id', $userId)
            ->where('period_start', '>', $now)
            ->orderBy('period_start')
            ->get();

        $chartLabelsPred = $predictions->pluck('period_start')->map(function ($date) {
            return Carbon::parse($date)->format('d/m/Y');
        });

        $chartDataPred = $predictions->pluck('predicted_calories');

        return view('pages.reports.predictions', compact(
            'predictedWeek', 'predictedMonth', 'predicted3Months',
            'trendAlert', 'predictions', 'chartLabelsPred', 'chartDataPred'
        ));
    }
}
