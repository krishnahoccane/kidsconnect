<?php

namespace App\Http\Controllers\Api;

use Validator;
use App\Mail\OtpEmail;
use App\Models\subscriberlogins;
use Illuminate\Http\Request;
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

        // Retrieve email and OTP from the request
        $email = $request->input('email');
        $otp = $request->input('otp');

        // Send the OTP via email
        try {
            Mail::to($email)->send(new OtpEmail($otp,$email));
            return response()->json(['status' => 200, 'message' => 'OTP sent successfully'], 200);
        } catch (\Exception $e) {
            // Handle the exception if email sending fails
            return response()->json(['message' => 'Failed to send OTP'], 500);
        }

       

        // Mail::html("
        //     <p>Hi {$email},</p>
        //     <p>Your current username and password are given below:</p>
        //     <p>Username: {$email}</p>
        //     <p>Password: {$otp}</p>
        //     <p><a href='" . url('/') . "'>Click Here</a> to login to your Kids Connect Admin Account</p>
        //     <p>Thanks,</p>
        //     <p>Kids Connect Team!</p>
        //     <p><strong>Disclaimer:</strong> This email was sent to you because you are registered with Kids Connect.com. This is a system-generated mail. Please don't reply to this email. If you have any queries, please reach out to us at <a href='mailto:support@freshersworld.com'>support@kidconnect.com</a>.</p>
        // ", function ($message) use ($email) {
        //     $message->to($email)
        //         ->subject('Your forgot password Information ' . $email)
        //         ->from(config('mail.from.address'), config('mail.from.name')); // Set the subject and from address
        // });
        // return response()->json(['status' => 200, 'message' => 'OTP sent successfully'], 200);
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
