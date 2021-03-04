<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Serie extends Model
{
    protected $fillable = [
        'title', 'genre', 'description', 'release_date', 'year', 'time', 'cast', 'director', 'producer', 'images', 'trailer_link', 'j_title', 'romaji'
    ];

    public function rate() {
        return $this->hasOne(RateSerie::class);
    }
    public function social() {
        return $this->hasOne(SocialSerie::class);
    }

    public function genreFilter($series, $genre) {
        return $series = $series->filter(function($value, $key) use($genre) {
            return strtolower($value['genre']) === $genre;
        });
    }
    public function yearFilter($series, $year) {
        $years = explode(',', $year);
        $filter_series = collect([]);
        foreach ($years as $current) {
            $filter_series = $filter_series->concat($series->filter(function($value, $key) use($current) {
                return strtolower($value['year']) === $current;
            }));
        }
        return $filter_series;
    }
    public function profileData($user) {
        if($user) {
            $user_series = UserSerie::where('user_id', $user->id)->get();
            $series = collect([]);
            foreach($user_series as $serie) {
                $serie_current = Serie::where('id', $serie->serie_id)->get();
                $series = $series->concat($serie_current);
            }
            $series->each(function($serie, $value) {
                $serie->rate;
                $settings = UserSerie::where('user_id', auth()->user()->id)->where('serie_id', $serie->id)->get();
                $serie->settings = $settings->first();
            });
            return $series;
        }
        return null;
    }
    
    use HasFactory;
}
