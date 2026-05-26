<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
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
            return back()->with('error', 'Trop de tentatives. Reessaie dans 1 minute.');
        }

        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            RateLimiter::clear($key);
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->role === 'admin') {
                return redirect()->intended('/admin')->with('success', 'Connecte en tant qu\'admin');
            }

            if (! $user->hasVerifiedEmail()) {
                return redirect()->route('verification.notice')
                    ->with('success', 'Verifie ton email pour activer ton compte.');
            }

            return redirect()->intended('/')->with('success', 'Connecte avec succes');
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

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
        ]);

        $user->sendEmailVerificationNotification();

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('verification.notice')
            ->with('success', 'Compte cree. Un email de verification vous a ete envoye.');
    }

    public function verify(Request $request, $id, $hash)
    {
        $user = User::findOrFail($id);

        if (! hash_equals((string) $hash, sha1($user->email))) {
            abort(403);
        }

        if (! $user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        Auth::login($user);
        $request->session()->regenerate();

        return redirect('/')->with('success', 'Email verifie !');
    }

    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect('/')->with('success', 'Votre email est deja verifie.');
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('success', 'Email de verification renvoye !');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Deconnecte avec succes');
    }
}
