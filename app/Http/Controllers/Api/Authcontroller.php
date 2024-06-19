<?php

// AuthController.php

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
            'Email' => 'sometimes|required|email',
            'phoneNumber' => 'sometimes|required|string',
            'password' => 'required|string'
        ]);

        if ($request->has('Email')) {
            return $this->loginWithEmail($request);
        } elseif ($request->has('phoneNumber')) {
            return $this->loginWithPhoneNumber($request);
        } else {
            throw ValidationException::withMessages([
                'Email or phoneNumber' => 'Email or phone number is required.',
            ]);
        }
    }

    private function loginWithEmail(Request $request)
    {
        $request->validate([
            'Email' => 'required|email',
            'password' => 'required|string'
        ]);

        $subscriber = subscriberlogins::where('Email', $request->input('Email'))->first();

        if (!$subscriber || !password_verify($request->input('password'), $subscriber->password)) {
            Log::info('Authentication attempt failed for email: ' . $request->input('Email'));
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $subscriber->createToken('kidsconnect')->accessToken;
        $username = $subscriber->FirstName . " " . $subscriber->LastName;
        $email = $subscriber->Email;

        return response()->json([
            'token' => $token,
            'username' => $username,
            'email' => $email
        ], 200);
    }

    private function loginWithPhoneNumber(Request $request)
    {
        $request->validate([
            'phoneNumber' => 'required|string',
            'password' => 'required|string'
        ]);

        $subscriber = subscriberlogins::where('phoneNumber', $request->input('phoneNumber'))->first();

        if (!$subscriber || !password_verify($request->input('password'), $subscriber->password)) {
            Log::info('Authentication attempt failed for phone number: ' . $request->input('phoneNumber'));
            throw ValidationException::withMessages([
                'phoneNumber' => ['The provided credentials are incorrect.'],
            ]);
        }

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

