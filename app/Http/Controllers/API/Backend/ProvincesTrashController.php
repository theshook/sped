<?php

namespace App\Http\Controllers\API\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Province;

class ProvincesTrashController extends Controller
{
  public function index(Request $request)
  {
    $search = $request->search;
    $limit = $request->limit;

    return ($search) ? Province::onlyTrashed()->where('name', 'like', "$search%")->paginate($limit) : Province::onlyTrashed()->paginate($limit);
  }

  public function restore(Request $request)
  {
    $key = $request->key;
    return $key == 'all' ? $this->restore_all() : $this->restore_province($key);
  }

  public function restore_all()
  {
    $restore = Province::onlyTrashed()->restore();
    if ($restore) {
      $response = array(
        'status' => 201,
        'message' => 'All province successfully restored'
      );
      return response()->json($response, 201);
    } else {
      $response = array(
        'status' => 500,
        'message' => 'Failed to restore all provinces'
      );
      return response()->json($response, 500);
    }
  }

  public function restore_province($id)
  {
    $restore = Province::onlyTrashed()->where('id', '=', $id)->restore();
    if ($restore) {
      $response = array(
        'status' => 201,
        'message' => 'Province successfully restored'
      );
      return response()->json($response, 201);
    } else {
      $response = array(
        'status' => 500,
        'message' => 'Failed to restore province'
      );
      return response()->json($response, 500);
    }
  }

  public function delete($id)
  {
    $restore = Province::onlyTrashed()->where('id', '=', $id)->forceDelete();
    if ($restore) {
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
  }
}
