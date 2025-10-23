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

    // ✅ Export PDF avec graphique
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

        // 🔹 Génération des données pour le graphique
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
}
