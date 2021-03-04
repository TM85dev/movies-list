<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialSerie extends Model
{
    protected $fillable = ['serie_id', 'like', 'visited'];

    use HasFactory;
}
