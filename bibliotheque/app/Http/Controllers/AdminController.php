<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\User;

class AdminController extends Controller
{
    public function dashboard(){
        $bookCount = Book::count();
        $userCount = User::count();
        return view('admin.dashboard', compact('bookCount','userCount'));
    }
}
