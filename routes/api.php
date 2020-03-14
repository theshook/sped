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

Route::group(['middleware' => ['auth:api']], function () {
  //BACKEND (PROVINCES)
  Route::get('/provinces', 'API\Backend\ProvincesController@index');
  Route::get('/provinces/raw', 'API\Backend\ProvincesController@index_raw');
  Route::post('/provinces', 'API\Backend\ProvincesController@store');
  Route::get('/province/{id}', 'API\Backend\ProvincesController@show');
  Route::put('/province/{id}', 'API\Backend\ProvincesController@update');
  Route::delete('/province/{id}', 'API\Backend\ProvincesController@destroy');

  //BACKEND (PROVINCES-TRASH)
  Route::get('/provinces/deleted', 'API\Backend\ProvincesTrashController@index');
  Route::post('/provinces/restore', 'API\Backend\ProvincesTrashController@restore');
  Route::delete('/provinces/delete/{id}', 'API\Backend\ProvincesTrashController@delete');

  /* ========================================================================================================== */

  //BACKEND (SCHOOLS)
  Route::get('/schools', 'API\Backend\SchoolsController@index');
  Route::get('/schools/raw', 'API\Backend\SchoolsController@index_raw');
  Route::post('/schools', 'API\Backend\SchoolsController@store');
  Route::get('/school/{id}', 'API\Backend\SchoolsController@show');
  Route::put('/school/{id}', 'API\Backend\SchoolsController@update');
  Route::delete('/school/{id}', 'API\Backend\SchoolsController@destroy');

  //BACKEND (SCHOOLS-TRASH)
  Route::get('/schools/deleted', 'API\Backend\SchoolsTrashController@index');
  Route::post('/schools/restore', 'API\Backend\SchoolsTrashController@restore_all');
  Route::delete('/schools/delete/{id}', 'API\Backend\SchoolsTrashController@delete');

  /* ========================================================================================================== */

  //BACKEND (PUPILS)
  Route::get('/pupils', 'API\Backend\PupilsController@index');
  Route::post('/pupils', 'API\Backend\PupilsController@store');
  Route::get('/pupil/{id}', 'API\Backend\PupilsController@show');
  Route::put('/pupil/{id}', 'API\Backend\PupilsController@update');
  Route::delete('/pupil/{id}', 'API\Backend\PupilsController@destroy');

  /* ========================================================================================================== */

  //BACKEND (TEACHERS)
  Route::get('/teachers', 'API\Backend\TeachersController@index');
  Route::get('/teachers/raw', 'API\Backend\TeachersController@raw');
  Route::post('/teachers', 'API\Backend\TeachersController@store');
  Route::get('/teacher/{id}', 'API\Backend\TeachersController@show');
  Route::put('/teacher/{id}', 'API\Backend\TeachersController@update');
  Route::delete('/teacher/{id}', 'API\Backend\TeachersController@destroy');

  //BACKEND (TEACHERS-TRASH)
  Route::get('/teachers/deleted', 'API\Backend\TeachersTrashController@index');
  Route::post('/teachers/restore', 'API\Backend\TeachersTrashController@restore_all');
  Route::delete('/teachers/delete/{id}', 'API\Backend\TeachersTrashController@delete');

  /* ========================================================================================================== */


  //BACKEND (TESTS) --GENERAL
  Route::get('/tests', 'API\Backend\TestsController@index');
  Route::post('/tests', 'API\Backend\TestsController@store');
  Route::get('/test/{id}', 'API\Backend\TestsController@show');
  Route::put('/test/{id}', 'API\Backend\TestsController@update');
  Route::delete('/test/{id}', 'API\Backend\TestsController@destroy');

  //BACKEND (SCHOOLS-TRASH)
  Route::get('/tests/deleted', 'API\Backend\TestsTrashController@index');
  Route::post('/tests/restore', 'API\Backend\TestsTrashController@restore');
  Route::delete('/tests/delete/{id}', 'API\Backend\TestsTrashController@delete');

  /* ========================================================================================================== */
  //BACKEND (ASSESSMENT QUESTIONS) --GENERAL
  Route::get('/questions', 'API\Backend\QuestionsController@index');
  Route::post('/questions', 'API\Backend\QuestionsController@store');
  Route::get('/question/{id}', 'API\Backend\QuestionsController@show');
  Route::put('/question/{id}', 'API\Backend\QuestionsController@update');
  Route::delete('/question/{id}', 'API\Backend\QuestionsController@destroy');

  /* ========================================================================================================== */

  //BACKEND (ASSESSMENT QUESTIONS) --TEACHER OWNED
  Route::get('/teacher/{id}/questions', 'API\Backend\TeacherQuestionsController@index');

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

  /* ========================================================================================================== */

  //BACKEND (REPORTS)
  Route::get('/reports', 'API\Backend\ChecklistsController@index');
});
