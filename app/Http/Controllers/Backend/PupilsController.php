<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pupil;

class PupilsController extends Controller
{
	public function __construct(Pupil $pupil) {
		$this->middleware('auth');
		$this->pupil = $pupil;
	}

    public function index() {
		return view('pages.backend.pupils.index');
	}
}
