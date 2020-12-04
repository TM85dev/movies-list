<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Serie extends Model
{

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
    
    use HasFactory;
}
