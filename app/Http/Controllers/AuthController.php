<?php

namespace App\Http\Controllers;

use App\Http\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    use HttpResponses;

    public function login(Request $request) 
    {

        if (Auth::attempt($request->only('email', 'password'))) {
            return $this->success(
                [
                    'token' => $request->user()->createToken('invoice')->plainTextToken
                ], 'Authorized', 200);
        }
        
        return $this->success($request, 'Not Authorized', 200);
    }

    public function logout(Request $request) 
    {
        $request->user()->currentAccessToken()->delete();
        return $this->success([], 'Logged out', 200);
    }
}
