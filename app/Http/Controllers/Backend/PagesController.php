<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PagesController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}

	public function checklistCategoriesIndex()
	{
		$data['title'] = 'Checklist Categories';
		$data['slug'] = 'configure';
		return view('pages.backend.checklist_categories.index', $data);
	}

	public function checklistsIndex()
	{
		$data['title'] = 'Checklists';
		$data['slug'] = 'configure';
		return view('pages.backend.checklists.index', $data);
	}
}
