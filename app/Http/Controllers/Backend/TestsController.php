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
}
