<?php

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

// ADMIN LOGIN
Route::get('/', function () {
    return view('auth.login');
});

// ADMIN REQUEST/REGISTER ACCOUNT
Route::get('/register', function () {
    return view('auth.register');
});

//AUTH ROUTES
Auth::routes();

//ADMIN DASHBOARD
Route::get('/home', 'HomeController@index')->name('home');

//BACKEND (Provinces)
Route::get('/configure/provinces', 'Backend\ProvincesController@index')->name('admin.provinces');

//BACKEND (Schools)
Route::get('configure/schools', 'Backend\SchoolsController@index')->name('admin.schools');

//BACKEND (Schools)
Route::get('configure/pupils', 'Backend\PupilsController@index')->name('admin.pupils');

/** Checklists */
Route::get('configure/categories', 'Backend\PagesController@checklistCategoriesIndex')->name('admin.categories');
Route::get('configure/checklists', 'Backend\PagesController@checklistsIndex')->name('admin.checklists');
