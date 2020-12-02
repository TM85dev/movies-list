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

Route::get('/api/all', 'Api@all');
Route::get('/api/all/genres/{genre}', 'Api@genre');

Route::get('/api/movies', 'Api@movies');

Route::get('/api/series', 'Api@series');


Route::resource('/', 'HomeController');
Route::resource('/movies', 'MovieController');
Route::resource('/series', 'SerieController');

Auth::routes();

