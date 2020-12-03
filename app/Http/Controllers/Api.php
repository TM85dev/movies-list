<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Movie;
use App\Models\Serie;

class Api extends Controller
{
    // public function all() {
    //     $movies = Movie::all();
    //     $series = Serie::all();
    //     $all = $movies->concat($series)->sortBy('title')->values()->chunk(10);
    //     return response()->json($all);
    // }
    // public function genre($genre) {
    //     $movies = Movie::where('genre', $genre)->get();
    //     $series = Serie::where('genre', $genre)->get();
    //     $all = $movies->concat($series)->sortBy('title')->values()->chunk(10);
    //     return response()->json($all);
    // }
    // public function year($year) {
    //     $movies = Movie::where('year', $year)->get();
    //     $series = Serie::where('year', $year)->get();
    //     $all = $movies->concat($series)->sortBy('title')->values()->chunk(10);
    //     return response()->json($all);
    // }
    // public function genreYear($genre, $year) {
    //     $movies = Movie::where('genre', $genre)->where('year', $year)->get();
    //     $series = Serie::where('genre', $genre)->where('year', $year)->get();
    //     $all = $movies->concat($series)->sortBy('title')->values()->chunk(10);
    //     return response()->json($all);
    // }
    // public function genreSelectedYears($genre, $firstYear, $lastYear) {
    //     $movies = Movie::whereBetween('year', [$firstYear, $lastYear])->get();
    //     $series = Serie::whereBetween('year', [$firstYear, $lastYear])->get();
    //     $all = $movies->concat($series)->sortBy('title')->values()->chunk(10);
    //     return response()->json($all);
    // }
    public function index($genre, $year, $sort) {
        $movies = Movie::all();
        $series = Serie::all();
        if($genre !== 'all') {
            $movies = $movies->filter(function($value, $key) use($genre) {
                return strtolower($value['genre']) === $genre;
            });
            $series = $series->filter(function($value, $key) use($genre) {
                return strtolower($value['genre']) === $genre;
            });
        } 
        if($year !== 'all') {
            $years = explode(',', $year);
            $filter_movies = collect([]);
            $filter_series = collect([]);
            foreach ($years as $current) {
                $filter_movies = $filter_movies->concat($movies->filter(function($value, $key) use($current) {
                    return strtolower($value['year']) === $current;
                }));
                $filter_series = $filter_series->concat($series->filter(function($value, $key) use($current) {
                    return strtolower($value['year']) === $current;
                }));
            }
            $movies = $filter_movies;
            $series = $filter_series;
        }
        if($sort === 'asort-title') {
            $all = $movies->concat($series)->sortBy('title')->values()->chunk(10);
        } elseif($sort === 'dsort-title') {
            $all = $movies->concat($series)->sortByDesc('title')->values()->chunk(10);
        } elseif($sort === 'asort-year') {
            $all = $movies->concat($series)->sortBy('year')->values()->chunk(10);
        } elseif($sort === 'dsort-year') {
            $all = $movies->concat($series)->sortByDesc('year')->values()->chunk(10);
        }
        return response()->json($all);

    }
    // public function years($genre, $firstYear, $lastYear) {
    //     $movies = Movie::all();
    //     $series = Serie::all();
    //     if($genre !== 'all') {
    //         $movies = $movies->filter(function($value, $key) use($genre) {
    //             return strtolower($value['genre']) === $genre;
    //         });
    //         $series = $series->filter(function($value, $key) use($genre) {
    //             return strtolower($value['genre']) === $genre;
    //         });
    //     } 
    //     $movies = $movies->filter(function($value, $key) use($firstYear, $lastYear) {
    //         return $firstYear <= $value['year'] && $lastYear >= $value['year'];
    //     });
    //     $series = $series->filter(function($value, $key) use($firstYear, $lastYear) {
    //         return $firstYear <= $value['year'] && $lastYear >= $value['year'];
    //     });
    //     $all = $movies->concat($series)->sortBy('title')->values()->chunk(10);
    //     return response()->json($all);
    // }



    public function movies() {
        $movies = Movie::all()->sortBy('title')->values()->chunk(10);
        return response()->json($movies);
    }

    public function series() {
        $series = Serie::all()->sortBy('title')->values()->chunk(10);
        return response()->json($series);
    }
}
