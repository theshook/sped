<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PupilPagesController extends Controller
{
  public function tests_index()
  {
    return view('pages.frontend.pupils.tests.index');
  }
}
