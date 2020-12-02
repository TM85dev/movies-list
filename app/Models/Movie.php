<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    // $protected = [
    // 'title', 'genre', 'description', 'release_date', 'time', 'cast', 'director', 'producer', 'trailer_link'
    // ];
    use HasFactory;
}
