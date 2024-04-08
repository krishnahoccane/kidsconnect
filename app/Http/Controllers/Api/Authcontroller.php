<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\subscriberlogins;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'Email' => 'required|email',
            'password' => 'required|string'
        ]);

        $subscriber = subscriberlogins::where('Email', $request->input('Email'))->first();

        if (!$subscriber || !password_verify($request->input('password'), $subscriber->password)) {
            // Log authentication failure
            Log::info('Authentication attempt failed for email: ' . $request->input('Email'));

            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Generate token using Laravel Passport
        $token = $subscriber->createToken('kidsconnect')->accessToken;
        $username = $subscriber->FirstName . " " . $subscriber->LastName;
        $email = $subscriber->Email;

        return response()->json([
            'token' => $token,
            'username' => $username,
            'email' => $email
        ], 200);
    }
}
