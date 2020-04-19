<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

class ParentPagesController extends Controller
{
  public function login()
  {
    return view('pages.frontend.parents.login');
  }

  public function forgot_password()
  {
    return view('pages.frontend.parents.forgot-password');
  }

  public function intake_form()
  {
    return view('pages.frontend.parents.intake-form.intake-form');
  }

  public function intake_form_fillup()
  {
    return view('pages.frontend.parents.intake-form.intake-form-fillup');
  }
}
