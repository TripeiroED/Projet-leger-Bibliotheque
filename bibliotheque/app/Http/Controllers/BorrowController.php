<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Borrow;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class BorrowController extends Controller
{
    // -------------------------------
    // Emprunter un livre
    // -------------------------------
    public function borrow(Request $request, $book_id)
{
    $book = Book::findOrFail($book_id);

    // Quantité demandée (par défaut 1 si rien envoyé)
    $qty = max(1, intval($request->quantity ?? 1));

    // Vérifie la disponibilité
    if ($qty > $book->available) {
        return back()->with('error', "Impossible d'emprunter « {$book->title} » : stock insuffisant (disponible : {$book->available}).");
    }

    // Vérifie le paiement
    if (!$request->has('paid') || $request->paid != 'true') {
        return back()->with('error', 'Veuillez payer pour emprunter le livre.');
    }

    // Crée l'emprunt avec la bonne quantité
    Borrow::create([
        'user_id' => Auth::id(),
        'book_id' => $book->id,
        'quantity' => $qty,
        'paid' => true,
        'borrowed_at' => now(),
        'due_at' => now()->addDays(14), // 14 jours max
    ]);

    // Met à jour le stock du livre
    $book->available -= $qty;
    $book->save();

    return back()->with('success', "Vous avez emprunté {$qty} exemplaire(s) de « {$book->title} » !");
}


    // -------------------------------
    // Mes emprunts
    // -------------------------------
    public function myBorrows()
    {
        $borrows = Borrow::where('user_id', Auth::id())
            ->with('book')
            ->orderBy('created_at', 'desc')
            ->get();

            // Total des livres empruntés depuis l'inscription
        $totalBorrowed = Borrow::where('user_id', Auth::id())
            ->sum('quantity');

        return view('borrows.index', compact('borrows', 'totalBorrowed'));
    }

    // -------------------------------
    // Rendre un livre
    // -------------------------------
    public function returnBook($id)
    {
        $borrow = Borrow::findOrFail($id);

        if ($borrow->user_id != Auth::id()) {
            return back()->with('error', 'Action non autorisée');
        }

        if ($borrow->returned_at) {
            return back()->with('error', 'Livre déjà rendu');
        }

        $borrow->returned_at = now();
        $borrow->save();

        $book = $borrow->book;
        $book->available += $borrow->quantity; // <- ajoute la quantité réelle
        $book->save();

        return back()->with('success', 'Livre rendu avec succès !');
    }

    // -------------------------------
    // Historique
    // -------------------------------
    public function history()
    {
        $borrows = Borrow::where('user_id', Auth::id())
            ->with('book')
            ->orderBy('borrowed_at', 'desc')
            ->get();

        return view('user.history', compact('borrows'));
    }

    // -------------------------------
    // Profil
    // -------------------------------
    public function profile()
    {
        /** @var User $user */
        $user = Auth::user();

        // Quantité totale empruntée depuis l'inscription (somme de toutes les colonnes 'quantity')
        $totalBorrowed = Borrow::where('user_id', $user->id)
            ->sum('quantity');

        // Quantité de livres encore à rendre
        $toReturn = Borrow::where('user_id', $user->id)
            ->whereNull('returned_at')
            ->sum('quantity');

        // Quantité empruntée par livre
        $booksBorrowed = Borrow::where('user_id', $user->id)
            ->with('book')
            ->select('book_id')
            ->selectRaw('SUM(quantity) as total_quantity')
            ->groupBy('book_id')
            ->get();

        return view('user.profile', compact('user', 'totalBorrowed', 'toReturn', 'booksBorrowed'));
    }


    // -------------------------------
    // Edit profil
    // -------------------------------
    public function editProfile()
    {
        return view('user.profile_edit', ['user' => Auth::user()]);
    }

    public function updateProfile(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|confirmed|min:6',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return back()->with('success', 'Profil mis à jour !');
    }

    // -------------------------------
    // Favoris
    // -------------------------------
    public function addFavorite($book_id)
    {
        $book = Book::findOrFail($book_id);

        /** @var User $user */
        $user = Auth::user();

        if (!$user->favorites()->where('book_id', $book->id)->exists()) {
            $user->favorites()->attach($book->id);
            return back()->with('success', 'Livre ajouté aux favoris !');
        }

        return back()->with('info', 'Livre déjà dans vos favoris.');
    }

    public function removeFavorite($book_id)
    {
        $book = Book::findOrFail($book_id);

        /** @var User $user */
        $user = Auth::user();

        $user->favorites()->detach($book->id);

        return back()->with('success', 'Livre retiré des favoris !');
    }

    public function favorites()
    {
        /** @var User $user */
        $user = Auth::user();

        $books = $user->favorites()->get();

        return view('user.favorites', compact('books'));
    }
}
