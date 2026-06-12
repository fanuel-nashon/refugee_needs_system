<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request){
        $credentials=$request->validate([
            'email'=>'required|email',
            'password'=>'required|string',
        ]);

        //attempt to login
        if(Auth::attempt($credentials)) {
            return response()->json([
                'status'=>'success',
                'message'=>'Login successful!'
            ], 200);
        }
        else {
            return response()->json([
                'status'=>'error',
                'message'=>'Invalid credentials',
                'user'=>Auth::user(), //pass back the user credentials
            ], 401);
        }
    }
}
