<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Model
use App\Tests;
use App\Results;
use App\Verification;

// Resource
use App\Http\Resources\TestsParResource as TestsRes;

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
      $user = Auth::user();
      $tests = Tests::All();
      if(!($id < $tests->count()))
        return response()->json([
          'success' => false,
          'message' => 'Invalid id'
        ], 404);
      $validator = Validator::make($req->All(), [
        'code' => 'required',
      ]);
      if(count($validator->errors()))
        return response()->json([
          'success' => false,
          'erros' => $validator->errors(),
        ], 400);
      $ver = Verification::where([ 'code' => $req->code, 'status' => 't' ])
                           ->first();
      if(!$ver)
        return response()->json([
          'success' => false,
          'message' => 'Invalid Verification code',
        ], 400);
      $save = Results::Create([
        'id_participant' => $user->id_participant,
        'id_test' => $tests[$id]->id_test,
        'date' => date('Y-m-d'),
      ]);
      $token = $user->createToken('Test Token', [ 'test' ])
                    ->accessToken;
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

}
