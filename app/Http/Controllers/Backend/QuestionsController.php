<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Question;

class QuestionsController extends Controller
{
	public function __construct(Question $question)
	{
		$this->question = $question;
	}

	public function index()
	{
		return view('pages.backend.assessment_questions.index');
	}
}
