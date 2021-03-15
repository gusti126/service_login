<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class mentorController extends BaseController
{
    public function getMentor()
    {
        $response = Http::get(env('MENTOR_SERVICE'));
         $data = $response->json();
         $data['http_code'] = $response->getStatusCode();
        return response()->json([
            'data' => $data
        ]);

    }

    public function create(Request $request)
    {
        // $data = $request->all();
        // // dd($data);
        // $image_name = time().$request->file('image')->getClientOriginalName();
        // $request->file('image')->move('upload', $image_name);
        // $data['image'] = url('upload'.'/'.$image_name);
        try {
            $data = $request->all();
            // dd($data);
            $image_name = time().Str::random(4).$request->file('image')->getClientOriginalName();
            $request->file('image')->move('upload', $image_name);
            $data['image'] = url('upload'.'/'.$image_name);
            // dd($data);
            $response = Http::post(env('MENTOR_SERVICE'), $data);
            $data = $response->json();
            $data['http_code'] = $response->getStatusCode();
            // $image = $request->file('image');
            // dd($data);
            return $data;
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'service mentor tidak aktif'
            ], 400);
        }

        return $response;

    }

    public function show($id)
    {
        $response = Http::get(env('MENTOR_SERVICE').$id);
         $data = $response->json();
         $data['http_code'] = $response->getStatusCode();

         if($data['status'] === 'error')
         {
             return response()->json([
                 'status' => $data['status'],
                 'message' => $data['message'],
             ], $data['http_code'] );
         }
        return $data;

    }
}
