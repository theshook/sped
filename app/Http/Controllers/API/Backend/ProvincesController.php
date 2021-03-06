<?php

namespace App\Http\Controllers\API\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\ProvincesStoreRequest;
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
  public function index(Request $request)
  {
    $limit = $request->limit;

    $paginated = Province::with('schools')->paginate($limit);
    $raw = Province::with('schools')->get();
    $data = array(
      'paginated' => $paginated,
      'raw' => $raw
    );

    return $data;
  }

  public function index_raw()
  {
    return Province::orderBy('name', 'ASC')->get();
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(ProvincesStoreRequest $request)
  {
    $province = new Province;
    $province->name = $request->name;

    if ($province->save()) {
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
    $province = Province::with('schools')->find($id);
    if (!empty($province)) {
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
  public function update(ProvincesStoreRequest $request, $id)
  {
    //return $id;
    $province = Province::find($id);
    if (!empty($province)) {
      $province->name = $request->name;
      if ($province->save()) {
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
    if (count($province->schools) > 0) {
      $response = array(
        'status' => 400,
        'message' => 'Province cannot be deleted, contains one or more schools'
      );
      return response()->json($response);
    } else {
      if (!empty($province)) {
        if ($province->delete()) {
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
}
