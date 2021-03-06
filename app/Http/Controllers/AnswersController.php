<?php

namespace App\Http\Controllers;

use App\Answers;
use App\Images;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\AnswerResource as AnsRes;

class AnswersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id, $qid)
    {
        $user = Auth::user();
        $tests = $user->tests;
        if(!($id < $tests->count()))
          return response()->json([
            'message' => 'Not found',
            'success' => false,
          ], 404);
        $questions = $tests[$id]->questions;
        if(!($qid < $questions->count()))
          return response()->json([
            'message' => 'Not found',
            'success' => false,
          ], 404);
        return AnsRes::collection($questions[$qid]->answers);
    }

    public function show($id, $qid, $aid)
    {
        $user = Auth::user();
        $tests = $user->tests;
        if(!($id < $tests->count()))
          return response()->json([
            'message' => 'Not found',
            'success' => false,
          ], 404);
        $questions = $tests[$id]->questions;
        if(!($qid < $questions->count()))
          return response()->json([
            'message' => 'Not found',
            'success' => false,
          ], 404);
        if(!($aid < $questions[$qid]->answers->count()))
          return response()->json([
            'message' => 'Not found',
            'success' => false,
          ], 404);
        return new AnsRes($questions[$qid]->answers[$aid]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req, $id, $qid)
    {
        $user = Auth::user();
        $tests = $user->tests;
        if(!($id < $tests->count()))
          return response()->json([
            'message' => 'Not found',
            'success' => false,
          ], 404);
        $questions = $tests[$id]->questions;
        if(!($qid < $questions->count()))
          return response()->json([
            'message' => 'Not found',
            'success' => false,
          ], 404);
        $req->validate([
          'option' => 'required',
          'status' => 'required|boolean',
          'image' => 'image|max:2048|mimes:jpeg,png,jpg|unique:images|nullable',
        ]);
        
        // Auth duplicate image
        if($req->hasFile('image')) {
          $tmp = $req->file('image');
          $fileName = $tmp->getClientOriginalName();
          // auth duplicate file name
          $img = Images::where(['image' => $fileName])->first();
          if($img)
            return response()->json([
              'message' => 'Invalid Image file name',
              'errors' => [
                  'image' => 'File name has already taken',
                ]
            ], 400);
        }

        $save = Answers::create([
          'option' => $req->option,
          'status' => $req->status,
          'id_question' => $questions[$qid]->id_question,
        ]);
        if($save) {
          // save images
          $id_answer = $save->id_answer;
          if($req->hasFile('image')) {
            $tmp = $req->file('image');
            $fileName = $tmp->getClientOriginalName();
              Images::create([
                'mime' => $tmp->getMimeType(),
                'image' => $fileName,
                'id_answer' => $id_answer,
              ]);
              if($tmp->move(public_path().'/images',$fileName))
              return response()->json([
                'message' => 'Answer Created',
                'success' => true,
              ], 201);
              else
              return response()->json([
                'error' => 'Something went wrong',
                'success' => false,
              ], 500);
          }

          return response()->json([
            'message' => 'Answer Created',
            'success' => true,
          ], 201);
        }
        else
          return response()->json([
            'error' => 'Something went wrong',
            'success' => false,
          ], 500);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $req, $id, $qid, $aid)
    {
      $user = Auth::user();
      $tests = $user->tests;
      if(!($id < $tests->count()))
        return response()->json([
          'message' => 'Not found',
          'success' => false,
        ], 404);
      $questions = $tests[$id]->questions;
      if(!($qid < $questions->count()))
        return response()->json([
          'message' => 'Not found',
          'success' => false,
        ], 404);
      if(!($aid < $questions[$qid]->answers->count()))
        return response()->json([
          'message' => 'Not found',
          'success' => false,
        ], 404);
      $answer = $questions[$qid]->answers[$aid];
      if($answer->update($req->all()))
        return response()->json([
          'message' => 'Answer successfully updated',
          'success' => true,
        ], 200);
      else
        return response()->json([
          'message' => 'Answer could not be updated',
          'success' => false,
        ], 500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, $qid, $aid)
    {
      $user = Auth::user();
      $tests = $user->tests;
      if(!($id < $tests->count()))
        return response()->json([
          'message' => 'Not found',
          'success' => false,
        ], 404);
      $questions = $tests[$id]->questions;
      if(!($qid < $questions->count()))
        return response()->json([
          'message' => 'Not found',
          'success' => false,
        ], 404);
      if(!($aid < $questions[$qid]->answers->count()))
        return response()->json([
          'message' => 'Not found',
          'success' => false,
        ], 404);
      $answer = $questions[$qid]->answers[$aid];
      if($answer->delete())
        return response()->json([
          'message' => 'Answer successfully deleted',
          'success' => true,
        ], 201);
      else
        return response()->json([
          'message' => 'Answer could not be deleted',
          'success' => false,
        ], 500);
    }
}
