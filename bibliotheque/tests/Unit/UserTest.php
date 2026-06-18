<?php

namespace Tests\Unit;

use App\Models\Book;
use App\Models\Borrow;
use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_must_verify_email(): void
    {
        $this->assertInstanceOf(MustVerifyEmail::class, new User());
    }

    public function test_password_is_hashed_when_set(): void
    {
        $user = new User();
        $user->password = 'secret-password';

        $this->assertNotSame('secret-password', $user->password);
        $this->assertTrue(Hash::check('secret-password', $user->password));
    }

    public function test_sensitive_attributes_are_hidden_from_array(): void
    {
        $user = User::factory()->create();

        $array = $user->toArray();

        $this->assertArrayNotHasKey('password', $array);
        $this->assertArrayNotHasKey('remember_token', $array);
    }

    public function test_role_is_mass_assignable(): void
    {
        $user = new User(['role' => 'admin']);

        $this->assertSame('admin', $user->role);
    }

    public function test_user_has_many_borrows(): void
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();

        Borrow::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'quantity' => 1,
            'paid' => true,
            'borrowed_at' => now(),
            'due_at' => now()->addDays(14),
        ]);

        $this->assertCount(1, $user->borrows);
        $this->assertTrue($user->borrows->first()->book->is($book));
    }

    public function test_user_can_favorite_books(): void
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();

        $user->favorites()->attach($book->id);

        $this->assertCount(1, $user->favorites);
        $this->assertTrue($user->favorites->first()->is($book));
    }
}
