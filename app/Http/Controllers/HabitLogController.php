<?php

namespace App\Http\Controllers;

use App\Models\HabitLog;
use App\Models\HabitSaif;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class HabitLogController extends Controller
{
    public function index()
    {
        $logs = HabitLog::with(['habit', 'user'])->orderBy('created_at', 'desc')->paginate(10);
        return view('habitslog.index', compact('logs'));
    }

    public function create()
    {
        $habits = HabitSaif::all();
        return view('habitslog.create', compact('habits'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'habit_saif_id' => 'required|exists:habitsaif,id', // Use habit_saif_id
            'value' => 'required|numeric',
            'logged_at' => 'required|date',
        ]);

        $validated['user_id'] = auth()->id() ?? 1;
        
        HabitLog::create($validated);

        return redirect()->route('habit-logs.index')->with('success', 'Log ajouté avec succès !');
    }

    public function show(HabitLog $habitLog)
    {
        return view('habitslog.show', compact('habitLog'));
    }

    public function edit(HabitLog $habitLog)
    {
        $habits = HabitSaif::all();
        return view('habitslog.edit', compact('habitLog', 'habits'));
    }

    public function update(Request $request, HabitLog $habitLog)
    {
        $validated = $request->validate([
            'habit_saif_id' => 'required|exists:habitsaif,id', // Use habit_saif_id
            'value' => 'required|numeric',
            'logged_at' => 'required|date',
        ]);

        $habitLog->update($validated);

        return redirect()->route('habit-logs.index')->with('success', 'Log mis à jour avec succès.');
    }

    public function destroy(HabitLog $habitLog)
    {
        $habitLog->delete();
        return redirect()->route('habit-logs.index')->with('success', 'Log supprimé avec succès.');
    }
}