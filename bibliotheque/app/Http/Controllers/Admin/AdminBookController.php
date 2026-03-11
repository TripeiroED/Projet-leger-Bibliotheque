<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;

class AdminBookController extends Controller
{
    public function index()
    {
        $books = Book::all();
        return view('admin.books.index', compact('books'));
    }

    public function create()
    {
        return view('admin.books.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'=>'required',
            'author'=>'required',
            'price'=>'required|numeric',
        ]);

        Book::create([
            'title'=>$request->title,
            'author'=>$request->author,
            'description'=>$request->description,
            'price'=>$request->price,
            'available'=>1
        ]);

        return redirect()->route('books.index')->with('success','Livre ajouté');
    }

    public function edit(Book $book)
    {
        return view('admin.books.edit', compact('book'));
    }

    public function update(Request $request, Book $book)
    {
        $request->validate([
            'title'=>'required',
            'author'=>'required',
            'price'=>'required|numeric',
        ]);

        $book->update($request->only('title','author','description','price','available'));

        return redirect()->route('books.index')->with('success','Livre modifié');
    }

    public function destroy(Book $book)
    {
        $book->delete();
        return redirect()->route('books.index')->with('success','Livre supprimé');
    }
}
