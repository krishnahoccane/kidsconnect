<?php

namespace App\Http\Controllers\Api;

use Validator;
use App\Mail\OtpEmail;
use Illuminate\Http\Request;
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
}
