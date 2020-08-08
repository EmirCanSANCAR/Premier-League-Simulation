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

Route::get('reset-league', 'CommonController@postResetLeague');

Route::get('standings', 'CommonController@getStandings');
Route::get('results', 'CommonController@getResults');

Route::post('results', 'CommonController@getResults');
Route::post('play-all', 'CommonController@postPlayAll');
Route::post('next-week', 'CommonController@postNextWeek');