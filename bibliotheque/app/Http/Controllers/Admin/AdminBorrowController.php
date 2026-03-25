<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Borrow; // ton modèle d'emprunt
use Illuminate\Http\Request;

class AdminBorrowController extends Controller
{
    public function index()
    {
        // On récupère tous les emprunts avec l'utilisateur et le livre
        $borrows = Borrow::with(['user', 'book'])->get();

        return view('admin.borrows.index', compact('borrows'));
    }
}
