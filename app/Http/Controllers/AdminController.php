<?php

namespace App\Http\Controllers;

use App\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Validator;

class AdminController extends Controller
{
    public function login(Request $req)
    {
      $validator = Validator::make($req->all(), [
        'username' => 'required',
        'password' => 'required',
      ]);
      if(count($validator->errors()))
        return response()->json([
          'success' => false,
          'errors' => $validator->errors(),
        ]);
      $credintial = [
        'username' => $req->username,
        'password' => md5($req->password),
      ];
      $admin = Admin::where($credintial)->first();
      if($admin) {
        $token = $admin->createToken('Admin Token')
                       ->accessToken;
        return response()->json([
          'success' => true,
          'token' => $token,
        ]);
      }
      return response()->json([
        'success' => true,
        'error' => 'UnAuthorized',
      ], 401);
    }

    public function logout($value='')
    {
      $user = Auth::user();
      $revoke = $user->token()
                     ->revoke();
      if($revoke)
        return response()->json([
          'success' => true,
          'message' => 'Successfully logged out',
        ]);
      return response()->json([
        'success' => false,
        'message' => 'Unsuccessfully logged out',
      ], 500);
    }

    public function detail()
    {
      return Auth::user();
    }
}
