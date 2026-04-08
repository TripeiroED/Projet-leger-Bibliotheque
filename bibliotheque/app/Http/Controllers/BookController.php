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

        $books = $query->paginate(8);

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

public function store(Request $request)
{
    //  Validation
    $request->validate([
        'title' => 'required|string|max:255',
        'author' => 'required|string|max:255',
        'description' => 'nullable|string',
        'price' => 'required|numeric|min:0',
        'available' => 'required|integer|min:0', // quantité
        'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    //  Upload image
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('books', 'public');
    } else {
        $imagePath = null;
    }

    //  Création du livre
    Book::create([
        'title' => $request->title,
        'author' => $request->author,
        'description' => $request->description,
        'price' => $request->price,
        'available' => $request->available, // quantité choisie
        'image' => $imagePath,
    ]);

    return redirect()->route('home')->with('success', 'Livre ajouté avec succès !');
}


}
