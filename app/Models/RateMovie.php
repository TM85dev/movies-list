<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RateMovie extends Model
{

    protected $fillable = ['movie_id', 'sum', 'rating', 'count'];

    use HasFactory;
}
