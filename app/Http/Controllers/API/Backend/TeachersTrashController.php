<?php

namespace App\Http\Controllers\API\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Teacher;

class TeachersTrashController extends Controller
{
  public function index(Request $request)
  {
    $search = $request->search;
    $limit = $request->limit;

    return ($search) ? Teacher::onlyTrashed()->with('school')->where('name', 'like', "$search%")->paginate($limit) : Teacher::onlyTrashed()->with('school')->paginate($limit);
  }

  public function restore_all()
  {
    $restore = Teacher::onlyTrashed()->restore();
    if ($restore) {
      $response = array(
        'status' => 201,
        'message' => 'All teachers successfully restored'
      );
      return response()->json($response, 201);
    } else {
      $response = array(
        'status' => 500,
        'message' => 'Failed to restore all teachers'
      );
      return response()->json($response, 500);
    }
  }

  public function restore($id)
  {
    $restore = Teacher::onlyTrashed()->where('id', '=', $id)->restore();
    if ($restore) {
      $response = array(
        'status' => 201,
        'message' => 'Teacher successfully restored'
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
    $restore = Teacher::onlyTrashed()->forceDelete();
    if ($restore) {
      $response = array(
        'status' => 201,
        'message' => 'All teachers successfully deleted'
      );
      return response()->json($response, 201);
    } else {
      $response = array(
        'status' => 500,
        'message' => 'Failed to delete all teachers'
      );
      return response()->json($response, 500);
    }
  }

  public function delete($id)
  {
    $restore = Teacher::onlyTrashed()->where('id', '=', $id)->forceDelete();
    if ($restore) {
      $response = array(
        'status' => 201,
        'message' => 'Teacher successfully deleted'
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
