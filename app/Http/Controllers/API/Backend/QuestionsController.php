<?php

namespace App\Http\Controllers\API\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Question;
use App\Http\Requests\API\QuestionStoreRequest;

class QuestionsController extends Controller
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

    return ($search) ? Question::with('teacher', 'category')->where('question', 'like', "%$search%")->paginate($limit) : Question::with('teacher', 'category')->paginate($limit);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $question = new Question;
    $question->checklist_id = $request->checklist_category_id;
    $question->teacher_id = $request->teacher_id;
    $question->question_type = $request->question_type;
    $question->question = $request->question;
    $question->choice1 = $request->choice1;
    $question->choice2 = $request->choice2;
    $question->choice3 = $request->choice3;
    $question->choice4 = $request->choice4;
    $question->answer = $request->answer;
    $question->explanation = $request->explanation;

    if ($question->save()) {
      $response = array(
        'status' => 201,
        'message' => 'Successfully added question'
      );

      return response()->json($response, 201);
    } else {
      $response = array(
        'status' => 500,
        'message' => 'Failed to add question'
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
    $question = Question::with('teacher')->find($id);
    if (!empty($question)) {
      return $question;
    } else {
      $response = array(
        'status' => 404,
        'message' => 'Question do not exist'
      );

      return response()->json($response, 404);
    }
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
    $question = Question::find($id);
    if (!empty($question)) {
      $question = new Question;
      $question->checklist_category_id = $request->checklist_category_id;
      $question->teacher_id = $request->teacher_id;
      $question->question = $request->question;
      $question->choice1 = $request->choice1;
      $question->choice2 = $request->choice2;
      $question->choice3 = $request->choice3;
      $question->choice4 = $request->choice4;
      $question->answer = $request->answer;
      $question->explanation = $request->explanation;

      if ($question->save()) {
        $response = array(
          'status' => 201,
          'message' => 'Successfully added question'
        );

        return response()->json($response, 201);
      } else {
        $response = array(
          'status' => 500,
          'message' => 'Failed to add question'
        );

        return response()->json($response, 500);
      }
    } else {
      $response = array(
        'status' => 404,
        'message' => 'Question do not exist'
      );

      return response()->json($response, 404);
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
    $question = Question::find($id);
    if (!empty($question)) {
      if ($question->delete()) {
        $response = array(
          'status' => 201,
          'message' => 'Successfully deleted question'
        );

        return response()->json($response, 201);
      } else {
        $response = array(
          'status' => 500,
          'message' => 'Failed to delete question'
        );

        return response()->json($response, 500);
      }
    } else {
      $response = array(
        'status' => 404,
        'message' => 'Question do not exist'
      );

      return response()->json($response, 404);
    }
  }
}
