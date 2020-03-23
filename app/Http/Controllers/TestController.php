<?php

namespace App\Http\Controllers;

use App\Models\Teacher;

class TestController extends Controller
{
    public function index() {
        $Teachers = Teacher::with('school', 'questions', 'tests', 'assessments')->get();
        return view('pages.backend.test-data-view')->with([
            'data' => $Teachers
        ]);
    }
}
