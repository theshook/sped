<?php

namespace App\Http\Controllers\API\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Province;
use App\Http\Resources\Backend\ProvincesResource;

class ProvincesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$provinces = Province::paginate(10);
		return ProvincesResource::collection($provinces);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		$province = new Province;
		$province->name = $request->input('name');

		if($province->save()) {
			$response = array(
				'status' => 201,
				'message' => 'Province successfully added'
			);
			return response()->json($response, 201);
		} else {
			$response = array(
				'status' => 500,
				'message' => 'Failed to add province'
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
		$province = Province::find($id);
		if(!empty($province)) {
			return $province;
		} else {
			$response = array(
				'status' => 404,
				'message' => 'Province do not exist'
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
        $province = Province::find($id);
		if(!empty($province)) {
			$province->name = $request->input('name');
			if($province->save()) {
				$response = array(
					'status' => 201,
					'message' => 'Province successfully updated'
				);
				return response()->json($response, 201);
			} else {
				$response = array(
					'status' => 500,
					'message' => 'Failed to update province'
				);
				return response()->json($response, 500);
			}
		} else {
			$response = array(
				'status' => 404,
				'message' => 'Province do not exist'
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
        $province = Province::find($id);
		if(!empty($province)) {
			if($province->delete()) {
				$response = array(
					'status' => 201,
					'message' => 'Province successfully deleted'
				);
				return response()->json($response, 201);
			} else {
				$response = array(
					'status' => 500,
					'message' => 'Failed to delete province'
				);
				return response()->json($response, 500);
			}
		} else {
			$response = array(
				'status' => 404,
				'message' => 'Province do not exist'
			);
			return response()->json($response, 404);
		}
    }
}