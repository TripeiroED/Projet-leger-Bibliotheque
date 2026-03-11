<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Borrow extends Model
{
    use HasFactory;

    // Ajouter ici les colonnes autorisées pour create()
    protected $fillable = [
        'user_id',
        'book_id',
        'paid',
        'borrowed_at',   // si tu veux remplir la date manuellement
        'returned_at'    // idem
    ];

    // Optionnel : définir les dates automatiquement
    protected $dates = ['borrowed_at', 'returned_at'];

    // Relations
    public function book() {
        return $this->belongsTo(Book::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
