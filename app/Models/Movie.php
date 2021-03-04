<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Response;

class Movie extends Model
{
    protected $fillable = [
    'title', 'genre', 'description', 'release_date', 'year', 'time', 'cast', 'director', 'producer', 'images', 'trailer_link', 'j_title', 'romaji'
    ];
    public function rate() {
        return $this->hasOne(RateMovie::class);
    }
    public function social() {
        return $this->hasOne(SocialMovie::class);
    }

    public function genreFilter($movies, $genre) {
        return $movies = $movies->filter(function($value, $key) use($genre) {
            return strtolower($value['genre']) === $genre;
        });
    }

    public function yearFilter($movies, $year) {
        $years = explode(',', $year);
        $filter_movies = collect([]);
        foreach ($years as $current) {
            $filter_movies = $filter_movies->concat($movies->filter(function($value, $key) use($current) {
                return strtolower($value['year']) === $current;
            }));
        }
        return $filter_movies;
    }

    public function profileData($user) {
        if($user) {
            $user_movies = UserMovie::where('user_id', $user->id)->get();
            $movies = collect([]);
            foreach($user_movies as $movie) {
                $movie_current = Movie::where('id', $movie->movie_id)->get();
                $movies = $movies->concat($movie_current);
            }
            $movies->each(function($movie, $value) {
                $movie->rate;
                $settings = UserMovie::where('user_id', auth()->user()->id)->where('movie_id', $movie->id)->get();
                $movie->settings = $settings->first();
            });
            return $movies;
        }
        return null;
    }

    use HasFactory;
}
