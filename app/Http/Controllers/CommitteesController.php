<?php

namespace App\Http\Controllers;

use App\Committees;
use App\Verification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\CommitteesResource as ComResource;

use Validator;

class CommitteesController extends Controller
{

    public function getAll()
    {
      return ComResource::collection(Committees::paginate(10));
    }

    public function findById($id = null)
    {
      if($id == null)
        return response()->json([
          'message' => 'Id Missmatch',
          'success' => true,
        ]);
      $par = Committees::find($id);
      if($par == null)
        return response()->json([
          'message' => 'Not Found',
          'success' => false
        ]);
        return new ComResource($par);
    }

    public function getByNum($id = null)
    {
      if($id == null)
        return response()->json([
          'message' => 'Id Missmatch',
          'success' => true,
        ], 204);
      $par = Committees::All();
      if($id < $par->count())
        return new ComResource($par[$id]);
      return response()->json([
        'message' => 'Not Found',
        'success' => false,
      ]);
    }

    public function register(Request $req)
    {
      $validator = Validator::make($req->all(), [
        'name' => 'required|string|max:20|min:1',
        'username' => 'required|string|max:20|min:1|unique:committees',
        'password' => 'required|min:6',
        'code' => 'required'
      ]);
      if(count($validator->errors()))
        return response()->json([
          'success' => false,
          'errors' => $validator->errors(),
        ]);
      // Get verification id
      $ver = Verification::where(['code' => $req->code,'status' => 'c'])
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
        $com = Committees::create([
                  'name' => $req->name,
                  'username' => $req->username,
                  'password' => md5($req->password),
                  'id_ver' => $ver->id_ver,
               ]);
         if($com)
            return response()->json([
              'message' => 'Committees Created'
            ]);
          else
            return response()->json([
              'error' => 'Something went wrong'
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
      $this->validate($req, [
        'username' => 'required',
        'password' => 'required',
      ]);
      $credential = [
        'username' => $req->username,
        'password' => md5($req->password),
      ];
      $com = Committees::where($credential)->first();
      if($com) {
          $token = $com->createToken('Committees Token')
                       ->accessToken;
          return response()->json([
            'token' => $token,
          ]);
      } else
          return response()->json([
                  'error' => 'Invalid login'
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
      $par = Committees::find($id);
      if(!$par)
        return response()->json([
          'message' => 'Id Committees '.$id.' not found',
          'success' => false,
        ], 404);
        // $update = $ver->fill($req->all())->save();
      $update = $par->update($req->all());
      if ($update)
          return response()->json([
              'message' => 'Committees '.$id.' successfully updated',
              'success' => true,
          ], 201);
      else
          return response()->json([
              'message' => 'Committees could not be updated',
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
      $ver = Committees::find($id);
      if(!$ver)
        return response()->json([
          'message' => 'Id Committees '.$id.' not found',
          'success' => false,
        ], 404);
      if($ver->delete())
          return response()->json([
              'message' => 'Id Committees '.$id.' successfully deleted',
              'success' => true,
          ]);
      else
          return response()->json([
              'message' => 'Committees could not be deleted',
              'success' => false,
          ], 500);
    }

}
