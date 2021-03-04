<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use App\Models\Serie;
use App\Models\RateSerie;
use App\Models\UserSerie;

class SerieController extends Controller
{

    public function index() {
        return view('series.index');
    }
    public function show($id) {
        $serie = Serie::find($id);
        $serie->social;
        $user = '';
        if(Auth::check()) {
            $user = Auth::user();
            $user_serie = UserSerie::where('user_id', $user->id)->where('serie_id', $serie->id)->get();
            $user['serie'] = $user_serie->first();
        }
        $related_series = Serie::where('id', '!=', $id)->
            where('genre', $serie->genre)->get()->shuffle()->slice(0, 3);
        $related_series->each(function($serie, $value) {
            $serie = $serie->push($serie->rate);
        });
        $rate = $serie->rate;
        return view('series.serie', compact('serie', 'related_series', 'rate', 'user'));
    }
    public function update(Request $request) {
        $rate = RateSerie::where('serie_id', $request->id)->first();
        $sum = $rate->sum + $request->rating;
        $count = $rate->count + 1;
        $rating = $sum / $count;
        $rate->update([
            'sum' => $sum,
            'count' => $count,
            'rating' => $rating
        ]);
        $user_serie = UserSerie::where('serie_id', $request->id)->where('user_id', $request->user_id)->first();
        $user_serie->update([
            'rated' => true,
            'rating' => $request->rating,
        ]);
        return response()->json([$rate, $user_serie]);
    }
}
