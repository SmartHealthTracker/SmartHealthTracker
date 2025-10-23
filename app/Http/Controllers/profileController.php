<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Affiche la page de profil
     */
    public function show()
    {
        $user = Auth::user();
        return view('profile', compact('user'));
    }

    /**
     * Affiche la page d'édition du profil
     */
    public function edit()
    {
        $user = Auth::user();
        return view('profile', compact('user')); // tu peux créer profile/edit.blade.php si tu veux séparer
    }

    /**
     * Met à jour le profil
     */
   public function update(Request $request)
{
    $user = Auth::user();

    $request->validate([
        'name'  => 'required|string|max:50',
        'email' => 'required|email|max:100|unique:users,email,' . $user->id,
    ]);

    $user->name  = $request->name;
    $user->email = $request->email;
    $user->save();

    return redirect()->route('profile.show')->with('success', 'Profil mis à jour avec succès');
}


    /**
     * Page des paramètres (exemple)
     */
    public function settings()
    {
        return view('settings');
    }
}
