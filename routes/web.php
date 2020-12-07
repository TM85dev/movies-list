<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Api;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\SerieController;

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

/* API */
Route::get('/api/all/{genre}/{year}/{sort}', 'Api@index');
Route::get('/api/movies/{genre}/{year}/{sort}', 'Api@movies');
Route::get('/api/series/{genre}/{year}/{sort}', 'Api@series');

Route::get('/api/search/all/{genre}/{year}/{sort}/{input}', 'Api@searchAll');
Route::get('/api/search/movies/{genre}/{year}/{sort}/{input}', 'Api@searchMovies');
Route::get('/api/search/series/{genre}/{year}/{sort}/{input}', 'Api@searchSeries');

Route::get('/api/incomings', 'Api@incomings');

/* VIEWS */
Route::resource('/', 'HomeController');
Route::resource('/movies', 'MovieController');
Route::resource('/series', 'SerieController');
Route::get('/incomings', function() {
    return view('incomings');
});

Auth::routes();

