<?php

namespace App\Http\Controllers;

use App\Models\Profile;

class ProfileController extends Controller
{
    public function index()
    {
        $data = Profile::with('user')->get();

        return response()->json([
            'status' => 'success',
            'message' => 'data list profile',
            'data' => $data
        ], 200);
    }

    public function show($id)
    {
        $data = Profile::with('user')->find($id);
        if(!$data)
        {
            return response()->json([
                'status' => 'error',
                'message' => 'id profile tidak ada'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'detail profile',
            'data' => $data
        ])
    }
}
