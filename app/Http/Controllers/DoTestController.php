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
use App\Participants;

// Resource
use App\Http\Resources\TestsParResource as TestsRes;
use App\Http\Resources\QuestionResource as QuestRes;
use App\Http\Resources\DetailResource as DetailRes;
use App\Http\Resources\AnswerParResource as AnsRes;
use App\Http\Resources\ResultResource as ResRes;
use App\Http\Resources\NewResultResource as NewRes;

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
        'score' => 0,
      ]);
      // set questions and shuffle questions
      $questions = $test->questions;
                        // ->shuffle();
      // create detailed results
      for($i = 0;$i < $questions->count();$i++) {
        $dr = new DetailedResults([
          'id_result' => $save->id_result,
        ]);
        $dr->question()->associate($questions[$i])->save();
      }
      // // create personal token
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

    public function startOffline(Request $req, $id)
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
        'score' => 0,
      ]);
      // set questions and shuffle questions
      $questions = $test->questions;
                        // ->shuffle();
      // create detailed results
      for($i = 0;$i < $questions->count();$i++) {
        $dr = new DetailedResults([
          'id_result' => $save->id_result,
        ]);
        $dr->question()->associate($questions[$i])->save();
      }
      // // create personal token
      $token = $user->createToken('Test Token', [ 'test' ])
                    ->accessToken;
      $data = DetailedResults::where(['id_result' => $save->id_result]);
      $answers = [];
      for ($i=1; $i <= $data->count(); $i++) {
        array_push($answers, [$i => null]);
      }
      // return token
      if($token)
        return response()->json([
          'success' => true,
          'token' => $token,
          'data' => $answers
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
      if(!($qid < $find->detail->count()))
        return response()->json([
          'success' => false,
          'message' => 'Invalid id',
        ], 404);
      if($qid != null)
        return new DetailRes($find->detail[$qid]);
      return DetailRes::collection($find->detail);
    }

    public function getAnswers($id, $qid, $aid = null)
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
      if(!($qid < $find->detail->count()))
        return response()->json([
          'success' => false,
          'message' => 'Invalid id',
        ], 404);
      if($aid != null)
        return new AnsRes($find->detail[$qid]->question->answers[$aid]);
      return AnsRes::collection($find->detail[$qid]->question->answers);
    }

    public function answer(Request $req, $id, $qid)
    {
      // Inital state
      $user = Auth::user();
      $tests = Tests::All();
      // $test = Test::find($id);
      // validation id
      if(!($id < $tests->count()))
        return response()->json([
          'success' => false,
          'message' => 'Invalid id',
        ], 404);
      $test = $tests[$id];
      // validation test date
      if(!($test->start < date('Y-m-d H:i:s') && date('Y-m-d H:i:s') < $test->end))
        return response()->json([
          'success' => false,
          'message' => 'The test has expired',
        ], 401);
      // validation airing test
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
      // Init detail
      $detail = $find->detail;
      // qid validation
      if(!($qid < $detail->count()))
        return response()->json([
          'success' => false,
          'message' => 'Invalid Question ID',
        ], 404);
      // body validation
      // $valid = Validator::make($req->all(), [
      //   'answer' => 'required|numeric'
      // ]);
      // if(count($valid->errors()))
      //   return response()->json([
      //     'success' => false,
      //     'errors' => $valid->errors(),
      //   ]);
      // answer validation
      $question = $detail[$qid]->question;
      $answers = $question->answers;
      if(!($req->answer < $answers->count()))
        return response()->json([
          'success' => false,
          'message' => 'Invalid answer',
        ], 404);
      // insert answer
      $detail = $detail[$qid];
      $answer = $answers[$req->answer];

      $detail->id_answer = $answer->id_answer;
      $detail->status = $answer->status;
      if($detail->save())
        return response()->json([
          'success' => true,
          'message' => 'The answer submitted',
        ], 201);
      return response()->json([
        'success' => false,
        'message' => 'Internal Server Error'
      ],500);
    }

    public function finish($id)
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
      $test = $tests[$id];
      $where = [
        'id_participant' => $user->id_participant,
        'id_test' => $test->id_test,
        'status' => 'airing'
      ];
      $find = Results::where($where)->first();
      if(!$find)
        return response()->json([
          'success' => false,
          'message' => 'You haven\'t start the test',
        ],404);
      $detail = $find->detail;
      $score = 0;
      for ($i=0; $i < $detail->count(); $i++) {
        if($detail[$i]->status === null)
          $score += $test->empty_value;
        if($detail[$i]->status === 0)
          $score += $test->wrong_value;
        if($detail[$i]->status === 1)
          $score += $test->true_value;
      }
      $find->status = 'aired';
      $find->score = $score;
      if($find->save())
        return response()->json([
          'success' => true,
          'message' => 'Test Finished'
        ],201);
      return response()->json([
        'success' => false,
        'message' => 'Internal server errors',
      ], 500);
    }

    public function finishOffline(Request $req, $id)
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
      $test = $tests[$id];
      $where = [
        'id_participant' => $user->id_participant,
        'id_test' => $test->id_test,
        'status' => 'airing'
      ];
      $find = Results::where($where)->first();
      if(!$find)
        return response()->json([
          'success' => false,
          'message' => 'You haven\'t start the test',
        ],404);

      // init
      $detail = $find->detail;
      $req = $req->All();

      // insert answer
      for ($i=0; $i < $detail->count(); $i++) {
        $question = $detail[$i]->question;
        $answers = $question->answers;

        if($req[$i][$i+1]) {
          $detail[$i]->id_answer = $answers[$req[$i][$i+1]]->id_answer;
          $detail[$i]->status = $answers[$req[$i][$i+1]]->status;
          $detail[$i]->save();
        }

      }

      $score = 0;
      for ($i=0; $i < $detail->count(); $i++) {
        if($detail[$i]->status === null)
          $score += $test->empty_value;
        if($detail[$i]->status === 0)
          $score += $test->wrong_value;
        if($detail[$i]->status === 1)
          $score += $test->true_value;
      }
      $find->status = 'aired';
      $find->score = $score;
      if($find->save())
        return response()->json([
          'success' => true,
          'message' => 'Test Finished'
        ],201);
      return response()->json([
        'success' => false,
        'message' => 'Internal server errors',
      ], 500);
    }

    public function results($id = null)
    {
      // init
      $user = Auth::user();
      // find
      $where = [
        'status' => 'aired',
        'id_participant' => $user->id_participant,
      ];
      $find = Results::where($where)->get();
      return ResRes::collection($find);
    }

    public function allResults($id = null)
    {
      $par = Participants::with([
        'results' => function($query) {
          $query->where(['status' => 'aired']);
        }
      ])
      ->has('results')
      ->paginate(10);
      if($id != null)
        return new NewRes($par[$id]);
      return NewRes::collection($par);
    }

    public function allResPagination()
    {
      $find = Results::where(['status' => 'aired'])->get();
      return ResRes::collection($find);
    }

}
