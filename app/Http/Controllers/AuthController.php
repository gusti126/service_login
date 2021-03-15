<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Lumen\Routing\Controller as BaseController;

class AuthController extends BaseController
{
     public function register(Request $request)
     {
         $this->validate($request, [
             'name' => 'required|string',
             'no_tlp' => 'required',
             'jenis_kelamin' => 'string|required',
             'email' => 'required|unique:users|email',
             'password' => 'required|min:6'
         ]);
         $name = $request->input('name');
         $email = $request->input('email');
         $password = $request->input('password');
         $hasPassword = Hash::make($password);

         $user = User::create([
             'name' => $name,
             'email' => $email,
             'password' => $hasPassword

         ]);
         $pool = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
         $referal = substr(str_shuffle(str_repeat($pool, 5)), 0, 9);
        $profile = Profile::create([
             'user_id' => $user->id,
             'tanggal_lahir' => $request->input('tanggal_lahir'),
             'no_tlp' => $request->input('no_tlp'),
             'jenis_kelamin' => $request->input('jenis_kelamin'),
             'alamat' => $request->input('alamat'),
             'referal' => $referal,
             'point' => 50
         ]);

         return response()->json([
             'status' => 'success',
             'data' => $user,
             'profile' => $profile
         ], 200);
     }

     public function login(Request $request)
     {
         $this->validate($request, [
             'email' => 'required|email',
             'password' => 'required|min:6'
         ]);
         $email = $request->input('email');
         $password = $request->input('password');

         $user = User::where('email', $email)->first();
         if(!$user){
             return response()->json([
                 'status' => 'error',
                 'message' => 'login failed'
             ], 401);
         }
         $isValidPass = Hash::check($password, $user->password);
         if(!$isValidPass){
             return response()->json([
                 'status' => 'error',
                 'message' => 'login failed'
             ], 401);
         }
         $generetToken = bin2hex(random_bytes(54));
         $user->update([
             'token' => $generetToken
         ]);

         return response()->json($user);

     }

     public function logout(Request $request)
    {
        // $logout = Auth::user()->token->revoke();
        // if($logout){
        //     return response()->json([
        //         'message' => 'Successfully logged out'
        //     ]);
        // }
         $user = $request->user(); //GET USER YANG SEDANG LOGIN
            $user->update(['token' => null]); //UPDATE VALUENYA JADI NULL
            return response()->json(['status' => 'success']);
    }

    public function cariReferal(Request $request)
    {
        $this->validate($request, [
            'referal' => 'string|required',
        ]);
        $referalC = $request->input('referal');
        $referal = Profile::where('referal', $referalC)->first();
        if(!$referal){
            return response()->json([
                'status' => 'error',
                'message' => 'Referal tidak di temukan'
            ]);
        }
        $user = User::with('profile')->where('id', $referal->id)->first();


        return response()->json([
            'status' => 'success',
            'data' => $user
        ], 200);
    }
    public function getUserAll()
    {
        $data = User::with('profile')->get();

        return response()->json([
            'status' => 'success',
            'data' => $data
        ], 200);
    }

    public function userDetail($id)
    {
        $data = User::with('profile')->find($id);
        if(!$data){
            return response()->json([
                'status' => 'error',
                'message' => 'data id user tidak di temukan'
            ]);
        }

        return response()->json([
            'status' => 'success',
            'data' => $data
        ], 200);
    }

    public function createPoint(Request $request)
    {
        $this->validate($request, [
             'referal' => 'required|string',
            //  'user_id' => 'required|integer',
             'jumlah_point' => 'required|integer',
         ]);

        $data = $request->all();
        //  dd($data);
        $cekReferal = Profile::where('referal', $data['referal'])->first();
        // dd($cekReferal);
        if(!$cekReferal){
            return response()->json([
                'status' => 'error',
                'message' => 'referal tidak di temukan'
            ], 404);
        }
        // $user = User::where('id', $data['user_id'])->with('profile')->first();
        // // dd($user);

        // if(!$user)
        // {
        //     return response()->json([
        //         'status' => 'error',
        //         'message' => 'id user tidak di temukan'
        //     ], 404);
        // }
        // if($user->profile->referal !== $data['referal'])
        // {
        //         return response()->json([
        //             'status' => 'error',
        //             'message' => 'error referal tidak cocok denga user id'
        //         ]);
        // }

        $masukan_point = $request->input('jumlah_point')+$cekReferal->point;
        $cekReferal->update([
            'point' => $masukan_point,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Point berhasil di tambahkan',
            // 'nama user' => $user->name,
            'point sekarang' => $cekReferal->point
        ], 200);
    }
}
