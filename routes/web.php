<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Api;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\SerieController;
use App\Http\Controllers\ProfilesController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// Route::get('/api/profile/{id}', 'Api@profile');

Route::resource('/', 'HomeController');
Route::resource('/movies', 'MovieController');
Route::resource('/series', 'SerieController');
Route::resource('/social', 'SocialController');
Route::resource('/posts', 'PostsController');
Route::resource('/profile', 'ProfilesController')->middleware('auth');

Route::post('/posts/add-to-user/{id}', 'PostsController@addToUser');

Route::get('/incomings', function() {
    return view('incomings');
});
Route::get('/movies-series', function() {
    return view('all');
});

//profile
Route::post('/profile/add-movie', 'ProfilesController@addMovie');
Route::post('/profile/add-serie', 'ProfilesController@addSerie');
Route::post('/profile/edit-watched', 'ProfilesController@editWatched');
Route::post('/profile/edit-to_watch', 'ProfilesController@editToWatch');
Route::post('/profile/edit-visible', 'ProfilesController@editVisible');

// api
Route::get('api/profile/{type}/{id}', 'ProfilesController@profileApi');

//admin
Route::get('/controlls/admin/movies', 'AdminController@movies');
Route::post('/controlls/admin/movies', 'AdminController@createMovie');
Route::post('/controlls/admin/movies/{id}', 'AdminController@editMovie');
Route::patch('/controlls/admin/movies/reset', 'AdminController@resetMoviesRating');
Route::delete('/controlls/admin/movies/{id}', 'AdminController@destroyMovie');

Route::get('/controlls/admin/series', 'AdminController@series');
Route::post('/controlls/admin/series', 'AdminController@createSerie');
Route::post('/controlls/admin/series/{id}', 'AdminController@editSerie');
Route::patch('/controlls/admin/series/reset', 'AdminController@resetSeriesRating');
Route::delete('/controlls/admin/series/{id}', 'AdminController@destroySerie');

Route::get('/controlls/admin/incomings', 'AdminController@incomings');
Route::post('/controlls/admin/incomings', 'AdminController@createIncoming');
Route::delete('/controlls/admin/incomings/{id}', 'AdminController@destroyIncoming');

Route::get('/controlls/admin/posts', 'AdminController@posts');
Route::post('/controlls/admin/posts', 'AdminController@createPost');
Route::post('/controlls/admin/posts/{id}', 'AdminController@editPost');
Route::delete('/controlls/admin/posts/{id}', 'AdminController@destroyPost');

Route::get('/controlls/admin/users', 'AdminController@users');
Route::resource('/controlls/admin', 'AdminController');

Auth::routes();
