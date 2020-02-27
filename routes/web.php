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

//BACKEND (Pupils)
Route::get('configure/pupils', 'Backend\PupilsController@index')->name('admin.pupils');

//BACKEND (Teachers)
Route::get('configure/teachers', 'Backend\TeachersController@index')->name('admin.teachers');
//BACKEND (Teachers/Questions)
Route::get('configure/questions', 'Backend\QuestionsController@index')->name('admin.questions');

//BACKEND (Checklists & Categories)
Route::get('configure/categories', 'Backend\PagesController@checklistCategoriesIndex')->name('admin.categories');
Route::get('configure/checklists', 'Backend\PagesController@checklistsIndex')->name('admin.checklists');

//BACKEND (Reports)
Route::get('/configure/reports', 'Backend\ReportsController@index')->name('admin.reports');

//Progress Reports
Route::get('/report/printPDF', 'Backend\ReportsController@printPDF')->name('report.printpdf');
Route::get('/report/viewPDF', 'Backend\ReportsController@viewPDF')->name('report.viewpdf');
