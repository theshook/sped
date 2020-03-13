<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Province;

class ProvincesController extends Controller
{
	protected $province;

	public function __construct(Province $province)
	{
		$this->province = $province;
	}

	//Return admin provinces index
	public function index()
	{
		return view('pages.backend.province.index');
	}

	public function trash_index()
	{
		return view('pages.backend.province.trash');
	}
}
