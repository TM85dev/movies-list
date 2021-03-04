<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Incoming extends Model
{
    protected $fillable = ['title', 'release_date', 'image'];

    use HasFactory;
}
