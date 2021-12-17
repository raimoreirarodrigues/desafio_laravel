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

Route::group(['prefix' => 'v1/bolo', 'middleware' => ['api']], function () {
    Route::get('','App\Http\Controllers\BoloController@index');
    Route::post('','App\Http\Controllers\BoloController@store');
    Route::get('{id}/edit','App\Http\Controllers\BoloController@edit');
    Route::put('{id}','App\Http\Controllers\BoloController@update');
    Route::delete('{id}','App\Http\Controllers\BoloController@destroy');
});

Route::group(['prefix' => 'v1/bolointeressado', 'middleware' => ['api']], function () {
    Route::get('{bolo_id}','App\Http\Controllers\BoloInteressadoController@index');
    Route::post('{bolo_id}','App\Http\Controllers\BoloInteressadoController@store');
    Route::get('{bolo_interessado_id}/edit','App\Http\Controllers\BoloInteressadoController@edit');
    Route::put('{bolo_interessado_id}','App\Http\Controllers\BoloInteressadoController@update');
    Route::delete('{bolo_interessado_id}','App\Http\Controllers\BoloInteressadoController@destroy');
});