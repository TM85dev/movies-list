<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserMovie extends Model
{
    protected $fillable = ['user_id', 'movie_id', 'rated', 'rating', 'liked', 'watched', 'to_watch', 'visible'];

    use HasFactory;

}
