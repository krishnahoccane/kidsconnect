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
    
        // Attempt authentication using the 'subscriberlogin' guard
        if (Auth::guard('subscriber_logins')->attempt($request->only('Email', 'password'))) {
            $user = Auth::guard('subscriber_logins')->user(); // Retrieve the authenticated user from the 'subscriberlogin' guard
    
            // Create a token using Laravel Passport
            $token = $user->createToken('kidsconnect')->accessToken;
            $firstName = $user->FirstName;
            $lastName = $user->LastName;
            $email = $user->Email;
    
            return response()->json([
                'token' => $token,
                'username'=> $firstName . " " . $lastName,
                'email'=> $email
            ], 200);
        } else {
            // Log authentication failure
            Log::info('Authentication attempt failed for email: ' . $request->input('Email'));
    
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

    

}
