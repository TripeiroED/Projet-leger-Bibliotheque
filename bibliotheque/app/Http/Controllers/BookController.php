<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::query();

        // 🔹 Recherche par titre ou auteur
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('title', 'like', "%$search%")
                  ->orWhere('author', 'like', "%$search%");
        }

        // 🔹 Tri par prix ou titre
        if ($request->has('sort') && $request->sort != '') {
            if ($request->sort == 'price') $query->orderBy('price', 'asc');
            if ($request->sort == 'title') $query->orderBy('title', 'asc');
        }

        $books = $query->paginate(6);

        return view('home', compact('books'));
    }

public function toggleFavorite($book_id)
{
    $book = Book::findOrFail($book_id);

    /** @var User $user */
    $user = Auth::user(); // Utiliser Auth::user() plutôt que auth()

    if ($user->favorites->contains($book->id)) {
        $user->favorites()->detach($book->id);
        return back()->with('success', 'Livre retiré des favoris !');
    } else {
        $user->favorites()->attach($book->id);
        return back()->with('success', 'Livre ajouté aux favoris !');
    }
}

}
