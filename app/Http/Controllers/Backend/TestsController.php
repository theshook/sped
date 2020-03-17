<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Test;
use App\Models\Question;

class TestsController extends Controller
{
  public function index()
  {
    return view('pages.backend.tests.index');
  }

  public function show($id)
  {
    return view('pages.backend.tests.show')->with('test_id', $id);
  }

  public function show_pdf($id)
  {
    $test = Test::find($id);
    $questionsArr = $test->questions_id;
    $questions = empty($questionsArr) ? null : $questions = Question::whereIn('id', json_decode($questionsArr))->get();

    return view('pages.backend.tests.show_pdf')->with([
      'test' => $test,
      'questions' => $questions
    ]);
  }

  public function trash_index()
  {
    return view('pages.backend.tests.trash');
  }

  public function test_manage_questions($id)
  {
    return view('pages.backend.tests.test-manage-questions')->with('test_id', $id);
  }
}
