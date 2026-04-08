<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;

class AdminBookController extends Controller
{
    // Liste des livres
    public function index()
    {
        $books = Book::orderBy('created_at', 'desc')->get();
        $recentBooks = Book::orderBy('created_at', 'desc')->take(5)->get();
        $bookCount = Book::count();
        $availableBooks = Book::where('available', true)->count();
        $borrowedBooks = Book::where('available', false)->count();

        return view('admin.books.index', compact(
            'books','recentBooks','bookCount','availableBooks','borrowedBooks'
        ));
    }

    // Formulaire création
    public function create()
    {
        return view('admin.books.create');
    }

    // Enregistrement nouveau livre
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
    public function edit(Book $book)
    {
        return view('admin.books.edit', compact('book'));
    }

    // Mise à jour livre
    public function update(Request $request, Book $book)
    {
        $request->validate([
            'title'=>'required|string|max:255',
            'author'=>'required|string|max:255',
            'price'=>'required|numeric|min:0',
            'available' => 'required|integer|min:0', // quantité
        ]);

        $book->update($request->only(['title', 'author', 'description', 'price', 'available']));
        return redirect()->route('books.index')->with('success','Livre mis à jour avec succès');
    }

    // Supprimer livre
    public function destroy(Book $book)
    {
        $book->delete();
        return redirect()->route('books.index')->with('success','Livre supprimé avec succès');
    }
}
