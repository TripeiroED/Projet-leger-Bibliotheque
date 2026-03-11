<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Vérifie si l'utilisateur est admin.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'admin') {
            // Redirige vers la page précédente si possible, sinon vers l'accueil
            return redirect()->back()->with('error', 'Accès refusé');
        }

        return $next($request);
    }
}
