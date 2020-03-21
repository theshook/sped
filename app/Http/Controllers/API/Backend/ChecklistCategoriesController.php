<?php

namespace App\Http\Controllers\API\backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\ChecklistCategoryRequest;
use App\Models\ChecklistCategory;
use Illuminate\Http\Request;

class ChecklistCategoriesController extends Controller
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

    return ($search) ? ChecklistCategory::with('questions')->where('name', 'like', "$search%")->orderBy('name', 'ASC')->paginate($limit) : ChecklistCategory::with('questions')->paginate($limit);
  }

  public function index_raw()
  {
    return ChecklistCategory::orderBy('name', 'ASC')->get();
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(ChecklistCategoryRequest $request)
  {
    $cCategories = new ChecklistCategory;
    $cCategories->name = $request->name;
    if ($cCategories->save()) {
      $response = array(
        'status' => 201,
        'message' => 'Checklist Categories successfully added'
      );
      return response()->json($response, 201);
    } else {
      $response = array(
        'status' => 500,
        'message' => 'Failed to add Checklist Categories'
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
    $cCategory = ChecklistCategory::find($id);
    if (!empty($cCategory)) {
      return $cCategory;
    } else {
      $response = array(
        'status' => 404,
        'message' => 'Category do not exist'
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
  public function update(ChecklistCategoryRequest $request, $id)
  {
    $cCategory = ChecklistCategory::find($id);
    if (!empty($cCategory)) {
      $cCategory->name = $request->input('name');
      if ($cCategory->save()) {
        $response = array(
          'status' => 201,
          'message' => 'Category successfully updated'
        );
        return response()->json($response, 201);
      } else {
        $response = array(
          'status' => 500,
          'message' => 'Failed to update category'
        );
        return response()->json($response, 500);
      }
    } else {
      $response = array(
        'status' => 404,
        'message' => 'Category do not exist'
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
    $cCategory = ChecklistCategory::find($id);
    if (!empty($cCategory)) {
      if ($cCategory->delete()) {
        $response = array(
          'status' => 201,
          'message' => 'Category successfully deleted'
        );
        return response()->json($response, 201);
      } else {
        $response = array(
          'status' => 500,
          'message' => 'Failed to delete category'
        );
        return response()->json($response, 500);
      }
    } else {
      $response = array(
        'status' => 404,
        'message' => 'Category do not exist'
      );
      return response()->json($response, 404);
    }
  }
}
