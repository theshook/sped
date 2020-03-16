<?php

use Illuminate\Support\Facades\Auth;
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
Route::get('/home', 'HomeController@index')->name('home')->middleware('auth');

Route::group(['prefix' => 'configure', 'middleware' => ['auth']], function () {
  Route::group(['middleware' => ['roles:admin,teachers,pediatrics,deped,parents']], function () {

    Route::group(['middleware' => ['roles:admin']], function () {
      //BACKEND (Provinces)
      Route::get('/provinces', 'Backend\ProvincesController@index')->name('admin.provinces');
      Route::get('/provinces/trash', 'Backend\ProvincesController@trash_index')->name('admin.provinces.trash');
      //BACKEND (Schools)
      Route::get('/schools', 'Backend\SchoolsController@index')->name('admin.schools');
      Route::get('/schools/trash', 'Backend\SchoolsController@trash_index')->name('admin.schools.trash');
      //BACKEND (Teachers)
      Route::get('/teachers', 'Backend\TeachersController@index')->name('admin.teachers');
      Route::get('/teachers/trash', 'Backend\TeachersController@trash_index')->name('admin.teachers.trash');
      //BACKEND (Checklists & Categories)
      Route::get('/categories', 'Backend\PagesController@checklistCategoriesIndex')->name('admin.categories');
      Route::get('/checklists', 'Backend\PagesController@checklistsIndex')->name('admin.checklists');
    });

    Route::group(['middleware' => ['roles:admin,teachers']], function () {
      //BACKEND (Pupils)
      Route::get('/pupils', 'Backend\PupilsController@index')->name('admin.pupils');
      Route::get('/pupils/trash', 'Backend\PupilsController@trash_index')->name('admin.pupils.trash');
      //BACKEND (Tests)
      Route::get('/tests', 'Backend\TestsController@index')->name('admin.tests');
      Route::get('/tests/trash', 'Backend\TestsController@trash_index')->name('admin.test.trash');
      Route::get('/test/{id}/show', 'Backend\TestsController@show')->name('admin.show.manage');
      Route::get('/test/{id}/manage', 'Backend\TestsController@test_manage_questions')->name('admin.test.manage');
      //BACKEND (Teachers/Questions)
      Route::get('/questions', 'Backend\QuestionsController@index')->name('admin.questions');
      //BACKEND (Reports)
      Route::get('/reports', 'Backend\ReportsController@index')->name('admin.reports');
      //Progress Reports
      Route::get('/report/printPDF', 'Backend\ReportsController@printPDF')->name('report.printpdf');
      Route::get('/report/viewPDF', 'Backend\ReportsController@viewPDF')->name('report.viewpdf');
    });
  });
});
