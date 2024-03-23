<?php

namespace App\Http\Controllers\Api;

use Validator;
use App\Mail\OtpEmail;
use App\Models\subscriberlogins;
use Illuminate\Http\Request;
use App\Models\subscriberlogins;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Mail\ForgotPasswordEmail;
use Illuminate\Support\Facades\Crypt;


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
            return response()->json(['status' => 400, 'message' => 'Your Email Already Exist'], 400);
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

    // Forgot password
    public function forgotPassword(Request $request)
    {
        // Validate the request
        $request->validate([
            'email' => 'required|email'
        ]);

        // Retrieve email from the request
        $email = $request->input('email');

        // Check if a subscriber with the provided email exists
        $subscriberLogin = subscriberlogins::where('Email', $email)->first();

        // If subscriber not found, return error response
        if (!$subscriberLogin) {
            return response()->json(['status' => 404, 'message' => 'Subscriber not found'], 404);
        }

        // Retrieve the encrypted password associated with the email
        $encryptedPassword = $subscriberLogin->password;

        \Log::info('Encrypted Password: ' . $encryptedPassword);


        // Decrypt the password
        try {
            $password = Crypt::decryptString($encryptedPassword);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            // Handle decryption failure
            return response()->json(['message' => 'Failed to decrypt password'], 500);
        }

        // Send the email with the decrypted password
        try {
            Mail::to($email)->send(new ForgotPasswordEmail($email, $password));
            return response()->json(['status' => 200, 'message' => 'Password sent successfully'], 200);
        } catch (\Exception $e) {
            // Handle the exception if email sending fails
            return response()->json(['message' => 'Failed to send password'], 500);
        }
    }


}
