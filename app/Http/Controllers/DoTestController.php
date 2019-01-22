<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Model
use App\Tests;
use App\Results;
use App\Verification;
use App\DetailedResults;

// Resource
use App\Http\Resources\TestsParResource as TestsRes;
use App\Http\Resources\QuestionResource as QuestRes;

class DoTestController extends Controller
{

    public function show()
    {
      return TestsRes::collection(Tests::All());
    }

    public function showByNum($id)
    {
      $tests = Tests::All();
      if(!($id < $tests->count()))
        return response()->json([
          'success' => false,
          'message' => 'Invalid id'
        ], 404);
      return new TestsRes($tests[$id]);
    }

    public function start(Request $req, $id)
    {
      // init
      $user = Auth::user();
      $tests = Tests::All();
      // check id
      if(!($id < $tests->count()))
        return response()->json([
          'success' => false,
          'message' => 'Invalid id'
        ], 404);
      // validation
      $validator = Validator::make($req->All(), [
        'code' => 'required',
      ]);
      // show errors
      if(count($validator->errors()))
        return response()->json([
          'success' => false,
          'erros' => $validator->errors(),
        ], 400);
      // test init
      $test = $tests[$id];
      // check date test
      if(!($test->start < date('Y-m-d H:i:s') && date('Y-m-d H:i:s') < $test->end))
        return response()->json([
          'success' => false,
          'message' => 'The test has expired',
        ], 401);
      // check validation code
      $ver = Verification::where([ 'code' => $req->code, 'status' => 't' ])
                           ->first();
      if(!$ver)
        return response()->json([
          'success' => false,
          'message' => 'Invalid Verification code',
        ], 400);
      // create results
      $save = Results::Create([
        'id_participant' => $user->id_participant,
        'id_test' => $test->id_test,
        'date' => date('Y-m-d'),
        'status' => 'airing',
      ]);
      // create detailed results
      
      // create personal token
      $token = $user->createToken('Test Token', [ 'test' ])
                    ->accessToken;
      // return token
      if($token)
        return response()->json([
          'success' => true,
          'token' => $token,
        ], 201);
      return response()->json([
        'success' => false,
        'message' => 'something went wrong',
      ], 500);
    }

    public function getQuestions($id, $qid = null)
    {
      $user = Auth::user();
      $tests = Tests::All();
      if(!($id < $tests->count()))
        return response()->json([
          'success' => false,
          'message' => 'Invalid id',
        ], 404);
      $test = $tests[$id];
      if(!($test->start < date('Y-m-d H:i:s') && date('Y-m-d H:i:s') < $test->end))
        return response()->json([
          'success' => false,
          'message' => 'The test has expired',
        ], 401);
      $find = [
        'id_participant' => $user->id_participant,
        'id_test' => $test->id_test,
        'status' => 'airing',
      ];
      $find = Results::where($find)->first();
      if(!$find)
        return response()->json([
          'success' => false,
          'message' => 'You haven\'t start the test',
        ],404);
      $questions = $find->test->questions;
      if($qid != null)
        return new QuestRes($questions[$qid]);
      return QuestRes::collection($questions);
    }

}
