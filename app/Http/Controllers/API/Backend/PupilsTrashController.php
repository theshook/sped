<?php

namespace App\Http\Controllers\API\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pupil;

class PupilsTrashController extends Controller
{
  public function index(Request $request)
  {
    $search = $request->search;
    $limit = $request->limit;

    return ($search) ? Pupil::onlyTrashed()->where('name', 'like', "$search%")->paginate($limit) : Pupil::onlyTrashed()->paginate($limit);
  }

  public function restore(Request $request)
  {
    $key = $request->key;
    return $key == 'all' ? $this->restore_all() : $this->restore_pupil($key);
  }

  public function restore_all()
  {
    $restore = Pupil::onlyTrashed()->restore();
    if ($restore) {
      $response = array(
        'status' => 201,
        'message' => 'All pupils successfully restored'
      );
      return response()->json($response, 201);
    } else {
      $response = array(
        'status' => 500,
        'message' => 'Failed to restore all pupils'
      );
      return response()->json($response, 500);
    }
  }

  public function restore_pupil($id)
  {
    $restore = Pupil::onlyTrashed()->where('id', '=', $id)->restore();
    if ($restore) {
      $response = array(
        'status' => 201,
        'message' => 'Pupil successfully restored'
      );
      return response()->json($response, 201);
    } else {
      $response = array(
        'status' => 500,
        'message' => 'Failed to restore pupil'
      );
      return response()->json($response, 500);
    }
  }

  public function delete($id)
  {
    $restore = Pupil::onlyTrashed()->where('id', '=', $id)->forceDelete();
    if ($restore) {
      $response = array(
        'status' => 201,
        'message' => 'Pupil successfully deleted'
      );
      return response()->json($response, 201);
    } else {
      $response = array(
        'status' => 500,
        'message' => 'Failed to delete pupil'
      );
      return response()->json($response, 500);
    }
  }
}
