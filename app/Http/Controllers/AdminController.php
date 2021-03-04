<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Movie;
use App\Models\Serie;
use App\Models\SocialMovie;
use App\Models\SocialSerie;
use App\Models\RateMovie;
use App\Models\RateSerie;
use App\Models\UserMovie;
use App\Models\UserSerie;
use App\Models\Incoming;
use App\Models\Post;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct() {
        return $this->middleware('admin');
    }

    public function index()
    {

    }

    public function users() {
        $users = User::all();
        return view('auth.admin.users', compact('users'));
    }
    public function movies() {
        $movies = Movie::all();
        $movies->each(function($movie, $value) {
            $movie->social;
        });
        return view('auth.admin.movies', compact('movies'));
    }
    public function series() {
        $series = Serie::all();
        $series->each(function($serie, $value) {
            $serie->social;
        });
        return view('auth.admin.series', compact('series'));
    }
    public function incomings() {
        $incomings = Incoming::all();
        return view('auth.admin.incomings', compact('incomings'));
    }
    public function posts() {
        $posts = Post::all();
        return view('auth.admin.posts', compact('posts'));
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
        $user = User::find($id);
        if($request->password) {
            $request->validate([
                'password' => 'required|password'
            ]);
            $user->update([
                'name' => $request->name,
                'password' => Hash::make($request->password)
            ]);
        } else {
            $user->update([
                'name' => $request->name
            ]);
        }
        $message = 'Change successfully';
        $users = User::all();
        return response()->json(['users' => $users, 'message' => $message]);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $user = User::find($id);
        $user->delete();
        $users = User::all();
        $message = "User ".$user->name." has been deleted";
        return response()->json(['users' => $users, 'message' => $message]);
    }
    public function destroyMovie($id) {
        $movie = Movie::find($id);
        Storage::delete('/public/movies/'.$movie->images);
        $movie->delete();
        $movies = Movie::all();
        $movies->each(function($movie, $value) {
            $movie->social;
        });
        $message = "Movie ".$movie->title." has been deleted";
        return response()->json(['movies' => $movies, 'message' => $message]);
    }
    public function destroySerie($id) {
        $serie = Serie::find($id);
        Storage::delete('/public/series/'.$serie->images);
        $serie->delete();
        $series = Serie::all();
        $series->each(function($serie, $value) {
            $serie->social;
        });
        $message = "Serie ".$serie->title." has been deleted";
        return response()->json(['series' => $series, 'message' => $message]);
    }

    public function editMovie(Request $request, $id) {
        $movie = Movie::find($id);
        $file_name = $movie->images;
        if($request->hasFile('file')) {
            Storage::delete('/public/movies/'.$movie->images);
            $request->validate([
                'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:1024'
            ]);
            $file_name = $request->file->hashName();
            $request->file('file')->store('/public/movies');
        }
        $request_data = json_decode($request->data);
        $movie->update([
            'title' => $request_data->title,
            'j_title' => $request_data->j_title,
            'romaji' => $request_data->romaji,
            'genre' => $request_data->genre,
            'description' => $request_data->description,
            'release_date' => $request_data->release_date,
            'year' => $request_data->year,
            'time' => $request_data->time,
            'cast' => $request_data->cast,
            'director' => $request_data->director,
            'producer' => $request_data->producer,
            'images' => $file_name,
            'trailer_link' => $request_data->trailer_link
        ]);
        $social = SocialMovie::where('movie_id', $movie->id)->get()->first();
        $movies = Movie::all();
        $movies->each(function($movie, $value) {
            $movie->social;
        });
        $message = "Movie ".$movie->title." has been changed";
        return response() ->json(['movies' => $movies, 'message' => $message]);
    }
    public function editSerie(Request $request, $id) {
        $serie = Serie::find($id);
        $file_name = $serie->images;
        if($request->hasFile('file')) {
            Storage::delete('/public/series/'.$serie->images);
            $request->validate([
                'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:1024'
            ]);
            $file_name = $request->file->hashName();
            $request->file('file')->store('/public/series');
        }
        $request_data = json_decode($request->data);
        $serie->update([
            'title' => $request_data->title,
            'j_title' => $request_data->j_title,
            'romaji' => $request_data->romaji,
            'genre' => $request_data->genre,
            'description' => $request_data->description,
            'release_date' => $request_data->release_date,
            'year' => $request_data->year,
            'time' => $request_data->time,
            'cast' => $request_data->cast,
            'director' => $request_data->director,
            'producer' => $request_data->producer,
            'images' => $file_name,
            'trailer_link' => $request_data->trailer_link
        ]);
        $social = SocialSerie::where('serie_id', $serie->id)->get()->first();
        $series = Serie::all();
        $series->each(function($serie, $value) {
            $serie->social;
        });
        $message = "Serie ".$serie->title." has been changed";
        return response() ->json(['series' => $series, 'message' => $message]);
    }

    public function createMovie(Request $request) {
        $message = 'images has not been selected';
        if($request->hasFile('file')) {
            $request->validate([
                'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:1024'
            ]);
            $request_data = json_decode($request->data);
            $file_name = $request->file->hashName();
            $request->file('file')->store('/public/movies');
            $movie = Movie::create([
                'title' => $request_data->title,
                'j_title' => $request_data->j_title,
                'romaji' => $request_data->romaji,
                'genre' => $request_data->genre,
                'description' => $request_data->description,
                'release_date' => $request_data->release_date,
                'year' => $request_data->year,
                'time' => $request_data->time,
                'cast' => $request_data->cast,
                'director' => $request_data->director,
                'producer' => $request_data->producer,
                'images' => $file_name,
                'trailer_link' => $request_data->trailer_link
            ]);
            $social = SocialMovie::create([
                'movie_id' => $movie->id
            ]);
            $rate = RateMovie::create([
                'movie_id' => $movie->id
            ]);
            $message = "Movie ".$movie->title." has been created";
        }
        $movies = Movie::all();
        $movies->each(function($movie, $value) {
            $movie->social;
        });
        return response()->json(['movies' => $movies, 'message' => $message]);
    }
    public function createSerie(Request $request) {
        $message = 'images has not been selected';
        if($request->hasFile('file')) {
            $request->validate([
                'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:1024'
            ]);
            $request_data = json_decode($request->data);
            $file_name = $request->file->hashName();
            $request->file('file')->store('/public/series');
            $serie = Serie::create([
                'title' => $request_data->title,
                'j_title' => $request_data->j_title,
                'romaji' => $request_data->romaji,
                'genre' => $request_data->genre,
                'description' => $request_data->description,
                'release_date' => $request_data->release_date,
                'year' => $request_data->year,
                'time' => $request_data->time,
                'cast' => $request_data->cast,
                'director' => $request_data->director,
                'producer' => $request_data->producer,
                'images' => $file_name,
                'trailer_link' => $request_data->trailer_link
            ]);
            $social = SocialSerie::create([
                'serie_id' => $serie->id
            ]);
            $rate = RateSerie::create([
                'serie_id' => $serie->id
            ]);
            $message = "Serie ".$serie->title." has been created";
        }
        $series = Serie::all();
        $series->each(function($serie, $value) {
            $serie->social;
        });
        return response()->json(['series' => $series, 'message' => $message]);
    }

    public function resetMoviesRating() {
        $ratings = UserMovie::all();
        $ratings->each(function($rated, $value) {
           $rated->update([
                'rated' => false
            ]);
        });
        return response()->json(['message' => 'The rating has been reset']);
    }
    public function resetSeriesRating() {
        $ratings = UserSerie::all();
        $ratings->each(function($rated, $value) {
           $rated->update([
                'rated' => false
            ]);
        });
        return response()->json(['message' => 'The rating has been reset']);
    }
    
    public function createIncoming(Request $request) {
        $message = 'image has not been selected';
        if($request->hasFile('file')) {
            $request->validate([
                'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:1024'
            ]);
            $request_data = json_decode($request->data);
            $file_name = $request->file->hashName();
            $request->file('file')->store('/public/incomings');
            $incoming = Incoming::create([
                'title' => $request_data->title,
                'release_date' => $request_data->release_date,
                'image' => $file_name
            ]);
            $message = "Incoming ".$incoming->title." has been created";
        }
        $incomings = Incoming::all();
        return response()->json(['incomings' => $incomings, 'message' => $message]);
    }
    public function destroyIncoming($id) {
        $incoming = Incoming::find($id);
        $incoming->delete();
        Storage::delete('/public/incomings/'.$incoming->image);
        $incomings = Incoming::all();
        $message = "Incoming ".$incoming->title." has been deleted";
        return response()->json(['incomings' => $incomings, 'message' => $message]);
    }

    public function createPost(Request $request) {
        $message = 'images has not been selected';
        if($request->hasFile('file')) {
            $request->validate([
                'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:1024'
            ]);
            $request_data = json_decode($request->data);
            $file_name = $request->file->hashName();
            $request->file('file')->store('/public/posts');
            $post = Post::create([
                'title' => $request_data->title,
                'description' => $request_data->description,
                'image' => $file_name,
                'video' => $request_data->video
            ]);
            $message = "Post ".$post->title." has been created";
        }
        $posts = Post::all();
        return response()->json(['posts' => $posts, 'message' => $message]);
    }
    public function editPost(Request $request, $id) {
        $post = Post::find($id);
        $file_name = $post->image;
        if($request->hasFile('file')) {
            Storage::delete('/public/posts/'.$post->image);
            $request->validate([
                'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:1024'
            ]);
            $file_name = $request->file->hashName();
            $request->file('file')->store('/public/posts');
        }
        $request_data = json_decode($request->data);
        $post->update([
            'title' => $request_data->title,
            'description' => $request_data->description,
            'image' => $file_name,
            'video' => $request_data->video
        ]);
        $posts = Post::all();
        $message = "Post ".$post->title." has been updated";
        return response()->json(['posts' => $posts, 'message' => $message]);
    }
    public function destroyPost($id) {
        $post = Post::find($id);
        Storage::delete('/public/posts/'.$post->image);
        $post->delete();
        $posts = Post::all();
        $message = "Post ".$post->title." has been deleted";
        return response()->json(['posts' => $posts, 'message' => $message]);
    }
}
