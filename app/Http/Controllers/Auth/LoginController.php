<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    // Afficher le formulaire de login
    public function showLoginForm()
    {
        return view('pages.user-pages.login');
    }

    // Gérer la tentative de login
    public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required|string',
    ]);

    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials, $request->filled('remember'))) {
        $request->session()->regenerate();

        $user = Auth::user();

        // 🔹 Redirection selon le rôle
        if ($user->role === 'admin') {
            return redirect('/'); // admin → dashboard
        } else {
            return redirect('/home'); // user → home
        }
    }

    return back()->withErrors([
        'email' => 'Les informations de connexion sont incorrectes.',
    ])->withInput($request->except('password'));
}


    // Déconnexion
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/user-pages/login');
    }
}
