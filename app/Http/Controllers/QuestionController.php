<?php

namespace App\Http\Controllers;

use App\Questions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\QuestionResource as QuestRes;
use App\Http\Resources\AnswerResource as AnsRes;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $user = Auth::user();
        // return ['status' => $true,'id' => $id, 'totle' => $user->tests->count()];
        if($id < $user->tests->count())
          if($user->tests[$id]->questions->count() == 0)
            return response()->json([
              'message' => 'empty',
              'success' => false,
            ]);
          else
            return QuestRes::collection($user->tests[$id]->questions);
        else
          return response()->json([
            'message' => 'Not found',
            'success' => false,
          ], 404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req, $id)
    {
        $user = Auth::user();
        if($id > $user->tests->count()-1)
          return response()->json([
            'message' => 'Not found',
            'success' => false,
          ]);
        $test = $user->tests[$id];
        $req->validate([
          'content' => 'required',
        ]);
        $save = Questions::create([
          'content' => $req->content,
          'id_test' => $test->id_test,
        ]);
        if($save)
          return response()->json([
            'message' => 'Question Created',
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
    public function show($id, $qid)
    {
        $user = Auth::user();
        $true = $qid < $user->tests[$id]->questions->count();
        // return ['status' => $true];
        if($qid > $user->tests[$id]->questions->count()-1 || $id > $user->tests->count()-1)
          return response()->json([
            'message' => 'Not found',
            'success' => false,
          ]);
        return new QuestRes($user->tests[$id]->questions[$qid]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $req, $id,$qid)
    {
      $user = Auth::user();
      $question = $user->tests[$id]->questions[$qid];
      if(!$question)
        return response()->json([
          'message' => 'Question not found',
          'success' => false,
        ], 404);
      if($question->update($req->all()))
        return response()->json([
          'message' => 'Question successfully updated',
          'success' => true,
        ], 201);
      else
        return response()->json([
          'message' => 'Question could not be updated',
          'success' => false,
        ], 500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, $qid)
    {
        $user = Auth::user();
        $question = $user->tests[$id]->questions[$qid];
        if(!$question)
          return response()->json([
            'message' => 'Question not found',
            'success' => false,
          ], 404);
        if($question->delete())
          return response()->json([
            'message' => 'Question successfully deleted',
            'success' => true,
          ], 201);
        else
          return response()->json([
            'message' => 'Question could not be deleted',
            'success' => false,
          ], 500);
    }
}
