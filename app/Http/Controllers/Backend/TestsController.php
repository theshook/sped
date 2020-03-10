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

    public function test_manage_questions($id)
    {
        return view('pages.backend.tests.test-manage-questions')->with('test_id', $id);
    }
}
