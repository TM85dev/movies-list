<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RateSerie extends Model
{
    
    protected $fillable = ['serie_id', 'sum', 'rating', 'count'];

    use HasFactory;
}
