<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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

  public function trash_index()
  {
    return view('pages.backend.tests.trash');
  }

  public function test_manage_questions($id)
  {
    return view('pages.backend.tests.test-manage-questions')->with('test_id', $id);
  }
}
