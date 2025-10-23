<?php

namespace App\Http\Controllers;

use App\Models\Participation;
use App\Models\Challenge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ParticipationStatusMail;

class ParticipationController extends Controller
{
    // List all participations
    public function index()
    {
        $participations = Participation::with('user', 'challenge')->get();
        $challenges = Challenge::all();
        return view('participations.index', compact('participations', 'challenges'));
    }

    // Store new participation
    public function store(Request $request)
    {
        $data = $request->validate([
            'challenge_id' => 'required|exists:challenges,id',
            'age' => 'required|numeric|min:0',
            'weight' => 'required|numeric|min:0',
        ]);

        $challenge = Challenge::findOrFail($data['challenge_id']);

        // Count approved/pending participants for this challenge
        $participantCount = Participation::where('challenge_id', $challenge->id)
            ->whereIn('status', ['pending','approved'])
            ->count();

        $status = $participantCount >= 5 ? 'rejected' : 'approved';

        // Create participation for logged-in user
        $participation = Participation::create([
            'challenge_id' => $challenge->id,
            'user_id' => auth()->id(),
            'age' => $data['age'],
            'weight' => $data['weight'],
            'status' => $status,
        ]);

        // Send email notification to participant
        Mail::to(auth()->user()->email)->send(new ParticipationStatusMail($participation));

        return response()->json([
            'id' => $participation->id,
            'challenge_name' => $challenge->name,
            'user_name' => auth()->user()->name,
            'status' => $status,
        ]);
    }

    // Update participation (admin or owner)
    public function update(Request $request, Participation $participation)
    {
        $data = $request->validate([
            'challenge_id' => 'required|exists:challenges,id',
            'age' => 'required|numeric|min:0',
            'weight' => 'required|numeric|min:0',
            'status' => 'nullable|in:pending,approved,rejected',
        ]);

        $statusChanged = isset($data['status']) && $data['status'] != $participation->status;

        $participation->update($data);

        // Send email if status changed
        if($statusChanged){
            Mail::to($participation->user->email)->send(new ParticipationStatusMail($participation));
        }

        return response()->json([
            'success' => true,
            'id' => $participation->id,
            'status' => $participation->status,
        ]);
    }

    // Delete participation
    public function destroy(Participation $participation)
    {
        $participation->delete();
        return response()->json(['success' => true]);
    }
}
