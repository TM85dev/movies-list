<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\Models\SocialMovie;
use App\Models\SocialSerie;
use App\Models\UserMovie;
use App\Models\UserSerie;

class SocialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if($request->movie) {
            $social_movie = SocialMovie::find($id);
            if($request->like) {
                $user_movie = UserMovie::where('user_id', Auth::user()->id)->where('movie_id', $social_movie->movie_id);
                $social_movie->update([
                    'like' => $request->like
                ]);
                $user_movie->update([
                    'liked' => true
                ]);
            } else if($request->visited) {
                $social_movie->update([
                    'visited' => $request->visited
                ]);
            }
            return response()->json($social_movie);
        } else if($request->serie) {
            $social_serie = SocialSerie::find($id);
            if($request->like) {
                $user_serie = UserSerie::where('user_id', Auth::user()->id)->where('serie_id', $social_serie->serie_id);
                $social_serie->update([
                    'like' => $request->like
                ]);
                $user_serie->update([
                    'liked' => true
                ]);
            } else if($request->visited) {
                $social_serie->update([
                    'visited' => $request->visited
                ]);
            }
            return response()->json($social_serie);
        }
    }
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
