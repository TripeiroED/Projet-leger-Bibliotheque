<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminBookController;
use App\Http\Controllers\Admin\AdminUserController;

// ---------------------------
// Accueil
// ---------------------------
Route::get('/', [BookController::class,'index'])->name('home');

// ---------------------------
// Authentification
// ---------------------------
Route::get('/login', [AuthController::class,'showLogin'])->name('login');
Route::post('/login', [AuthController::class,'login']);
Route::get('/register', [AuthController::class,'showRegister'])->name('register');
Route::post('/register', [AuthController::class,'register']);

// Déconnexion
Route::get('/logout', [AuthController::class,'logout'])->name('logout');

// ---------------------------
// Routes utilisateur (auth)
// ---------------------------
Route::middleware('auth','verified')->group(function(){

    // Emprunts
    Route::post('/borrow/{book}', [BorrowController::class,'borrow'])->name('borrow.book');
    Route::get('/history', [BorrowController::class,'history'])->name('borrow.history');

    // Profil
    Route::get('/profile', [BorrowController::class,'profile'])->name('user.profile');
    Route::get('/profile/edit', [BorrowController::class,'editProfile'])->name('profile.edit');
    Route::post('/profile/update', [BorrowController::class,'updateProfile'])->name('profile.update');

    // Favoris
    Route::get('/favorites', [BorrowController::class,'favorites'])->name('favorites.index');
    Route::post('/favorites/{book}', [BorrowController::class,'addFavorite'])->name('favorites.add');
    Route::delete('/favorites/{book}', [BorrowController::class,'removeFavorite'])->name('favorites.remove');

    // Toggle favori depuis liste livres
    Route::post('/favorite/{book}', [BookController::class, 'toggleFavorite'])->name('books.toggleFavorite');
});

// ---------------------------
// Admin (auth + admin)
// ---------------------------
Route::middleware(['auth','admin'])->prefix('admin')->group(function(){

    // Dashboard
    Route::get('/', [AdminController::class,'dashboard'])->name('admin.dashboard');

    // Gestion des livres
    Route::resource('/books', AdminBookController::class)->names([
        'index'=>'books.index',
        'create'=>'books.create',
        'store'=>'books.store',
        'show'=>'books.show',
        'edit'=>'books.edit',
        'update'=>'books.update',
        'destroy'=>'books.destroy'
    ]);

    // Gestion des utilisateurs
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

Route::get('/my-borrows', [BorrowController::class, 'myBorrows'])->name('borrows.my');
Route::post('/borrow/return/{id}', [BorrowController::class, 'returnBook'])
    ->name('borrow.return');

use App\Http\Controllers\CartController;

Route::middleware('auth','verified')->group(function () {

    Route::get('/cart', [CartController::class, 'index'])->name('cart');

    Route::post('/cart/add/{book}', [CartController::class, 'add'])->name('cart.add');

    Route::post('/cart/update/{cart}', [CartController::class, 'update'])->name('cart.update'); // <-- ajout

    Route::delete('/cart/remove/{cart}', [CartController::class, 'remove'])->name('cart.remove');

    Route::post('/cart/pay', [CartController::class, 'pay'])->name('cart.pay');

    Route::post('/cart/borrow', [CartController::class, 'borrow'])->name('cart.borrow');

});

// Page après inscription
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

// Lien pour vérifier l'email
Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verify'])
    ->middleware(['auth', 'signed'])
    ->name('verification.verify');

// Renvoyer le mail de vérification
Route::post('/email/verification-notification', [AuthController::class, 'resend'])
    ->middleware(['auth', 'throttle:6,1'])
    ->name('verification.send');


