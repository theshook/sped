<?php

namespace App\Http\Controllers\API\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\TestsStoreRequest;
use Illuminate\Http\Request;
use App\Models\Test;

class TestsController extends Controller
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

        return ($search) ? Test::with('teacher', 'questions')->where('title', 'like', "%$search%")->paginate($limit) : Test::with('teacher', 'questions')->paginate($limit);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TestsStoreRequest $request)
    {
        $test = new Test;
        // $test->teacher_id = auth()->user()->id;
        $test->teacher_id = $request->teacher_id;
        $test->title = $request->title;
        $test->description = $request->description;
        $test->save();
        return $request->all();

        // if ($test->save()) {
        //     $response = array(
        //         'status' => 201,
        //         'message' => 'Successfully added test'
        //     );

        //     return response()->json($response, 201);
        // } else {
        //     $response = array(
        //         'status' => 500,
        //         'message' => 'Failed to add test'
        //     );

        //     return response()->json($response, 500);
        // }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}