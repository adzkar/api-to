<?php

namespace App\Http\Controllers;

use App\Tests;
use Illuminate\Http\Request;
use App\Http\Resources\TestsResource as TestRes;
use Illuminate\Support\Facades\Auth;

class TestsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $test = Auth::user()->tests()->paginate(10);
      return TestRes::collection($test);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req)
    {
      $req->validate([
        'title' => 'required',
        'information' => 'required',
        'start' => 'required',
        'end' => 'required',
        'duration' => 'required',
        'true_value' => 'required',
        'empty_value' => 'required',
        'wrong_value' => 'required',
      ]);
      $user = Auth::user();
      $save = Tests::create([
        'title' => $req->title,
        'information' => $req->information,
        'start' => $req->start,
        'end' => $req->end,
        'id_com' => $user->id_com,
        'duration' => $req->duration,
        'true_value' => $req->true_value,
        'empty_value' => $req->empty_value,
        'wrong_value' => $req->wrong_value,
      ]);
      if($save)
        return response()->json([
          'message' => 'Test Created',
          'success' => true,
        ]);
      else
        return response()->json([
          'error' => 'Something went wrong',
          'success' => false,
        ], 500);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // $user = ;
        $tests = Auth::user()->tests;
        if($id < $tests->count())
          return new TestRes($tests[$id]);
        else
          return response()->json([
            'message' => 'Not found',
            'success' => false,
          ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $req, $id)
    {
      $user = Auth::user();
      if(!($id < $user->tests->count()))
        return response()->json([
          'message' => 'Id Test '.$id.' not found',
          'success' => false,
        ], 404);
      $test = $user->tests[$id];
      $update = $test->update($req->all());
      if ($update)
          return response()->json([
              'message' => 'Test successfully updated',
              'success' => true,
          ], 201);
      else
          return response()->json([
              'message' => 'Test could not be updated',
              'success' => false,
          ], 500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      $user = Auth::user();
      if(!($id < $user->tests->count()))
        return response()->json([
          'message' => 'Test not found',
          'success' => false,
        ], 404);
      $test = $user->tests[$id];
      if($test->delete())
          return response()->json([
              'message' => 'Test successfully deleted',
              'success' => true,
          ]);
      else
          return response()->json([
              'message' => 'Participant could not be deleted',
              'success' => false,
          ], 500);
    }
}
