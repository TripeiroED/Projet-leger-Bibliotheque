<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Book;

class CartTest extends TestCase
{
    use RefreshDatabase; // <-- important

    public function test_user_can_borrow_books()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create(['available' => 5]);

        $this->actingAs($user);

        $response = $this->post("/borrow/{$book->id}", [
            'quantity' => 2,
            'paid' => 'true',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('borrows', [
            'user_id' => $user->id,
            'book_id' => $book->id,
            'quantity' => 2,
        ]);

        $this->assertSame(3, $book->fresh()->available);
    }
}
