<?php

namespace App\Http\Controllers;

use App\Verification;
use Illuminate\Http\Request;
use App\Http\Resources\VerificationResource as VerResource;

class VerificationController extends Controller
{

    public function getAll()
    {
      return VerResource::collection(Verification::All());
    }

    public function add(Request $req)
    {
      $this->validate($req, [
        'code' => 'required|string',
        'status' => 'required|max:1',
        'start' => 'required',
        'end' => 'required'
      ]);
      $ver = Verification::create([
              'code' => $req->code,
              'status' => $req->status,
              'start' => $req->start,
              'end' => $req->end,
            ]);
      if($ver)
        return response()->json([
          'message' => 'Verification code Created',
          'status' => true,
        ], 201);
      return response()->json([
        'message' => 'Verification code unsuccessfully created',
        'status' => false,
      ], 500);
    }

    public function getByNum($id = null)
    {
      if($id == null)
        return response()->json([
          'message' => 'Id Missmatch',
          'success' => true,
        ], 204);
      $ver = Verification::All();
      if($id < $ver->count())
        return new VerResource($ver[$id]);
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
      $ver = Verification::find($id);
      if($ver == null)
        return response()->json([
          'message' => 'Not Found',
          'success' => false
        ]);
      return new VerResource($ver);
    }

    public function update(Request $req, $id = null)
    {
      if($id == null)
        return response()->json([
          'message' => 'Id Missmatch',
          'errors' => 'Id Missing',
        ], 404);
      $ver = Verification::find($id);
      if(!$ver)
        return response()->json([
          'message' => 'Id Verification '.$id.' not found',
          'success' => false,
        ], 404);
        // $update = $ver->fill($req->all())->save();
      $update = $ver->update($req->all());
      if ($update)
          return response()->json([
              'message' => 'Verification code '.$id.' successfully updated',
              'success' => true,
          ], 201);
      else
          return response()->json([
              'success' => false,
              'message' => 'Verification code could not be updated'
          ], 500);
    }

    public function delete($id = null)
    {
      if($id == null)
        return response()->json([
          'message' => 'Id Missmatch',
          'errors' => 'Id Missing',
        ], 404);
      $ver = Verification::find($id);
      if(!$ver)
        return response()->json([
          'success' => false,
          'message' => 'Id Verification '.$id.' not found',
        ], 404);
      if($ver->delete())
          return response()->json([
              'message' => 'Verification code '.$id.' successfully deleted',
              'success' => true,
          ]);
      else
          return response()->json([
              'message' => 'Verification code could not be deleted',
              'success' => false,
          ], 500);
    }

}
