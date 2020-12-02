<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Serie;

class SerieController extends Controller
{

    public function index() {
        return view('/series');
    }
}
