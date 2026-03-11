<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{

public function favoritedBy()
{
    return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
}


}
