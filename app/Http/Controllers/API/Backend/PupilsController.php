<?php

namespace App\Http\Controllers\API\Backend;

use App\Http\Controllers\Controller;
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
    public function index()
    {
		$pupils = Pupil::paginate(10);
		return PupilsResource::collection($pupils);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		$pupil = new Pupil;
		$pupil->school_id = $request->input('school_id');
		$pupil->name = $request->input('name');
		$pupil->age = $request->input('age');
		$pupil->address = $request->input('address');

		if($pupil->delete()) {
			$response = array(
				'status' => 500,
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
    public function update(Request $request, $id)
    {
        $pupil = Pupil::find($id);
		if(!empty($pupil)) {
			$pupil->school_id = $request->input('school_id');
			$pupil->name = $request->input('name');
			$pupil->age = $request->input('age');
			$pupil->address = $request->input('address');

			if($pupil->delete()) {
				$response = array(
					'status' => 500,
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
					'status' => 500,
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
