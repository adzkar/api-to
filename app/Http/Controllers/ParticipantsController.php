<?php

namespace App\Http\Controllers;

use Validator;
use App\Participants;
use App\Verification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ParticipantsResource as ParResource;

class ParticipantsController extends Controller
{
    public function getAll()
    {
      return ParResource::collection(Participants::paginate(10));
    }

    public function getByNum($id = null)
    {
      if($id == null)
        return response()->json([
          'message' => 'Id Missmatch',
          'success' => true,
        ], 204);
      $par = Participants::All();
      if($id < $par->count())
        return new ParResource($par[$id]);
      return response()->json([
        'message' => 'Not Found',
        'success' => false,
      ]);
    }

    public function findById($id = null)
    {
      if($id == null)
        return response()->json([
          'message' => 'Id Missmatch',
          'success' => true,
        ]);
      $par = Participants::find($id);
      if($par == null)
        return response()->json([
          'message' => 'Not Found',
          'success' => false
        ]);
      return new Participants($par->toArray());
    }

    public function register(Request $req)
    {
      $this->validate($req, [
        'first_name' => 'required|string|max:20|min:1',
        'last_name' => 'required|string|max:20|min:1',
        'username' => 'required|string|max:20|min:1|unique:participants',
        'password' => 'required|min:6',
        'school' => 'required',
        'code' => 'required',
      ]);
      // Get verification id
      $ver = Verification::where(['code' => $req->code,'status' => 'p'])
             ->first();
      if(!$ver)
        return response()->json([
          'success' => false,
          'error' => 'Invalid Verification code'
        ]);
      // check code test
      if(!($ver->start < date('Y-m-d H:i:s') && date('Y-m-d H:i:s') < $ver->end))
        return response()->json([
          'success' => false,
          'message' => 'The code has expired',
        ], 401);
      if($ver) {
        $com = Participants::create([
                  'first_name' => $req->first_name,
                  'last_name' => $req->last_name,
                  'username' => $req->username,
                  'school' => $req->school,
                  'password' => md5($req->password),
                  'id_ver' => $ver->id_ver,
               ]);
         if($com)
            return response()->json([
              'message' => 'Participant Created',
              'success' => true,
            ]);
          else
            return response()->json([
              'error' => 'Something went wrong',
              'success' => false
            ], 500);
      } else {
          return response()->json([
            'error' => [
              'code' => 'Invalid code verification'
            ]
          ], 401);
      }
    }

    public function login(Request $req)
    {
      // $req->validate([
      //   'username' => 'required',
      //   'password' => 'required',
      // ]);
      $validator = Validator::make($req->all(), [
        'username' => 'required',
        'password' => 'required',
      ]);
      if(count($validator->errors()))
        return response()->json([
          'success' => false,
          'error' => $validator->errors(),
        ],401);
      $credential = [
        'username' => $req->username,
        'password' => md5($req->password),
      ];
      $com = Participants::where($credential)->first();
      if($com) {
          $token = $com->createToken('Committees Token')
                       ->accessToken;
          return response()->json([
            'success' => true,
            'token' => $token,
          ]);
      }
      return response()->json([
              'success' => false,
              'error' => 'Invalid login',
            ], 401);
    }

    public function detail()
    {
      return Auth::user();
    }

    public function logout($value='')
    {
      $user = Auth::user();
      $revoke = $user->token()
                     ->revoke();
      if($revoke)
        return response()->json([
          'message' => 'Successfully logged out',
          'success' => true,
        ]);
      return response()->json([
        'message' => 'Unsuccessfully logged out',
        'success' => false,
      ]);
    }

    public function update(Request $req, $id = null)
    {
      if($id == null)
        return response()->json([
          'message' => 'Id Missmatch',
          'errors' => 'Id Missing',
        ], 404);
      $par = Participants::find($id);
      if(!$par)
        return response()->json([
          'message' => 'Id Participant '.$id.' not found',
          'success' => false,
        ], 404);
        // $update = $ver->fill($req->all())->save();
      $update = $par->update($req->all());
      if ($update)
          return response()->json([
              'message' => 'Participant '.$id.' successfully updated',
              'success' => true,
          ], 201);
      else
          return response()->json([
              'message' => 'Participant could not be updated',
              'success' => false,
          ], 500);
    }

    public function delete($id = null)
    {
      if($id == null)
        return response()->json([
          'message' => 'Id Missmatch',
          'errors' => 'Id Missing',
        ], 404);
      $ver = Participants::find($id);
      if(!$ver)
        return response()->json([
          'message' => 'Id Participant '.$id.' not found',
          'success' => false,
        ], 404);
      if($ver->delete())
          return response()->json([
              'message' => 'Id Participant '.$id.' successfully deleted',
              'success' => true,
          ]);
      else
          return response()->json([
              'message' => 'Participant could not be deleted',
              'success' => false,
          ], 500);
    }

}
