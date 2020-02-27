<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\School;

class SchoolsController extends Controller
{
    protected $school;

    public function __construct(School $school)
    {
        $this->school = $school;
    }

    public function index()
    {
        return view('pages.backend.schools.index');
    }
}
