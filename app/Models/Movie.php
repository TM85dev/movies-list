<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    // $protected = [
    // 'title', 'genre', 'description', 'release_date', 'time', 'cast', 'director', 'producer', 'trailer_link'
    // ];
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

    use HasFactory;
}
