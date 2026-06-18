<?php

namespace Tests\Unit;

use App\Models\Book;
use App\Models\Cart;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    public function test_fillable_attributes_are_mass_assignable(): void
    {
        $cart = new Cart([
            'user_id' => 1,
            'book_id' => 2,
            'quantity' => 3,
        ]);

        $this->assertSame(1, $cart->user_id);
        $this->assertSame(2, $cart->book_id);
        $this->assertSame(3, $cart->quantity);
    }

    public function test_a_cart_item_belongs_to_a_user_and_a_book(): void
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();

        $cart = Cart::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'quantity' => 1,
        ]);

        $this->assertTrue($cart->user->is($user));
        $this->assertTrue($cart->book->is($book));
    }
}
