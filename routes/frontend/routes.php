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

//PARENT
Route::group(['prefix' => 'parent'], function () {
  Route::get('/intake-form', 'Frontend\ParentPagesController@intake_form')->name('parent.intake-form');
  Route::get('/intake-form/fill-up', 'Frontend\ParentPagesController@intake_form_fillup')->name('parent.intake-form-fillup');
});

//PUPIL
Route::group(['prefix' => 'pupil'], function () {
  Route::get('/tests', 'Frontend\PupilPagesController@tests_index')->name('pupils.test.index');
});
