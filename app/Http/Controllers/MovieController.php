<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\Models\Movie;
use App\Models\RateMovie;
use App\Models\UserMovie;

class MovieController extends Controller
{

    public function index() {
        return view('movies.index');
    }
    public function show($id) {
        $movie = Movie::find($id);
        $movie->social;
        $user = '';
        if(Auth::check()) {
            $user = Auth::user();
            $user_movie = UserMovie::where('user_id', $user->id)->where('movie_id', $movie->id)->get();
            $user['movie'] = $user_movie->first();
        }
        $related_movies = Movie::where('id', '!=', $id)->
            where('genre', $movie->genre)->get()->shuffle()->slice(0, 3);
        $related_movies->each(function($movie, $value) {
            $movie = $movie->push($movie->rate);
        });
        $rate = $movie->rate;
        return view('movies.movie', compact('movie', 'related_movies', 'rate', 'user'));
    }
    public function update(Request $request) {
        $rate = RateMovie::where('movie_id', $request->id)->first();
        $sum = $rate->sum + $request->rating;
        $count = $rate->count + 1;
        $rating = $sum / $count;
        $rate->update([
            'sum' => $sum,
            'count' => $count,
            'rating' => $rating
        ]);
        $user_movie = UserMovie::where('movie_id', $request->id)->where('user_id', $request->user_id)->first();
        $user_movie->update([
            'rated' => true,
            'rating' => $request->rating,
        ]);
        return response()->json([$rate, $user_movie]);
    }
}
