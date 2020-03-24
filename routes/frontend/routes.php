<?php

use Illuminate\Support\Facades\Route;

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

Route::group(['prefix' => 'parent'], function () {
  Route::get('/intake-form', 'Frontend\ClientPagesController@intake_form')->name('parent.intake-form');
  Route::get('/intake-form/fill-up', 'Frontend\ClientPagesController@intake_form_fillup')->name('parent.intake-form-fillup');
});
