<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;

class AdminBookController extends Controller
{
    // Liste des livres
    public function index() {
        $books = Book::all();
        return view('admin.books.index', compact('books')); // juste les livres
    }

    // Formulaire ajout
    public function create() {
        return view('admin.books.create');
    }

    // Ajouter un livre
    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'available' => 'required|integer|min:0', // quantité
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Upload image
        $imagePath = $request->hasFile('image') 
            ? $request->file('image')->store('books', 'public') 
            : null;

        // Création du livre
        Book::create([
            'title' => $request->title,
            'author' => $request->author,
            'description' => $request->description,
            'price' => $request->price,
            'available' => $request->available, // quantité choisie
            'image' => $imagePath,
        ]);

        return redirect()->route('books.index')->with('success', 'Livre ajouté avec succès !');
    }

    // Formulaire édition
    public function edit(Book $book) {
        return view('admin.books.edit', compact('book'));
    }

    // Mettre à jour le livre
    public function update(Request $r, Book $book) {
        $book->update($r->only('title','author','description','price','available'));
        return redirect()->route('books.index')->with('success', 'Livre mis à jour !');
    }

    // Supprimer le livre
    public function destroy(Book $book) {
        $book->delete();
        return redirect()->route('books.index')->with('success', 'Livre supprimé !');
    }
}
