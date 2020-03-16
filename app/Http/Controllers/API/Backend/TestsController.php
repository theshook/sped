<?php

namespace App\Http\Controllers\API\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\TestsStoreRequest;
use Illuminate\Http\Request;
use App\Models\Test;
use App\Models\Question;
use DB;

class TestsController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
    $search = $request->search;
    $limit = $request->limit;

    return ($search) ? Test::with('teacher')->where('title', 'like', "%$search%")->paginate($limit) : Test::with('teacher')->paginate($limit);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(TestsStoreRequest $request)
  {
    $test = new Test;
    // $test->teacher_id = auth()->user()->id;
    $test->teacher_id = 1;
    $test->title = $request->title;
    $test->description = $request->description;

    if ($test->save()) {
      $response = array(
        'status' => 201,
        'message' => 'Successfully added test'
      );

      return response()->json($response, 201);
    } else {
      $response = array(
        'status' => 500,
        'message' => 'Failed to add test'
      );

      return response()->json($response, 500);
    }
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    $teacher_id = auth()->user()->id;
    $test = Test::find($id);
    $test_questionsArr = $test->questions_id;
    $test_questions = empty($test_questionsArr) ? [] : $test_questions = Question::whereIn('id', json_decode($test_questionsArr))->get();
    $teacher_questions = Question::where('teacher_id', '=', $teacher_id)->get();

    $response = array(
      'status' => 201,
      'test' => $test,
      'questions' => $test_questions,
      'questions_id' => $test_questionsArr,
      'teacher_questions' => $teacher_questions
    );

    return response()->json($response, 201);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
    $test = Test::find($id);
    if (!empty($test)) {
      $test->teacher_id = $request->teacher_id;
      $test->title = $request->title;
      $test->description = $request->description;

      if ($test->save()) {
        $response = array(
          'status' => 201,
          'message' => 'Successfully added test'
        );

        return response()->json($response, 201);
      } else {
        $response = array(
          'status' => 500,
          'message' => 'Failed to add test'
        );

        return response()->json($response, 500);
      }
    } else {
      $response = array(
        'status' => 404,
        'message' => 'Teacher do not exist'
      );

      return response()->json($response, 404);
    }
  }

  public function update_questions(Request $request, $id)
  {
    $test = Test::find($id);
    $test->questions_id = $request->questions_id;

    if ($test->update()) {
      $response = array(
        'status' => 201,
        'message' => 'Test questions successfully updated'
      );

      return response()->json($response, 201);
    } else {
      $response = array(
        'status' => 500,
        'message' => 'Failed to update test questions'
      );

      return response()->json($response, 500);
    }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $test = Test::find($id);
    if (!empty($test)) {
      if ($test->delete()) {
        $response = array(
          'status' => 201,
          'message' => 'Successfully deleted test'
        );

        return response()->json($response, 201);
      } else {
        $response = array(
          'status' => 500,
          'message' => 'Failed to delete test'
        );

        return response()->json($response, 500);
      }
    } else {
      $response = array(
        'status' => 404,
        'message' => 'Test do not exist'
      );

      return response()->json($response, 404);
    }
  }

  public function delete_question(Request $request, $id)
  {
    $test = Test::find($id);
    $test->questions_id = $request->questions_id;
    if ($test->update()) {
      $response = array(
        'status' => 201,
        'message' => 'Test questions successfully updated'
      );

      return response()->json($response, 201);
    } else {
      $response = array(
        'status' => 500,
        'message' => 'Failed to update test questions'
      );

      return response()->json($response, 500);
    }
  }
}
