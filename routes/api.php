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
Route::get('/provinces/raw', 'API\Backend\ProvincesController@index_raw');
Route::post('/provinces', 'API\Backend\ProvincesController@store');
Route::get('/province/{id}', 'API\Backend\ProvincesController@show');
Route::put('/province/{id}', 'API\Backend\ProvincesController@update');
Route::delete('/province/{id}', 'API\Backend\ProvincesController@destroy');

/* ========================================================================================================== */

//BACKEND (SCHOOLS)
Route::get('/schools', 'API\Backend\SchoolsController@index');
Route::get('/schools/raw', 'API\Backend\SchoolsController@index_raw');
Route::post('/schools', 'API\Backend\SchoolsController@store');
Route::get('/school/{id}', 'API\Backend\SchoolsController@show');
Route::put('/school/{id}', 'API\Backend\SchoolsController@update');
Route::delete('/school/{id}', 'API\Backend\SchoolsController@destroy');

/* ========================================================================================================== */

//BACKEND (PUPILS)
Route::get('/pupils', 'API\Backend\PupilsController@index');
Route::post('/pupils', 'API\Backend\PupilsController@store');
Route::get('/pupil/{id}', 'API\Backend\PupilsController@show');
Route::put('/pupil/{id}', 'API\Backend\PupilsController@update');
Route::delete('/pupil/{id}', 'API\Backend\PupilsController@destroy');

/* ========================================================================================================== */

//BACKEND (CHECKLIST)
Route::get('/checklists', 'API\Backend\ChecklistsController@index');
Route::post('/checklists', 'API\Backend\ChecklistsController@store');
Route::get('/checklist/{id}', 'API\Backend\ChecklistsController@show');
Route::put('/checklist/{id}', 'API\Backend\ChecklistsController@update');
Route::delete('/checklist/{id}', 'API\Backend\ChecklistsController@destroy');

/* ========================================================================================================== */

//BACKEND (CHECKLIST CATEGORIES)
Route::get('/checklists/categories', 'API\Backend\ChecklistCategoriesController@index');
Route::get('/checklists/categories/raw', 'API\Backend\ChecklistCategoriesController@index_raw');
Route::post('/checklists/categories', 'API\Backend\ChecklistCategoriesController@store');
Route::get('/checklist/categories/{id}', 'API\Backend\ChecklistCategoriesController@show');
Route::put('/checklist/categories/{id}', 'API\Backend\ChecklistCategoriesController@update');
Route::delete('/checklist/categories/{id}', 'API\Backend\ChecklistCategoriesController@destroy');
//BACKEND (REPORTS)
Route::get('/reports', 'API\Backend\ChecklistsController@index');
