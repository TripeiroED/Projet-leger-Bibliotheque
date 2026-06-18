<?php

namespace Tests\Unit;

use App\Models\Book;
use App\Models\Borrow;
use App\Models\User;
use Carbon\CarbonInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BorrowTest extends TestCase
{
    use RefreshDatabase;

    public function test_dates_are_cast_to_carbon_instances(): void
    {
        $borrow = new Borrow([
            'borrowed_at' => '2026-01-01 10:00:00',
            'due_at' => '2026-01-15 10:00:00',
        ]);

        $this->assertInstanceOf(CarbonInterface::class, $borrow->borrowed_at);
        $this->assertInstanceOf(CarbonInterface::class, $borrow->due_at);
    }

    public function test_due_date_is_fourteen_days_after_borrow_date(): void
    {
        $borrow = new Borrow([
            'borrowed_at' => '2026-01-01 10:00:00',
            'due_at' => '2026-01-15 10:00:00',
        ]);

        $this->assertSame(14, (int) $borrow->borrowed_at->diffInDays($borrow->due_at));
    }

    public function test_a_borrow_belongs_to_a_user_and_a_book(): void
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();

        $borrow = Borrow::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'quantity' => 2,
            'paid' => true,
            'borrowed_at' => now(),
            'due_at' => now()->addDays(14),
        ]);

        $this->assertTrue($borrow->user->is($user));
        $this->assertTrue($borrow->book->is($book));
    }
}
