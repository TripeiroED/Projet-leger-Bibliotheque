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
    public function store(Request $r) {
        Book::create($r->only('title','author','description','price') + ['available'=>1]);
        return redirect()->route('books.index')->with('success', 'Livre ajouté !');
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
