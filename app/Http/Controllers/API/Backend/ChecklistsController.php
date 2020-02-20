<?php

namespace App\Http\Controllers\API\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Checklist;
use App\Http\Resources\Backend\ChecklistsResource;

class ChecklistsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$checklists = Checklist::paginate(10);
		return ChecklistsResource::collection($checklists);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		$checklist = new Checklist;
		$checklist->content = $request->input('content');

		if($checklist->save()) {
			$response = array(
				'status' => 201,
				'message' => 'Checklist successfully added'
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
		if(!empty($checklist)) {
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
    public function update(Request $request, $id)
    {
        $checklist = Checklist::find($id);
		if(!empty($checklist)) {
			$checklist->content = $request->input('content');

			if($checklist->save()) {
				$response = array(
					'status' => 201,
					'message' => 'Checklist successfully added'
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
		if(!empty($checklist)) {
			if($checklist->delete()) {
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
