<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/all/{genre}/{year}/{sort}', 'Api@index');
Route::get('/movies/{genre}/{year}/{sort}', 'Api@movies');
Route::get('/series/{genre}/{year}/{sort}', 'Api@series');

Route::get('/news/{sort}', 'Api@news');
Route::get('/posts/{id}', 'Api@post');

Route::get('/search/all/{genre}/{year}/{sort}/{input}', 'Api@searchAll');
Route::get('/search/movies/{genre}/{year}/{sort}/{input}', 'Api@searchMovies');
Route::get('/search/series/{genre}/{year}/{sort}/{input}', 'Api@searchSeries');

Route::get('/incomings', 'Api@incomings');

