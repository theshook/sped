<?php

namespace App\Http\Controllers\API\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Test;

class TestsTrashController extends Controller
{
  public function index(Request $request)
  {
    $search = $request->search;
    $limit = $request->limit;

    return ($search) ? Test::onlyTrashed()->where('name', 'like', "$search%")->paginate($limit) : Test::onlyTrashed()->paginate($limit);
  }

  public function restore(Request $request)
  {
    $key = $request->key;
    return $key == 'all' ? $this->restore_all() : $this->restore_school($key);
  }

  public function restore_all()
  {
    $restore = Test::onlyTrashed()->restore();
    if ($restore) {
      $response = array(
        'status' => 201,
        'message' => 'All tests successfully restored'
      );
      return response()->json($response, 201);
    } else {
      $response = array(
        'status' => 500,
        'message' => 'Failed to restore all tests'
      );
      return response()->json($response, 500);
    }
  }

  public function restore_school($id)
  {
    $restore = Test::onlyTrashed()->where('id', '=', $id)->restore();
    if ($restore) {
      $response = array(
        'status' => 201,
        'message' => 'Test successfully restored'
      );
      return response()->json($response, 201);
    } else {
      $response = array(
        'status' => 500,
        'message' => 'Failed to restore test'
      );
      return response()->json($response, 500);
    }
  }

  public function delete($id)
  {
    $restore = Test::onlyTrashed()->where('id', '=', $id)->forceDelete();
    if ($restore) {
      $response = array(
        'status' => 201,
        'message' => 'Test successfully deleted'
      );
      return response()->json($response, 201);
    } else {
      $response = array(
        'status' => 500,
        'message' => 'Failed to delete test'
      );
      return response()->json($response, 500);
    }
  }
}
