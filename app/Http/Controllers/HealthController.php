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
        $logs = auth()->user()->healthLogs()->orderByDesc('date')->get();
        return view('health.healthlog', compact('logs'));
    }

    // Ajouter un log
    public function store(Request $request)
    {
        // Validation stricte : tous les champs sont requis et bornés
        $request->validate([
            'water' => 'required|numeric|min:0|max:10000',
            'weight' => 'required|numeric|min:0.1|max:250',
            'height' => 'required|numeric|min:30|max:230',
            'steps' => 'required|integer|min:0|max:50000',
            'food_name' => 'required|string|max:100',
            'calories' => 'required|numeric|min:0',
            'protein' => 'required|numeric|min:0',
            'carbs' => 'required|numeric|min:0',
            'fat' => 'required|numeric|min:0',
            'sleep_hours' => 'required|numeric|min:0|max:24',
            'heart_rate' => 'required|integer|min:30|max:220',
            'blood_pressure' => 'required|string|regex:/^\d{2,3}\/\d{2,3}$/',
        ], [
            'required' => 'Ce champ est obligatoire.',
            'max' => 'Valeur maximale dépassée.',
            'min' => 'Valeur minimale invalide.',
            'regex' => 'Format invalide.',
        ]);

        $log = new HealthLog();
        $log->user_id = Auth::id();
        $log->date = now()->toDateString();
        $log->water = $request->water;
        $log->weight = $request->weight;
        $log->height = $request->height;
        $log->steps = $request->steps;
        $log->food_name = $request->food_name;
        $log->calories = $request->calories;
        $log->protein = $request->protein;
        $log->carbs = $request->carbs;
        $log->fat = $request->fat;
        $log->sleep_hours = $request->sleep_hours;
        $log->heart_rate = $request->heart_rate;
        $log->blood_pressure = $request->blood_pressure;
        $log->save();

        return redirect()->route('health.index')->with('success', 'Log santé ajouté avec succès !');
    }

    // Supprimer un log
    public function destroy(HealthLog $healthLog)
    {
        if ($healthLog->user_id !== Auth::id()) {
            abort(403, 'Action non autorisée');
        }

        $healthLog->delete();
        return back()->with('success', 'Log supprimé');
    }

    // Ajoutez cette méthode pour corriger l'erreur
    public function logs()
    {
        $logs = auth()->user()->healthLogs()->orderByDesc('date')->get();
        return view('health.healthlog', compact('logs'));
    }
}
