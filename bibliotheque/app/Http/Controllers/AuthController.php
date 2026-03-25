<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;

class AuthController extends Controller
{
    // Afficher le formulaire de connexion
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
{
    $key = 'login-'.$request->ip();

    if (RateLimiter::tooManyAttempts($key, 5)) {
        return back()->with('error', 'Trop de tentatives. Réessaie dans 1 minute.');
    }

    // Validation
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (Auth::attempt($credentials)) {
        RateLimiter::clear($key);

        // sécurité session
        $request->session()->regenerate();

        $user = Auth::user();

        if ($user->role !== 'admin' && !$user->hasVerifiedEmail()) {
            Auth::logout();
            return back()->with('error', 'Veuillez vérifier votre email avant de vous connecter.');
        }

        if ($user->role === 'admin') {
            return redirect()->intended('/admin')->with('success', 'Connecté en tant qu\'admin');
        }

        return redirect()->intended('/')->with('success', 'Connecté avec succès');
    }

    RateLimiter::hit($key, 60);

    return back()->with('error', 'Email ou mot de passe incorrect');
}

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

        $user =User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user', // Défaut : utilisateur normal
        ]);

        $user->sendEmailVerificationNotification();

        return redirect('/login')->with('success', 'Compte créé avec succès');
    }

    public function verify(Request $request, $id, $hash)
    {
        $user = User::findOrFail($id);
        if (! hash_equals((string) $hash, sha1($user->email))) {
            abort(403);
        }
        $user->markEmailAsVerified();
        return redirect('/')->with('success', 'Email vérifié !');
    }

    public function resend(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('success', 'Email de vérification renvoyé !');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Déconnecté avec succès');
    }
}