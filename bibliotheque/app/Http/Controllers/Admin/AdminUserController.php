<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    // Liste des utilisateurs
    public function index()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    // Formulaire pour changer le rôle
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    // Mise à jour du rôle
    public function update(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:admin,user'
        ]);

        $user->update([
            'role' => $request->role
        ]);

        return redirect()->route('users.index')->with('success', 'Rôle mis à jour avec succès.');
    }
}
