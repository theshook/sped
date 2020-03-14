<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pupil;

class PupilsController extends Controller
{
  public function __construct(Pupil $pupil)
  {
    $this->pupil = $pupil;
  }

  public function index()
  {
    return view('pages.backend.pupils.index');
  }

  public function trash_index()
  {
    return view('pages.backend.pupils.trash');
  }
}
