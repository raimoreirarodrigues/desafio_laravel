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

Route::group(['prefix' => 'v1', 'middleware' => ['api']], function () {
    Route::get('bolo','App\Http\Controllers\BoloController@index');
    Route::post('bolo','App\Http\Controllers\BoloController@store');
    Route::get('bolo/{id}','App\Http\Controllers\BoloController@edit');
    Route::put('bolo/{id}','App\Http\Controllers\BoloController@update');
    Route::delete('bolo/{id}','App\Http\Controllers\BoloController@index');
});