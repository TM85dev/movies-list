<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\UserMovie;
use App\Models\UserSerie;
use App\Models\Movie;
use App\Models\Serie;

class ProfilesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {

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
        $user = Auth::user();
        if($user) {
            if(Auth::user()->id == $id) {
                return view('auth.profile');
            } else {
                return abort(401, "Don't have access to this user");
            }
        }
        return abort(401);
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
        if($request->movie_id) {
            $user_movie = UserMovie::where('user_id', Auth::user()->id)->where('movie_id', $request->movie_id)->first();
            if($request->visible) {
                if($user_movie->visible) {
                    return response()->json(['message' => 'movie already added to your profile']);
                } else {
                    $user_movie->update([
                        'visible' => $request->visible
                    ]);
                    return response()->json(['message' => 'movie was added to your profile']);
                }
            }
        } else if($request->serie_id) {
            $user_serie = UserSerie::where('user_id', Auth::user()->id)->where('serie_id', $request->serie_id)->first();
            if($request->visible) {
                if($user_serie->visible) {
                    return response()->json(['message' => 'serie already added to your profile']);
                } else {
                    $user_serie->update([
                        'visible' => $request->visible
                    ]);
                    return response()->json(['message' => 'serie was added to your profile']);
                }
            }
        }
    }
    public function addMovie(Request $request) {
        if(UserMovie::where('user_id', Auth::user()->id)->where('movie_id', $request->type_id)->first()) {
            return response()->json(['message' => 'movie already exist']);
        } else {
            UserMovie::create([
                'user_id' => Auth::user()->id,
                'movie_id' => $request->type_id
            ]);
            $user = auth()->user();
            $user_movie = UserMovie::where('user_id', $user->id)->where('movie_id', $request->type_id)->get();
            $user['movie'] = $user_movie->first();
            return response()->json(['user' => $user, 'message' => 'movie added to profile']);
        }
    }
    public function addSerie(Request $request) {
        if(UserSerie::where('user_id', Auth::user()->id)->where('serie_id', $request->type_id)->first()) {
            return response()->json(['message' => 'serie already exist']);
        } else {
            UserSerie::create([
                'user_id' => Auth::user()->id,
                'serie_id' => $request->type_id
            ]);
            $user = auth()->user();
            $user_serie = UserSerie::where('user_id', $user->id)->where('serie_id', $request->type_id)->get();
            $user['serie'] = $user_serie->first();
            return response()->json(['user' => $user, 'message' => 'serie added to profile']);
        }
    }
    
    public function editWatched(Request $request) {
        $user = Auth::user();
        if($request->movie_id) {
            $movie = UserMovie::where('user_id', $user->id)->where('movie_id', $request->movie_id)->get()->first();
            $movie->update([
                'watched' => $request->watched
            ]);
            return response()->json($movie);
        } else if($request->serie_id) {
            $serie = UserSerie::where('user_id', $user->id)->where('serie_id', $request->serie_id)->get()->first();
            $serie->update([
                'watched' => $request->watched
            ]);
            return response()->json($serie);
        }
    }
    public function editToWatch(Request $request) {
        $user = Auth::user();
        if($request->movie_id) {
            $movie = UserMovie::where('user_id', $user->id)->where('movie_id', $request->movie_id)->get()->first();
            $movie->update([
                'to_watch' => $request->to_watch
            ]);
            return response()->json($movie);
        } else if($request->serie_id) {
            $serie = UserSerie::where('user_id', $user->id)->where('serie_id', $request->serie_id)->get()->first();
            $serie->update([
                'to_watch' => $request->to_watch
            ]);
            return response()->json($serie);
        }
    }
    public function editVisible(Request $request) {
        $user = Auth::user();
        if($request->movie_id) {
            $movie = UserMovie::where('user_id', $user->id)->where('movie_id', $request->movie_id)->get()->first();
            $movie->update([
                'watched' => false,
                'to_watch' => false,
                'visible' => $request->visible

            ]);
            return response()->json($movie);
        } else if($request->serie_id) {
            $serie = UserSerie::where('user_id', $user->id)->where('serie_id', $request->serie_id)->get()->first();
            $serie->update([
                'watched' => false,
                'to_watch' => false,
                'visible' => $request->visible
            ]);
            return response()->json($serie);
        }
    }

    public function profileApi(Request $request, Movie $movie, Serie $serie, $type, $id) {
        $user = Auth::user();
        if($type === 'movies') {
            if($user) {
                if($user->id == $id) {
                    $movies = $movie->profileData($user);
                    $movies = $movies->sortBy('title')->values()->chunk(10);
                    return response()->json($movies);
                } else {
                    return abort(401, "Don't have access to this user");
                }
            } else {
                return abort(401);
            }
        } else if($type === 'series') {
            if($user) {
                if($user->id == $id) {
                    $series = $serie->profileData($user);
                    $series = $series->sortBy('title')->values()->chunk(10);
                    return response()->json($series);
                } else {
                    return abort(401, "Don't have access to this user");
                }
            } else {
                return abourt(401);
            }
        } else if($type === 'all') {
            if($user) {
                if($user->id == $id) {
                    $movies = $movie->profileData($user);
                    $series = $serie->profileData($user);
                    if($movies || $series) {
                        $all = $movies->concat($series)->sortBy('title')->values()->chunk(10);
                        return response()->json($all);
                    }
                } else {
                    return abort(401, "Don't have access to this user");
                }
            }
            return abort(401);
        } else {
            return abort(404);
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
