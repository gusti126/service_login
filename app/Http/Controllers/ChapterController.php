<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Http;

class ChapterController extends BaseController
{
    public function index()
    {
        $response = Http::get(env('CHAPTER_SERVICE'));
        $data = $response->json();
        $data['http_code'] = $response->getStatusCode();

        return $data;
    }

    public function show($id)
    {
        $response = Http::get(env('CHAPTER_SERVICE').$id);
        $data = $response->json();
        $data['http_code'] = $response->getStatusCode();
        if($data['status'] === 'error')
        {
            return response()->json([
                'status' => 'error',
                'message' => $data['message']
            ], 404);
        }
        return $data;
    }
}
