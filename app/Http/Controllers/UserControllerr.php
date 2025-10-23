<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserControllerr extends Controller
{
    /**
     * Affiche la liste des utilisateurs.
     */
    public function index()
    {
        $users = User::all();
        return view('pages.user-pages.deleteusers', compact('users'));
    }

    /**
     * Supprime un utilisateur spécifique.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Utilisateur supprimé avec succès.');
    }

    /**
     * Supprime tous les utilisateurs directement.
     */
    public function deleteAll()
    {
        User::truncate(); // Supprime tous les utilisateurs
        return redirect()->route('users.index')->with('success', 'Tous les utilisateurs ont été supprimés avec succès.');
    }
    // app/Http/Controllers/UserController.php
public function toggleBlock(User $user)
{
    $user->is_blocked = !$user->is_blocked;
    $user->save();

    $status = $user->is_blocked ? 'bloqué' : 'débloqué';
    return redirect()->route('users.index')->with('success', "L'utilisateur a été $status avec succès.");
}

}
