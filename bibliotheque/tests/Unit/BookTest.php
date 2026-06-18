<?php

namespace Tests\Unit;

use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookTest extends TestCase
{
    use RefreshDatabase;

    public function test_available_is_cast_to_integer(): void
    {
        $book = new Book(['available' => '7']);

        $this->assertIsInt($book->available);
        $this->assertSame(7, $book->available);
    }

    public function test_fillable_attributes_are_mass_assignable(): void
    {
        $book = new Book([
            'title' => 'Le Petit Prince',
            'author' => 'Antoine de Saint-Exupéry',
            'description' => 'Un conte poétique.',
            'price' => 12.50,
            'available' => 3,
            'image' => 'cover.jpg',
        ]);

        $this->assertSame('Le Petit Prince', $book->title);
        $this->assertSame('Antoine de Saint-Exupéry', $book->author);
        $this->assertSame('Un conte poétique.', $book->description);
        $this->assertEquals(12.50, $book->price);
        $this->assertSame(3, $book->available);
        $this->assertSame('cover.jpg', $book->image);
    }

    public function test_a_book_can_be_favorited_by_users(): void
    {
        $book = Book::factory()->create();
        $user = User::factory()->create();

        $book->favoritedBy()->attach($user->id);

        $this->assertTrue($book->favoritedBy()->where('users.id', $user->id)->exists());
        $this->assertCount(1, $book->favoritedBy);
    }
}
