<?php

namespace App\Http\Controllers;

use App\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function login(Request $req)
    {
      $this->validate($req, [
        'username' => 'required',
        'password' => 'required',
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
          'token' => $token,
        ]);
      }
      return response()->json([
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
          'message' => 'Successfully logged out',
          'status' => true,
        ]);
      return response()->json([
        'message' => 'Unsuccessfully logged out',
        'status' => false,
      ]);
    }

    public function detail()
    {
      return Auth::user();
    }
}
