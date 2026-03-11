<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminBookController;
use App\Http\Controllers\Admin\AdminUserController;

// Accueil
Route::get('/', [BookController::class,'index'])->name('home');

// Auth
Route::get('/login', [AuthController::class,'showLogin'])->name('login');
Route::post('/login', [AuthController::class,'login']);
Route::get('/register', [AuthController::class,'showRegister']);
Route::post('/register', [AuthController::class,'register']);
Route::get('/logout', [AuthController::class,'logout']);

// Utilisateur
Route::middleware('auth')->group(function(){
    Route::post('/borrow/{book}', [BorrowController::class,'borrow'])->name('borrow.book');
    Route::get('/history', [BorrowController::class,'history'])->name('borrow.history');
    Route::get('/profile', [BorrowController::class,'profile'])->name('user.profile');
});

Route::middleware('auth')->group(function(){
    Route::get('/profile', [BorrowController::class,'profile']);
    Route::get('/profile/edit', [BorrowController::class,'editProfile']);
    Route::post('/favorite/{book}', [BookController::class,'toggleFavorite'])->middleware('auth');
    Route::post('/profile/update', [BorrowController::class,'updateProfile']);
});


Route::middleware('auth')->group(function(){
    Route::get('/favorites', [BorrowController::class,'favorites']);
    Route::post('/favorites/{book}', [BorrowController::class,'addFavorite']);
    Route::delete('/favorites/{book}', [BorrowController::class,'removeFavorite']);
    Route::post('/favorite/{book}', [BookController::class, 'toggleFavorite'])->middleware('auth');
});


// Admin
Route::middleware(['auth','admin'])->prefix('admin')->group(function(){
    Route::get('/', [AdminController::class,'dashboard'])->name('admin.dashboard');

    Route::resource('/books', AdminBookController::class)->names([
        'index'=>'books.index',
        'create'=>'books.create',
        'store'=>'books.store',
        'show'=>'books.show',
        'edit'=>'books.edit',
        'update'=>'books.update',
        'destroy'=>'books.destroy'
    ]);

    Route::resource('/users', AdminUserController::class)->names([
        'index'=>'users.index',
        'create'=>'users.create',
        'store'=>'users.store',
        'show'=>'users.show',
        'edit'=>'users.edit',
        'update'=>'users.update',
        'destroy'=>'users.destroy'
    ]);
});
