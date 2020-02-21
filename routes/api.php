<?php

use Illuminate\Http\Request;

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

Route::post('/signup','UserController@signup');
Route::post('/login','UserController@login');

Route::middleware('auth:api')->group(function (){
    Route::post('/logout','UserController@logout');
    Route::post('/photo','PhotoController@store');
    Route::patch('/photo/{photo}','PhotoController@update');
    Route::get('/photo','PhotoController@index');
    Route::get('photo/{photo}','PhotoController@show');
    Route::delete('/photo/{photo}','PhotoController@destroy');
    Route::post('/user/{user}/share','UserController@share');
    Route::get('/user','UserController@search');
});

