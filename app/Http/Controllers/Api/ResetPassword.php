<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ForgotPasswordController;
use Illuminate\Support\Facades\Auth;
use App\Models\subscriberlogins;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\ForgotPasswordEmail;



class ResetPassword extends Controller
{
    public function showResetPasswordForm(Request $request)
    {
        // Retrieve the email from the request
        $email = $request->query('email');

        // Pass the email to the view
        return view('reset', compact('email'));
    }
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        // Check if the email exists in the database
        $subscriber = subscriberlogins::where('Email', $request->email)->first();

        if (!$subscriber) {
            return response()->json(['message' => 'Email not found'], 404);
        }

        // Send the password reset email
        Mail::to($subscriber->Email)->send(new ForgotPasswordEmail($subscriber->Email, $subscriber->password));

        // Return success response
        return response()->json(['message' => 'Reset link sent to the email'], 200);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'new_password' => 'required|min:8|confirmed',
        ]);

        // Retrieve the user by email
        $user = subscriberlogins::where('Email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'Email not found'], 404);
        }

        // Update the password
        $user->password = Hash::make($request->new_password);
        $user->save();

        // Return success response
        return response()->json(['message' => 'Password updated successfully'], 200);
    }

}
