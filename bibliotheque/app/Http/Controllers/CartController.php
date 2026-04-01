<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Borrow;
use App\Models\Book;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // Afficher le panier
    public function index()
    {
        $user = Auth::user();
        $cartItems = Cart::where('user_id', $user->id)
            ->with('book')
            ->get();

        $total = $cartItems->sum(fn($item) => $item->book->price * $item->quantity);

        return view('cart.index', compact('cartItems', 'total'));
    }

    // Ajouter un livre au panier
    public function add(Book $book)
    {
        $user = Auth::user();

        $cart = Cart::firstOrCreate(
            ['user_id' => $user->id, 'book_id' => $book->id],
            ['quantity' => 0]
        );

        if ($cart->quantity < $book->available) {
            $cart->quantity += 1;
            $cart->save();
            return response()->json(['success' => true, 'message' => 'Livre ajouté au panier']);
        }

        return response()->json(['success' => false, 'message' => 'Stock insuffisant']);
    }

    // Mettre à jour la quantité
    public function update(Request $request, $id)
    {
        $cart = Cart::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $qty = max(1, intval($request->quantity));

        if ($qty > $cart->book->available) {
            return back()->with('error', "Impossible de mettre plus de {$cart->book->available} exemplaire(s) pour '{$cart->book->title}'.");
        }

        $cart->quantity = $qty;
        $cart->save();

        return back()->with('success', 'Quantité mise à jour');
    }

    // Supprimer un livre du panier
    public function remove($id)
    {
        Cart::where('id', $id)
            ->where('user_id', Auth::id())
            ->delete();

        return back()->with('success', 'Livre retiré du panier');
    }

    // Emprunter tous les livres du panier
    public function borrow(Request $request)
{
    $user = Auth::user();
    $cartItems = Cart::where('user_id', $user->id)->with('book')->get();
    $quantities = $request->input('quantities', []);

    if ($cartItems->isEmpty()) {
        return redirect()->route('cart')->with('error', 'Votre panier est vide.');
    }

    foreach ($cartItems as $item) {
        $book = $item->book;

        // Récupère la quantité du formulaire ou 1 par défaut
        $qty = intval($quantities[$item->id] ?? 1);
        $qty = max(1, $qty);

        if ($qty > $book->available) {
            return redirect()->route('cart')->with(
                'error',
                "Impossible d'emprunter « {$book->title} » : quantité dépasse le stock disponible ({$book->available})."
            );
        }

        // Création de l'emprunt
        Borrow::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'quantity' => $qty,
            'borrowed_at' => now(),
            'due_at' => now()->addDay(),
        ]);

        // Décrémenter le stock
        $book->available -= $qty;
        $book->save();
    }

    // Vide le panier
    Cart::where('user_id', $user->id)->delete();

    return redirect('/')->with('success', 'Livres empruntés avec succès !');
}

}
