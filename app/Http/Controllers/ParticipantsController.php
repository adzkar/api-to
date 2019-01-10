<?php

namespace App\Http\Controllers;

use App\Participants;
use App\Verification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParticipantsController extends Controller
{
    public function register(Request $req)
    {
      $this->validate($req, [
        'name' => 'required|string|max:20|min:1',
        'username' => 'required|string|max:20|min:1|unique:committees',
        'password' => 'required|min:6',
        'code' => 'required'
      ]);
      // Get verification id
      $ver = Verification::where(['code' => $req->code,'status' => 'p'])
             ->first();
      if($ver) {
        $com = Participants::create([
                  'name' => $req->name,
                  'username' => $req->username,
                  'password' => md5($req->password),
                  'id_ver' => $ver->id_ver,
               ]);
         if($com)
            return response()->json([
              'message' => 'Committees Created',
              'status' => true,
            ]);
          else
            return response()->json([
              'error' => 'Something went wrong',
              'status' => false
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
      $com = Participants::where($credential)->first();
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
          'status' => true,
        ]);
      return response()->json([
        'message' => 'Unsuccessfully logged out',
        'status' => false,
      ]);
    }
}