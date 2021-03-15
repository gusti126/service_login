<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Http;

class CourseController extends BaseController
{
    public function index()
    {
        $response = Http::get(env('COURSE_SERVICE'));
        $data = $response->json();
        $data['http_code'] = $response->getStatusCode();

        return $data;
    }

    public function show($id)
    {
        $response = Http::get(env('COURSE_SERVICE').$id);
        $data = $response->json();
        $data['http_code'] = $response->getStatusCode();
        if($data['status'] === 'error')
        {
            return response()->json([
                'status' => 'error',
                'message' => $data['message']
            ], $data['http_code']);
        }
        return $data;

    }

    public function create(Request $request)
    {
        $data = $request->all();
        $response = Http::post(env('COURSE_SERVICE'), $data);
        $data = $response->json();
        $data['http_code'] = $response->getStatusCode();

        return $response;
    }
}
