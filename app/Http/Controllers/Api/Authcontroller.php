<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class Authcontroller extends Controller
{
    //

    public function login(Request $request)
    {
        $request->validate([
            'Email' => 'required|email',
            'password' => 'required|string'
        ]);

        if (Auth::attempt($request->only('Email', 'password'))) {
            $user = Auth::user();
            $token = $user->createToken('kidsconnect')->accessToken;
            $FirstName = $user->FirstName;
            $LastName = $user->LastName;
            $email = $user->Email;


            return response()->json([
                'token' => $token,
                'username'=> $FirstName." ".$LastName,
                'email'=>$email
            ], 200);
        } else {
            // Log authentication failure
            Log::info('Authentication attempt failed for email: ' . $request->input('Email'));

            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }



}
