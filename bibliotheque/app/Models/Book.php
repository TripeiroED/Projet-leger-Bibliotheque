<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
     use HasFactory;

    // Champs autorisés à l'assignation de masse
    protected $fillable = [
        'title',
        'author',
        'description',
        'price',
        'available',
        'image', // si tu gères les images
    ];

public function favoritedBy()
{
    return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
}


}
