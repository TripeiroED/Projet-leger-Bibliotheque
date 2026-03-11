<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Borrow;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class BorrowController extends Controller
{
    // -------------------------------
    // Emprunter un livre avec paiement simulé
    // -------------------------------
    public function borrow(Request $request, $book_id)
    {
        /** @var Book $book */
        $book = Book::findOrFail($book_id);

        if (!$book->available) {
            return back()->with('error', 'Livre non disponible');
        }

        if (!$request->has('paid') || $request->paid != 'true') {
            return back()->with('error', 'Veuillez payer pour emprunter le livre.');
        }

        Borrow::create([
            'user_id' => Auth::id(),
            'book_id' => $book->id,
            'paid' => true,
            'borrowed_at' => now(),
            'return_date' => now()->addDays(14),
        ]);

        $book->available = 0;
        $book->save();

        return back()->with('success', 'Livre payé et emprunté !');
    }

    // -------------------------------
    // Historique détaillé des emprunts
    // -------------------------------
    public function history()
    {
        $borrows = Borrow::where('user_id', Auth::id())
            ->with('book') // Assure-toi que la relation 'book' existe dans le modèle Borrow
            ->orderBy('borrowed_at', 'desc')
            ->get();

        return view('user.history', compact('borrows'));
    }

    // -------------------------------
    // Afficher le profil utilisateur
    // -------------------------------
    public function profile()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $totalBorrowed = Borrow::where('user_id', $user->id)->count();
        $toReturn = Borrow::where('user_id', $user->id)
                  ->whereNull('returned_at')
                  ->count();


        return view('user.profile', compact('user', 'totalBorrowed', 'toReturn'));
    }

    // -------------------------------
    // Modifier le profil utilisateur
    // -------------------------------
    public function editProfile()
    {
        return view('user.profile_edit', ['user' => Auth::user()]);
    }

    public function updateProfile(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|confirmed|min:6',
        ]);

        // ⚡ Utilisation de fill() + save() pour Laravel et Intelephense
        $user->fill([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
        ]);

        $user->save();

        return back()->with('success', 'Profil mis à jour !');
    }

    // -------------------------------
    // Ajouter un livre aux favoris / wishlist
    // -------------------------------
    public function addFavorite($book_id)
    {
        /** @var Book $book */
        $book = Book::findOrFail($book_id);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->favorites()->where('book_id', $book->id)->exists()) {
            $user->favorites()->attach($book->id);
            return back()->with('success', 'Livre ajouté aux favoris !');
        }

        return back()->with('info', 'Livre déjà dans vos favoris.');
    }

    // -------------------------------
    // Retirer un livre des favoris
    // -------------------------------
    public function removeFavorite($book_id)
    {
        /** @var Book $book */
        $book = Book::findOrFail($book_id);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $user->favorites()->detach($book->id);
        return back()->with('success', 'Livre retiré des favoris !');
    }

    // -------------------------------
    // Afficher les favoris
    // -------------------------------
    public function favorites()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // ⚡ Pas besoin de with('book') car favorites() renvoie déjà les books
        $books = $user->favorites()->get();

        return view('user.favorites', compact('books'));
    }
}
