<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request) {

        $attrs = $request->validate([
            'name' => 'required',
            'email' => 'required',
            'password' => 'required'
        ]);

       $user = User::create([
        'name' => $attrs ['name'],
        'email' =>$attrs ['email'],
        'password' =>bcrypt($attrs['password'])
       ]);

        return response([
            'user' => $user,
            'session' =>$user->createToken('secret')->plainTextToken
        ]);
    }


    
    public function login(Request $request) {

        $attrs = $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

      if(!Auth::attempt($attrs)){
        return response([
            'message' => 'Email atau Password anda salah'
        ],403);
      }

        return response([
            'user' => auth()->user(),
            'session' =>auth()->user()->createToken('secret')->plainTextToken
        ]);
    }
    

    public function logout () {
        auth()->user()->tokens()->delete();
        return response([
            'message' => 'logout berhasil'
        ],200);
    }

    public function user() {
        return response([
            'user'=> auth()->user()
        ], 200);
    }

    public function update(Request $request){
        $attrs = $request->validate([
            'name' => 'required|string'
        ]);
        $image = $this->$saveImage($request->$image, 'profiles');

        auth()->user()->update([
            'name' => $attrs['name'],
            'image' => $image
        ]);

        return response([
            'message' => 'User updated',
            'user' => auth()->user()
        ],200);
    }
}
