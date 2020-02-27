<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Teacher;

class TeachersController extends Controller
{
    public function __construct(Teacher $teacher) {
		$this->middleware('auth');
		$this->teacher = $teacher;
	}

	public function index() {
		return view('pages.backend.teachers.index');
	}
}
