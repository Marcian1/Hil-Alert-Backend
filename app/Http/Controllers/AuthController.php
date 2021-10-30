<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

use Illuminate\Support\Facades\Hash;
use App\User;
use App\Http\Requests\RegisterRequest;
class AuthController extends Controller
{
    public function login(Request $request)
    {
        
        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
          
            $token = $user->createToken('admin')->accessToken;

            return [
                'token' => $token,
            ];
        }

        return response([
            'error' => 'Invalid Credentials!',
        ], 401);
    }

    public function register(RegisterRequest $request)
    {
        $user = new User;
        $user->username=$request->username;
        $user->password=Hash::make($request->password);
        $user->email=$request->email;
  

        $user->save();
  
        return response($user, Response::HTTP_CREATED);
    }
}