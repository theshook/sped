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

//BACKEND (PROVINCES)
Route::get('/provinces', 'API\Backend\ProvincesController@index');
Route::post('/provinces', 'API\Backend\ProvincesController@store');
Route::get('/province/{id}', 'API\Backend\ProvincesController@show');
Route::put('/province/{id}', 'API\Backend\ProvincesController@update');
Route::delete('/province/{id}', 'API\Backend\ProvincesController@destroy');

/* ========================================================================================================== */

//BACKEND (SCHOOLS)
Route::get('/schools', 'API\Backend\SchoolsController@index');
Route::post('/schools', 'API\Backend\SchoolsController@store');
Route::get('/school/{id}', 'API\Backend\SchoolsController@show');
Route::put('/school/{id}', 'API\Backend\SchoolsController@update');
Route::delete('/school/{id}', 'API\Backend\SchoolsController@destroy');