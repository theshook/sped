<?php

namespace App\Http\Controllers\API\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\ChecklistsStoreRequest;
use App\Models\Checklist;
use Symfony\Component\HttpFoundation\Request;

class ChecklistsController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request)
	{
		$search = $request->search;
		$limit = $request->limit;

		return ($search) ? Checklist::with('category')->where('content', 'like', "$search%")->paginate($limit) : Checklist::with('category')->paginate($limit);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(ChecklistsStoreRequest $request)
	{
		$checklist = new Checklist;
		$checklist->content = $request->content;
		$checklist->checklist_categories_id = $request->checklist_categories_id;

		if ($checklist->save()) {
			$response = array(
				'status' => 201,
				'message' => 'Checklist successfully added',
				'checklist' => $checklist->category
			);
			return response()->json($response, 201);
		} else {
			$response = array(
				'status' => 500,
				'message' => 'Failed to add checklist'
			);
			return response()->json($response, 500);
		}
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		$checklist = Checklist::find($id);
		if (!empty($checklist)) {
			return $checklist;
		} else {
			$response = array(
				'status' => 404,
				'message' => 'Checklist do not exist'
			);
			return response()->json($response, 404);
		}
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(ChecklistsStoreRequest $request, $id)
	{
		$checklist = Checklist::find($id);
		if (!empty($checklist)) {
			$checklist->content = $request->input('content');
			$checklist->checklist_categories_id = $request->checklist_categories_id;
			if ($checklist->save()) {
				$response = array(
					'status' => 201,
					'message' => 'Checklist successfully added',
					'checklist' => $checklist->category
				);
				return response()->json($response, 201);
			} else {
				$response = array(
					'status' => 500,
					'message' => 'Failed to add checklist'
				);
				return response()->json($response, 500);
			}
		} else {
			$response = array(
				'status' => 404,
				'message' => 'Checklist do not exist'
			);
			return response()->json($response, 404);
		}
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		$checklist = Checklist::find($id);
		if (!empty($checklist)) {
			if ($checklist->delete()) {
				$response = array(
					'status' => 201,
					'message' => 'Checklist successfully deleted'
				);
				return response()->json($response, 201);
			} else {
				$response = array(
					'status' => 500,
					'message' => 'Failed to deleted checklist'
				);
				return response()->json($response, 500);
			}
		} else {
			$response = array(
				'status' => 404,
				'message' => 'Checklist do not exist'
			);
			return response()->json($response, 404);
		}
	}
}
