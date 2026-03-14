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
        $request->validate([
            'title'=>'required|string|max:255',
            'author'=>'required|string|max:255',
            'price'=>'required|numeric|min:0',
            'available'=>'required|boolean',
        ]);

        Book::create($request->all());
        return redirect()->route('books.index')->with('success','Livre ajouté avec succès');
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
            'available'=>'required|boolean',
        ]);

        $book->update($request->all());
        return redirect()->route('books.index')->with('success','Livre mis à jour avec succès');
    }

    // Supprimer livre
    public function destroy(Book $book)
    {
        $book->delete();
        return redirect()->route('books.index')->with('success','Livre supprimé avec succès');
    }
}
