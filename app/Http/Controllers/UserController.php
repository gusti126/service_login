<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $data = User::with('profile')->get();

        return response()->json([
            'status' => 'success',
            'message' => 'list data user',
            'data' => $data
        ], 200);
    }

    public function detailsLogin()
    {
        $userId = Auth::user()->id;
        $user = User::with('profile')->findOrFail($userId);
        return response()->json([
            'status' => 'success',
            'data' => $user
        ], 200);
    }

    public function show($id)
    {
        $user = User::with('profile')->find($id);

        if(!$user)
        {
            return response()->json([
                'status' => 'error',
                'message' => 'id user tidak ada'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $user
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request , [
             'name' => '|string',
             'email' => '|unique:users|email',
             'password' => '|min:6'
         ]);
         $data = $request->all();
        $user = User::find($id);
        if(!$user)
        {
            return response()->json([
                'status' => 'success',
                'message' => 'id user tidak ada'
            ], 404);
        }

        // $user->name = $name;
        // $user->email = $email;
        // $user->password = $hasPassword;
        $user->fill($data);
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'data berhasil di update',
            'data' => $user
        ], 200);
        //  $user = User::create([
        //      'name' => $name,
        //      'email' => $email,
        //      'password' => $hasPassword

        //  ]);
    }

    public function destroy($id)
    {
        $user = User::find($id);
        if(!$user)
        {
            return response()->json([
                'status' => 'error',
                'message' => 'id user tiak di temukan'
            ], 404);
        }

        $user->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'data berhasil di delet'
        ], 200);
    }
}
