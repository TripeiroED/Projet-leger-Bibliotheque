<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;

class AdminBookController extends Controller
{
    public function index(){ $books = Book::all(); return view('admin.books.index', compact('books')); }
    public function create(){ return view('admin.books.create'); }
    public function store(Request $r){ Book::create($r->only('title','author','description','price') + ['available'=>1]); return redirect()->route('books.index'); }
    public function edit(Book $book){ return view('admin.books.edit', compact('book')); }
    public function update(Request $r, Book $book){ $book->update($r->only('title','author','description','price','available')); return redirect()->route('books.index'); }
    public function destroy(Book $book){ $book->delete(); return redirect()->route('books.index'); }
}
