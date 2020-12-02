<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Movie;
use App\Models\Serie;

class Api extends Controller
{
    public function all() {
        $movies = Movie::all();
        $series = Serie::all();
        $all = $movies->concat($series)->sortBy('title')->values()->chunk(10);
        return response()->json($all);
    }
    public function genre(Movie $movie, Serie $serie, $genre) {
        $movies = Movie::where('genre', $genre)->get();
        $series = Serie::where('genre', $genre)->get();
        $movies->all();
        $series->all();
        $all = $movies->concat($series)->sortBy('title')->values()->chunk(10);
        return response()->json($all);
    }

    public function movies() {
        $movies = Movie::all()->sortBy('title')->values()->chunk(10);
        return response()->json($movies);
    }

    public function series() {
        $series = Serie::all()->sortBy('title')->values()->chunk(10);
        return response()->json($series);
    }
}
