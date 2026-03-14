<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Afficher le formulaire de connexion
    public function showLogin()
    {
        return view('auth.login');
    }

    // Connexion
    public function login(Request $request)
    {
        // Validation
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            // Regénérer la session pour sécurité
            $request->session()->regenerate();

            $user = Auth::user();

            // 🔹 Redirection selon le rôle
            if ($user->role === 'admin') {
                return redirect()->intended('/admin')->with('success', 'Connecté en tant qu\'admin');
            } else {
                return redirect()->intended('/')->with('success', 'Connecté avec succès');
            }
        }

        return back()->with('error', 'Email ou mot de passe incorrect');
    }

    // Afficher le formulaire d'inscription
    public function showRegister()
    {
        return view('auth.register');
    }

    // Enregistrer un nouvel utilisateur
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user', // Défaut : utilisateur normal
        ]);

        return redirect('/login')->with('success', 'Compte créé avec succès');
    }

    // Déconnexion
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Déconnecté avec succès');
    }
}
