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
    public function index()
    {
        $user = Auth::user();

        $cartItems = Cart::where('user_id', $user->id)
            ->with('book')
            ->get();

        $total = $cartItems->sum(fn($item) => $item->book->price * $item->quantity);

        return view('cart.index', compact('cartItems','total'));
    }

    public function add(Book $book)
    {
        $user = Auth::user();

        $cart = Cart::firstOrCreate(
            ['user_id' => $user->id, 'book_id' => $book->id],
            ['quantity' => 0]
        );

        // Vérifie la disponibilité
        if($cart->quantity < $book->available){
            $cart->quantity += 1;
            $cart->save();

            return response()->json(['success' => true, 'message' => 'Livre ajouté au panier']);
        } else {
            return response()->json(['success' => false, 'message' => 'Stock insuffisant']);
        }
    }

    public function update(Request $request, $id)
    {
        $cart = Cart::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $qty = max(1, intval($request->quantity));
        $cart->quantity = min($qty, $cart->book->available);
        $cart->save();

        return back()->with('success','Quantité mise à jour');
    }

    public function remove($id)
    {
        Cart::where('id',$id)
            ->where('user_id',Auth::id())
            ->delete();

        return back()->with('success','Livre retiré du panier');
    }

    public function pay()
    {
        $user = Auth::user();
        $cartItems = Cart::where('user_id', $user->id)->with('book')->get();

        foreach ($cartItems as $item) {
            $book = $item->book;
            $book->available -= $item->quantity;
            $book->save();
        }

        Cart::where('user_id', $user->id)->delete();

        return redirect('/')->with('success','Paiement effectué !');
    }

public function borrow()
{
    $user = Auth::user();

    $cartItems = Cart::where('user_id', $user->id)->get();

    if ($cartItems->isEmpty()) {
        return redirect()->route('cart')->with('error', 'Votre panier est vide.');
    }

    foreach ($cartItems as $item) {
        Borrow::create([
            'user_id' => $user->id,
            'book_id' => $item->book_id,
            'borrowed_at' => now(),
            'due_at' => Carbon::now()->addDay(), // 1 jour max
        ]);

        // réduire la disponibilité du livre
        $book = $item->book;
        if ($book->available > 0) {
            $book->available -= $item->quantity;
            $book->save();
        }
    }

    // vider le panier après emprunt
    Cart::where('user_id', $user->id)->delete();

    return redirect('/')->with('success', 'Livres empruntés avec succès ! Vous devez les rendre dans 1 jour.');
}

}
