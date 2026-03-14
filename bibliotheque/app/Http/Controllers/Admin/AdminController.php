<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\User;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Stats générales
        $bookCount      = Book::count();
        $userCount      = User::count();
        $availableBooks = Book::where('available', 1)->count();
        $borrowedBooks  = Book::where('available', 0)->count();

        // Livres récents
        $recentBooks = Book::orderBy('created_at', 'desc')->take(5)->get();

        // Utilisateurs récents
        $recentUsers = User::orderBy('created_at', 'desc')->take(5)->get();

        return view('admin.dashboard', compact(
            'bookCount', 'userCount', 'availableBooks', 'borrowedBooks', 'recentBooks', 'recentUsers'
        ));
    }
}
