<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialMovie extends Model
{
    protected $fillable = ['movie_id', 'like', 'visited'];

    use HasFactory;
}
