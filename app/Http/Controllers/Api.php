<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Movie;
use App\Models\Serie;
use App\Models\Incoming;
use App\Models\Post;

class Api extends Controller
{
    public function index($genre, $year, $sort, Movie $movie, Serie $serie) {
        $movies = $movie->all();
        $series = $serie->all();
        if($genre !== 'all') {
            $movies = $movie->genreFilter($movies, $genre);
            $series = $serie->genreFilter($series, $genre);
        } 
        if($year !== 'all') {
            $movies = $movie->yearFilter($movies, $year);
            $series = $serie->yearFilter($series, $year);
        }
        foreach($movies as $movie) {
            $movies->concat($movie->rate);
        }
        foreach($series as $serie) {
            $series->concat($serie->rate);
        }
        if($sort === 'asort-title') {
            $all = $movies->concat($series)->sortBy('title')->values()->chunk(12);
        } elseif($sort === 'dsort-title') {
            $all = $movies->concat($series)->sortByDesc('title')->values()->chunk(12);
        } elseif($sort === 'asort-year') {
            $all = $movies->concat($series)->sortBy('year')->values()->chunk(12);
        } elseif($sort === 'dsort-year') {
            $all = $movies->concat($series)->sortByDesc('year')->values()->chunk(12);
        }
        return response()->json($all);
    }

    public function movies($genre, $year, $sort, Movie $movie) {
        $movies = $movie->all();
        if($genre !== 'all') {
            $movies = $movie->genreFilter($movies, $genre);
        } 
        if($year !== 'all') {
            $movies = $movie->yearFilter($movies, $year);
        }
        foreach($movies as $movie) {
            $movies->concat($movie->rate);
        }
        if($sort === 'asort-title') {
            $movies = $movies->sortBy('title')->values()->chunk(12);
        } elseif($sort === 'dsort-title') {
            $movies = $movies->sortByDesc('title')->values()->chunk(12);
        } elseif($sort === 'asort-year') {
            $movies = $movies->sortBy('year')->values()->chunk(12);
        } elseif($sort === 'dsort-year') {
            $movies = $movies->sortByDesc('year')->values()->chunk(12);
        }
        return response()->json($movies);
    }

    public function series($genre, $year, $sort, Serie $serie) {
        $series = $serie->all();
        if($genre !== 'all') {
            $series = $serie->genreFilter($series, $genre);
        } 
        if($year !== 'all') {
            $series = $serie->yearFilter($series, $year);
        }
        foreach($series as $serie) {
            $series->concat($serie->rate);
        }
        if($sort === 'asort-title') {
            $series = $series->sortBy('title')->values()->chunk(12);
        } elseif($sort === 'dsort-title') {
            $series = $series->sortByDesc('title')->values()->chunk(12);
        } elseif($sort === 'asort-year') {
            $series = $series->sortBy('year')->values()->chunk(12);
        } elseif($sort === 'dsort-year') {
            $series = $series->sortByDesc('year')->values()->chunk(12);
        }
        return response()->json($series);
    }

    public function searchAll($genre, $year, $sort, $input, Movie $movie, Serie $serie) {
        $movies = $movie->where('title', 'LIKE', '%'.$input.'%')->get();
        $series = $serie->where('title', 'LIKE', '%'.$input.'%')->get();
        if($genre !== 'all') {
            $movies = $movie->genreFilter($movies, $genre);
            $series = $serie->genreFilter($series, $genre);
        } 
        if($year !== 'all') {
            $movies = $movie->yearFilter($movies, $year);
            $series = $serie->yearFilter($series, $year);
        }
        foreach($movies as $movie) {
            $movies->concat($movie->rate);
        }
        foreach($series as $serie) {
            $series->concat($serie->rate);
        }
        if($sort === 'asort-title') {
            $all = $movies->concat($series)->sortBy('title')->values()->chunk(12);
        } elseif($sort === 'dsort-title') {
            $all = $movies->concat($series)->sortByDesc('title')->values()->chunk(12);
        } elseif($sort === 'asort-year') {
            $all = $movies->concat($series)->sortBy('year')->values()->chunk(12);
        } elseif($sort === 'dsort-year') {
            $all = $movies->concat($series)->sortByDesc('year')->values()->chunk(12);
        }
        return response()->json($all);
    }
    public function searchMovies($genre, $year, $sort, $input, Movie $movie) {
        $movies = $movie->where('title', 'LIKE', '%'.$input.'%')->get();
        if($genre !== 'all') {
            $movies = $movie->genreFilter($movies, $genre);
        } 
        if($year !== 'all') {
            $movies = $movie->yearFilter($movies, $year);
        }
        foreach($movies as $movie) {
            $movies->concat($movie->rate);
        }
        if($sort === 'asort-title') {
            $movies = $movies->sortBy('title')->values()->chunk(12);
        } elseif($sort === 'dsort-title') {
            $movies = $movies->sortByDesc('title')->values()->chunk(12);
        } elseif($sort === 'asort-year') {
            $movies = $movies->sortBy('year')->values()->chunk(12);
        } elseif($sort === 'dsort-year') {
            $movies = $movies->sortByDesc('year')->values()->chunk(12);
        }
        return response()->json($movies);
    }
    public function searchSeries($genre, $year, $sort, $input, Serie $serie) {
        $series = $serie->where('title', 'LIKE', '%'.$input.'%')->get();
        if($genre !== 'all') {
            $series = $serie->genreFilter($series, $genre);
        } 
        if($year !== 'all') {
            $series = $serie->yearFilter($series, $year);
        }
        foreach($series as $serie) {
            $series->concat($serie->rate);
        }
        if($sort === 'asort-title') {
            $series = $series->sortBy('title')->values()->chunk(12);
        } elseif($sort === 'dsort-title') {
            $series = $series->sortByDesc('title')->values()->chunk(12);
        } elseif($sort === 'asort-year') {
            $series = $series->sortBy('year')->values()->chunk(12);
        } elseif($sort === 'dsort-year') {
            $series = $series->sortByDesc('year')->values()->chunk(12);
        }
        return response()->json($series);
    }

    public function incomings(Incoming $incoming) {
        $incomings = $incoming->all();
        return response()->json($incomings);
    }

    public function news($sort) {
        $posts = Post::all();
        if($sort === 'asort-title') {
            $posts = $posts->sortBy('title')->values()->chunk(12);
        } elseif($sort === 'dsort-title') {
            $posts = $posts->sortByDesc('title')->values()->chunk(12);
        } elseif($sort === 'asort-year') {
            $posts = $posts->sortBy('created_at')->values()->chunk(12);
        } elseif($sort === 'dsort-year') {
            $posts = $posts->sortByDesc('created_at')->values()->chunk(12);
        } else return abort(404);
        return response()->json($posts);
    }
    public function post($id) {
        $post = Post::find($id);
        $related_posts = Post::where('id', '!=', $id)->get()->sortBy('created_at')->take(3);
        $post['related_posts'] = $related_posts;
        return response()->json($post);
    }

}



