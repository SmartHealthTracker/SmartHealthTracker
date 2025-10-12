<?php

namespace App\Http\Controllers;

use App\Models\Participation;
use App\Models\Challenge;
use App\Models\User;
use Illuminate\Http\Request;

class ParticipationController extends Controller
{
    // List all participations
    public function index()
    {
        $participations = Participation::with(['challenge', 'user'])->get();
        return view('participations.index', compact('participations'));
    }

    // Show create form
    public function create()
    {
        $challenges = Challenge::all();
        $users = User::all(); // Pass users to the view
        return view('participations.create', compact('challenges', 'users'));
    }

    // Store new participation
    public function store(Request $request)
    {
        $data = $request->validate([
            'challenge_id' => 'required|exists:challenges,id',
            'user_id' => 'required|exists:users,id',
            'status' => 'required|in:pending,approved,rejected',
        ]);

        Participation::create($data);

        return redirect()->route('participations.index')->with('success', 'Participation added.');
    }

    // Show edit form
    public function edit(Participation $participation)
    {
        $challenges = Challenge::all();
        $users = User::all();
        return view('participations.edit', compact('participation', 'challenges', 'users'));
    }

    // Update participation
    public function update(Request $request, Participation $participation)
    {
        $data = $request->validate([
            'challenge_id' => 'required|exists:challenges,id',
            'user_id' => 'required|exists:users,id',
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $participation->update($data);

        return redirect()->route('participations.index')->with('success', 'Participation updated.');
    }

    // Delete participation
    public function destroy(Participation $participation)
    {
        $participation->delete();
        return redirect()->route('participations.index')->with('success', 'Participation deleted.');
    }
}
