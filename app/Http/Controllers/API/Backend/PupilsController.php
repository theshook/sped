<?php

namespace App\Http\Controllers\API\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\PupilsStoreRequest;
use Illuminate\Http\Request;
use App\Models\Pupil;
use App\Http\Resources\Backend\PupilsResource;

class PupilsController extends Controller
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

		return ($search) ? Pupil::with('school')::where('first_name', 'like', "$search%")->orWhere('last_name', 'like', "$search%")->paginate($limit) : Pupil::with('school')->paginate($limit);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PupilsStoreRequest $request)
    {
		$pupil = new Pupil;
		$pupil->school_id = $request->input('school_id');
		$pupil->first_name = $request->input('first_name');
		$pupil->last_name = $request->input('last_name');
		$pupil->middle_name = $request->input('middle_name');
		$pupil->birth_date = $request->input('birth_date');
		$pupil->prof_pic = $request->input('prof_pic');

		if($pupil->delete()) {
			$response = array(
				'status' => 201,
				'message' => 'Successfully added pupil'
			);

			return response()->json($response, 201);
		} else {
			$response = array(
				'status' => 500,
				'message' => 'Failed to add pupil'
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
		$pupil = Pupil::find($id);
		if(!empty($pupil)) {
			return $pupil;
		} else {
			$response = array(
				'status' => 404,
				'message' => 'Pupil do not exist'
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
    public function update(PupilsStoreRequest $request, $id)
    {
        $pupil = Pupil::find($id);
		if(!empty($pupil)) {
			$pupil->school_id = $request->input('school_id');
			$pupil->first_name = $request->input('first_name');
			$pupil->last_name = $request->input('last_name');
			$pupil->middle_name = $request->input('middle_name');
			$pupil->birth_date = $request->input('birth_date');
			$pupil->prof_pic = $request->input('prof_pic');

			if($pupil->delete()) {
				$response = array(
					'status' => 201,
					'message' => 'Successfully added pupil'
				);

				return response()->json($response, 201);
			} else {
				$response = array(
					'status' => 500,
					'message' => 'Failed to add pupil'
				);

				return response()->json($response, 500);
			}
		} else {
			$response = array(
				'status' => 404,
				'message' => 'Pupil do not exist'
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
        $pupil = Pupil::find($id);
		if(!empty($pupil)) {
			if($pupil->delete()) {
				$response = array(
					'status' => 201,
					'message' => 'Successfully deleted pupil'
				);
	
				return response()->json($response, 201);
			} else {
				$response = array(
					'status' => 500,
					'message' => 'Failed to delete pupil'
				);
	
				return response()->json($response, 500);
			}
		} else {
			$response = array(
				'status' => 404,
				'message' => 'Pupil do not exist'
			);

			return response()->json($response, 404);
		}
    }
}
