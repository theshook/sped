<?php

namespace App\Http\Controllers\API\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Http\Requests\API\TeachersStoreRequest;

class TeachersController extends Controller
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

		return ($search) ? Teacher::with('school')::where('first_name', 'like', "$search%")
			->orWhere('last_name', 'like', "$search%")
			->orWhere('middle_name', 'like', "$search%")
			->paginate($limit)
			: Teacher::with('school')->paginate($limit);
	}

	public function raw()
	{
		return Teacher::all();
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(TeachersStoreRequest $request)
	{
		$teacher = new Teacher;
		$teacher->school_id = $request->school_id;
		$teacher->first_name = $request->first_name;
		$teacher->last_name = $request->last_name;
		$teacher->middle_name = $request->middle_name;

		if ($teacher->save()) {
			$response = array(
				'status' => 201,
				'message' => 'Successfully added teacher'
			);

			return response()->json($response, 201);
		} else {
			$response = array(
				'status' => 500,
				'message' => 'Failed to add teacher'
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
		$teacher = Teacher::with('school')->find($id);
		if (!empty($teacher)) {
			return $teacher;
		} else {
			$response = array(
				'status' => 404,
				'message' => 'Teacher do not exist'
			);

			return response()->json($response, 404);
		}
		return $teacher;
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		$teacher = Teacher::find($id);
		if (!empty($teacher)) {
			$teacher->school_id = $request->school_id;
			$teacher->first_name = $request->first_name;
			$teacher->last_name = $request->last_name;
			$teacher->middle_name = $request->middle_name;

			if ($teacher->save()) {
				$response = array(
					'status' => 201,
					'message' => 'Successfully updated teacher'
				);

				return response()->json($response, 201);
			} else {
				$response = array(
					'status' => 500,
					'message' => 'Failed to update teacher'
				);

				return response()->json($response, 500);
			}
		} else {
			$response = array(
				'status' => 404,
				'message' => 'Teacher do not exist'
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
		$teacher = Teacher::find($id);
		if (!empty($teacher)) {
			if ($teacher->delete()) {
				$response = array(
					'status' => 201,
					'message' => 'Successfully deleted teacher'
				);

				return response()->json($response, 201);
			} else {
				$response = array(
					'status' => 500,
					'message' => 'Failed to delete teacher'
				);

				return response()->json($response, 500);
			}
		} else {
			$response = array(
				'status' => 404,
				'message' => 'Teacher do not exist'
			);

			return response()->json($response, 404);
		}
	}
}
