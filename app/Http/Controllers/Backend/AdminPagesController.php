<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminPagesController extends Controller
{
  public function dashboard()
  {
    return view('pages.backend.admin.dashboard');
  }
}
