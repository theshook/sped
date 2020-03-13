<?php

namespace App\Http\Controllers\API\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\School;

class SchoolsTrashController extends Controller
{
  public function index(Request $request)
  {
    $search = $request->search;
    $limit = $request->limit;

    return ($search) ? School::onlyTrashed()->where('name', 'like', "$search%")->paginate($limit) : School::onlyTrashed()->paginate($limit);
  }

  public function restore_all()
  {
    $restore = School::onlyTrashed()->restore();
    if ($restore) {
      $response = array(
        'status' => 201,
        'message' => 'All schools successfully restored'
      );
      return response()->json($response, 201);
    } else {
      $response = array(
        'status' => 500,
        'message' => 'Failed to restore all schools'
      );
      return response()->json($response, 500);
    }
  }

  public function restore($id)
  {
    $restore = School::onlyTrashed()->where('id', '=', $id)->restore();
    if ($restore) {
      $response = array(
        'status' => 201,
        'message' => 'School successfully restored'
      );
      return response()->json($response, 201);
    } else {
      $response = array(
        'status' => 500,
        'message' => 'Failed to restore school'
      );
      return response()->json($response, 500);
    }
  }

  public function delete_all()
  {
    $restore = School::onlyTrashed()->forceDelete();
    if ($restore) {
      $response = array(
        'status' => 201,
        'message' => 'All schools successfully deleted'
      );
      return response()->json($response, 201);
    } else {
      $response = array(
        'status' => 500,
        'message' => 'Failed to delete all schools'
      );
      return response()->json($response, 500);
    }
  }

  public function delete($id)
  {
    $restore = School::onlyTrashed()->where('id', '=', $id)->forceDelete();
    if ($restore) {
      $response = array(
        'status' => 201,
        'message' => 'School successfully deleted'
      );
      return response()->json($response, 201);
    } else {
      $response = array(
        'status' => 500,
        'message' => 'Failed to delete school'
      );
      return response()->json($response, 500);
    }
  }
}
