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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/news', 'NewsController@index');
Route::get('/news/{id}', 'NewsController@show');
Route::post('/news', 'NewsController@store');
Route::put('/news/{id}', 'NewsController@update');
Route::delete('/news/{id}', 'NewsController@destroy');

Route::get('/topics', 'TopicController@index');
Route::get('/topics/{id}', 'TopicController@show');
Route::post('/topics', 'TopicController@store');
Route::put('/topics/{id}', 'TopicController@update');
Route::delete('/topics/{id}', 'TopicController@destroy');




