<?php

namespace App\Http\Controllers\API\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\SchoolsStoreRequest;
use Illuminate\Http\Request;
use App\Models\School;
use App\Http\Resources\Backend\SchoolsResource;

class SchoolsController extends Controller
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

    return ($search) ? School::with('province', 'teachers', 'pupils')::where('name', 'like', "$search%")->paginate($limit) : School::with('province', 'teachers', 'pupils')->paginate($limit);
  }

  public function index_raw()
  {
    return School::with('province')->get();
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(SchoolsStoreRequest $request)
  {
    $school = new School;
    $school->province_id = $request->province_id;
    $school->name = $request->name;

    if ($school->save()) {
      $response = array(
        'status' => 201,
        'message' => 'Successfully added school'
      );

      return response()->json($response, 201);
    } else {
      $response = array(
        'status' => 500,
        'message' => 'Failed to add school'
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
    $school = School::with('province', 'teachers', 'pupils')->find($id);
    if (!empty($school)) {
      return $school;
    } else {
      $response = array(
        'status' => 404,
        'message' => 'School do not exist'
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
  public function update(SchoolsStoreRequest $request, $id)
  {
    $school = School::find($id);
    if (!empty($school)) {
      $school->province_id = $request->province_id;
      $school->name = $request->name;

      if ($school->save()) {
        $response = array(
          'status' => 201,
          'message' => 'Successfully updated school'
        );

        return response()->json($response, 201);
      } else {
        $response = array(
          'status' => 500,
          'message' => 'Failed to updated school'
        );

        return response()->json($response, 500);
      }
    } else {
      $response = array(
        'status' => 404,
        'message' => 'School do not exist'
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
    $school = School::find($id);
    if (count($school->pupils) > 0) {
      $response = array(
        'status' => 400,
        'message' => 'School cannot be deleted, contains one or more pupils'
      );
      return response()->json($response);
    } else {
      if (!empty($school)) {
        if ($school->delete()) {
          $response = array(
            'status' => 201,
            'message' => 'Successfully deleted school'
          );

          return response()->json($response, 201);
        } else {
          $response = array(
            'status' => 500,
            'message' => 'Failed to delete school'
          );

          return response()->json($response, 500);
        }
      } else {
        $response = array(
          'status' => 404,
          'message' => 'School do not exist'
        );

        return response()->json($response, 404);
      }
    }
  }
}
