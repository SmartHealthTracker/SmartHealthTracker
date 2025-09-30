<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HealthLog;
use Illuminate\Support\Facades\Auth;

class HealthController extends Controller
{
    // Affichage du dashboard
    public function index()
    {
        // Récupère tous les logs de l'utilisateur connecté, triés par date décroissante
        $logs = auth()->user()->healthLogs()->orderByDesc('date')->get();
        // Corrigez le nom de la vue :
        return view('health.healthlog', compact('logs'));
    }

    // Ajouter un log
    public function store(Request $request)
    {
        // Validation des champs
        $request->validate([
            'water' => 'nullable|numeric|min:0',
            'weight' => 'nullable|numeric|min:0',
            'height' => 'nullable|numeric|min:0',
            'food_name' => 'nullable|string|max:255',
            'calories' => 'nullable|numeric|min:0',
            'protein' => 'nullable|numeric|min:0',
            'carbs' => 'nullable|numeric|min:0',
            'fat' => 'nullable|numeric|min:0',
            'sleep_hours' => 'nullable|numeric|min:0',
            'heart_rate' => 'nullable|numeric|min:0',
            'blood_pressure' => 'nullable|string|max:20',
        ]);

        // Création du log pour l'utilisateur connecté
        $log = new \App\Models\HealthLog();
        $log->user_id = auth()->id();
        $log->date = now()->toDateString();
        $log->water = $request->water;
        $log->weight = $request->weight;
        $log->height = $request->height;
        $log->food_name = $request->food_name;
        $log->calories = $request->calories;
        $log->protein = $request->protein;
        $log->carbs = $request->carbs;
        $log->fat = $request->fat;
        $log->sleep_hours = $request->sleep_hours;
        $log->heart_rate = $request->heart_rate;
        $log->blood_pressure = $request->blood_pressure;
        $log->save();

        return redirect()->route('health.index');
    }

    // Supprimer un log
    public function destroy(HealthLog $healthLog)
    {
        // Vérification que le log appartient à l'utilisateur connecté
        if ($healthLog->user_id !== auth()->id()) {
            abort(403, 'Action non autorisée');
        }

        $healthLog->delete();
        return back()->with('success', 'Log supprimé');
    }

    // Ajoutez cette méthode pour corriger l'erreur
    public function logs()
    {
        // Récupère les logs de l'utilisateur connecté
        $logs = HealthLog::where('user_id', Auth::id())->orderBy('date', 'desc')->get();
        // Corrigez le chemin de la vue :
        return view('health.healthlog', compact('logs'));
    }
}
