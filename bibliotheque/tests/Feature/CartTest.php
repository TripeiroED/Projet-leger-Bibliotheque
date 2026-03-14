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
        $book = Book::factory()->create();

        $this->actingAs($user);

        $response = $this->post('/borrow', [
            'book_id' => $book->id,
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('borrows', [
            'user_id' => $user->id,
            'book_id' => $book->id,
        ]);
    }
}
