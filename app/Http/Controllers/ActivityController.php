<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index()
    {
        $activities = Activity::all();
        return view('pages.activities.index', compact('activities'));
    }

    public function create()
    {
        return view('pages.activities.create');
    }

    public function store(Request $request)
    {
        // Validation stricte
        $request->validate([
            'name' => 'required|string|min:3|max:100|unique:activities,name',
            'calories_per_hour' => 'required|integer|min:1|max:2000',
        ], [
            'name.required' => 'Le nom de l\'activité est obligatoire.',
            'name.min' => 'Le nom doit contenir au moins 3 caractères.',
            'name.max' => 'Le nom ne peut pas dépasser 100 caractères.',
            'name.unique' => 'Cette activité existe déjà.',
            'calories_per_hour.required' => 'Les calories par heure sont obligatoires.',
            'calories_per_hour.integer' => 'Veuillez entrer un nombre entier.',
            'calories_per_hour.min' => 'La valeur doit être supérieure à 0.',
            'calories_per_hour.max' => 'La valeur ne doit pas dépasser 2000.',
        ]);

        Activity::create($request->all());

        return redirect()->route('activities.index')->with('success', 'Activité créée avec succès ✅');
    }

    public function edit(Activity $activity)
    {
        return view('pages.activities.edit', compact('activity'));
    }

    public function update(Request $request, Activity $activity)
    {
        $request->validate([
            'name' => 'required|string|min:3|max:100|unique:activities,name,' . $activity->id,
            'calories_per_hour' => 'required|integer|min:1|max:2000',
        ]);

        $activity->update($request->all());

        return redirect()->route('activities.index')->with('success', 'Activité mise à jour avec succès ✅');
    }

    public function destroy(Activity $activity)
    {
        $activity->delete();
        return redirect()->route('activities.index')->with('success', 'Activité supprimée 🗑️');
    }
}
