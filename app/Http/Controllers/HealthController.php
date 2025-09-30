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
        $data = $request->only([
            'water', 'weight', 'height', 'steps',
            'food_name', 'calories', 'protein', 'carbs', 'fat',
            'sleep_hours', 'heart_rate', 'blood_pressure'
        ]);

        // Validation personnalisée
        $errors = [];

        if (empty($data['water']) || !is_numeric($data['water']) || $data['water'] < 0) {
            $errors[] = 'Veuillez saisir une quantité d\'eau valide.';
        }
        if (empty($data['weight']) || !is_numeric($data['weight']) || $data['weight'] <= 0) {
            $errors[] = 'Veuillez saisir un poids valide.';
        }
        if (empty($data['height']) || !is_numeric($data['height']) || $data['height'] <= 0) {
            $errors[] = 'Veuillez saisir une taille valide.';
        }
        if (empty($data['steps']) || !is_numeric($data['steps']) || $data['steps'] < 0) {
            $errors[] = 'Veuillez saisir un nombre de pas valide.';
        }
        if (empty($data['food_name'])) {
            $errors[] = 'Veuillez saisir le nom de l\'aliment.';
        }
        if (empty($data['calories']) || !is_numeric($data['calories']) || $data['calories'] < 0) {
            $errors[] = 'Veuillez saisir un nombre de calories valide.';
        }
        if (empty($data['protein']) || !is_numeric($data['protein']) || $data['protein'] < 0) {
            $errors[] = 'Veuillez saisir une quantité de protéines valide.';
        }
        if (empty($data['carbs']) || !is_numeric($data['carbs']) || $data['carbs'] < 0) {
            $errors[] = 'Veuillez saisir une quantité de glucides valide.';
        }
        if (empty($data['fat']) || !is_numeric($data['fat']) || $data['fat'] < 0) {
            $errors[] = 'Veuillez saisir une quantité de lipides valide.';
        }
        if (empty($data['sleep_hours']) || !is_numeric($data['sleep_hours']) || $data['sleep_hours'] < 0 || $data['sleep_hours'] > 24) {
            $errors[] = 'Veuillez saisir un nombre d\'heures de sommeil valide.';
        }
        if (empty($data['heart_rate']) || !is_numeric($data['heart_rate']) || $data['heart_rate'] < 30 || $data['heart_rate'] > 220) {
            $errors[] = 'Veuillez saisir une fréquence cardiaque valide.';
        }
        if (empty($data['blood_pressure']) || !preg_match('/^\d{2,3}\/\d{2,3}$/', $data['blood_pressure'])) {
            $errors[] = 'Veuillez saisir une tension artérielle valide (ex: 120/80).';
        }

        // Si des erreurs existent, rediriger avec les messages
        if (!empty($errors)) {
            return redirect()->back()->withErrors($errors)->withInput();
        }

        // Sinon, créer le log
        $log = new HealthLog();
        $log->user_id = Auth::id();
        $log->date = now()->toDateString();
        $log->water = $data['water'];
        $log->weight = $data['weight'];
        $log->height = $data['height'];
        $log->steps = $data['steps'];
        $log->food_name = $data['food_name'];
        $log->calories = $data['calories'];
        $log->protein = $data['protein'];
        $log->carbs = $data['carbs'];
        $log->fat = $data['fat'];
        $log->sleep_hours = $data['sleep_hours'];
        $log->heart_rate = $data['heart_rate'];
        $log->blood_pressure = $data['blood_pressure'];
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
}
