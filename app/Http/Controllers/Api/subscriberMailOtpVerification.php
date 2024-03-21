<?php

namespace App\Http\Controllers\Api;

use Validator;
use App\Mail\OtpEmail;
use Illuminate\Http\Request;
use App\Models\subscriberlogins;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class SubscriberMailOtpVerification extends Controller
{
    // Getting mail and OTP from the application
    public function getInfoFromApp(Request $request)
    {
        // Validate the request
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required'
        ]);

        //Getting the data and comparing with subscribers Email with input email
        $subLoginEmailMatch = subscriberlogins::where('Email', $request->email)->first();

        // Checking the conditions if it matched says-- "Account exist"
        // Else OTP will sent
        if ($subLoginEmailMatch) {
            return response()->json(['status' => 400, 'message' => 'Account Already Existing'], 400);
        } else {
            $email = $request->input('email');
            $otp = $request->input('otp');
            try {
                Mail::to($email)->send(new OtpEmail($otp, $email));
                return response()->json(['status' => 200, 'message' => 'OTP sent successfully'], 200);
            } catch (\Exception $e) {
                // Handle the exception if email sending fails
                return response()->json(['message' => 'Failed to send OTP'], 500);
            }
        }
        
    }
}
