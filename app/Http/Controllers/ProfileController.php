<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;

use Symfony\Component\HttpFoundation\File\UploadedFile;

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
        ], 200);
    }

    public function update(Request $request, $id)
    {
        // $this->validate($request , [
        //      'image' => 'image',
        //  ]);
         $data = $request->all();
         $profile = Profile::find($id);
        if(!$profile)
        {
            return response()->json([
                'status' => 'success',
                'message' => 'id profile tidak ada'
            ], 404);
        }
        $image_name = time().$request->file('image')->getClientOriginalName();
         $request->file('image')->move('upload', $image_name);
         $data['image'] = url('upload'.'/'.$image_name);
        //  $profile->fill($data);
        // $profile->save();
        $profile->update($data);

         return response()->json([
             'status' => 'success',
             'message' => 'data berhasil di update',
             'data' => $profile
         ]);

    }

    public function kurangPoint(Request $request)
    {
        $this->validate($request, [
             'user_id' => 'required|integer',
             'jumlah_point' => 'required|integer',
         ]);

         $userId = $request->input('user_id');
         $profile = Profile::where('user_id', $userId)->with('user')->first();
         if(!$profile)
        {
            return response()->json([
                'status' => 'error',
                'message' => 'id profile tidak ada'
            ], 404);
        }
         $userPoint = $profile->point;
         $penguranganPoint = $request->input('jumlah_point');

         $point = $userPoint - $penguranganPoint;
        // dd($point);
        $profile->update([
            'point' => $point
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'point berkurang',
            'data' => $profile
        ], 200);
    }
}
