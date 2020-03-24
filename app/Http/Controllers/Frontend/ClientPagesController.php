<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClientPagesController extends Controller
{
  /* ==================== PARENTS ==================== */
  public function intake_form()
  {
    return view('pages.frontend.parents.intake-form');
  }

  public function intake_form_fillup()
  {
    return view('pages.frontend.parents.intake-form-fillup');
  }
}
