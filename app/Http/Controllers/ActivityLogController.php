<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Activity;
use App\Models\User;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    // Liste des journaux d'activités avec pagination
    public function index()
    {
        $logs = ActivityLog::with(['activity', 'user'])
            ->orderBy('date', 'desc')
            ->paginate(10); // 10 logs par page

        return view('pages.activity_logs.index', compact('logs'));
    }

    // Formulaire de création
    public function create()
    {
        $activities = Activity::all();
        $users = User::all();
        return view('pages.activity_logs.create', compact('activities', 'users'));
    }

    // Enregistrement d'un nouveau journal
    public function store(Request $request)
    {
        $request->validate([
            'activity_id' => 'required|exists:activities,id',
            'user_id' => 'required|exists:users,id',
            'duration' => 'required|integer|min:1|max:1440',
            'date' => 'required|date',
        ], [
            'activity_id.required' => 'Veuillez sélectionner une activité.',
            'activity_id.exists' => 'Cette activité n’existe pas.',
            'user_id.required' => 'Veuillez sélectionner un utilisateur.',
            'user_id.exists' => 'Cet utilisateur n’existe pas.',
            'duration.required' => 'La durée est obligatoire.',
            'duration.integer' => 'La durée doit être un nombre entier.',
            'duration.min' => 'La durée doit être supérieure à 0 minute.',
            'duration.max' => 'La durée ne peut dépasser 1440 minutes (24h).',
            'date.required' => 'La date est obligatoire.',
            'date.date' => 'La date est invalide.',
        ]);

        $activity = Activity::find($request->activity_id);
        $calories_burned = round(($request->duration / 60) * $activity->calories_per_hour);

        ActivityLog::create([
            'activity_id' => $request->activity_id,
            'user_id' => $request->user_id,
            'duration' => $request->duration,
            'calories_burned' => $calories_burned,
            'date' => $request->date,
        ]);

        return redirect()->route('activity_logs.index')->with('success', 'Journal d’activité créé avec succès ✅');
    }

    // Formulaire d'édition
    public function edit(ActivityLog $activity_log)
    {
        $activities = Activity::all();
        $users = User::all();
        return view('pages.activity_logs.edit', compact('activity_log', 'activities', 'users'));
    }

    // Mise à jour du journal
    public function update(Request $request, ActivityLog $activity_log)
    {
        $request->validate([
            'activity_id' => 'required|exists:activities,id',
            'user_id' => 'required|exists:users,id',
            'duration' => 'required|integer|min:1|max:1440',
            'date' => 'required|date',
        ]);

        $activity = Activity::find($request->activity_id);
        $calories_burned = round(($request->duration / 60) * $activity->calories_per_hour);

        $activity_log->update([
            'activity_id' => $request->activity_id,
            'user_id' => $request->user_id,
            'duration' => $request->duration,
            'calories_burned' => $calories_burned,
            'date' => $request->date,
        ]);

        return redirect()->route('activity_logs.index')->with('success', 'Journal d’activité mis à jour avec succès ✅');
    }

    // Suppression du journal
    public function destroy(ActivityLog $activity_log)
    {
        $activity_log->delete();
        return redirect()->route('activity_logs.index')->with('success', 'Journal d’activité supprimé 🗑️');
    }
}
