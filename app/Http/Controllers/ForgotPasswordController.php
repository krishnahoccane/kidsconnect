<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Registration;

class ForgotPasswordController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }
    public function showLinkRequestForm()
    {
        return view('security/forgotPassword');
    }
    

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = Registration::where('email', $request->email)->first();

        if ($user) {
            // Get the original password (plaintext)
            $password = $user->plain_password; // Assuming you have a column named 'plain_password' in your 'registrations' table
            $username = $user->username;
            
            // Send the password via email
            Mail::html("
            <p>Hi {$username},</p>
            <p>Your current username and password are given below:</p>
            <p>Username: {$user->email}</p>
            <p>Password: {$password}</p>
            <p><a href='" . url('/') . "'>Click Here</a> to login to your Kids Connect Admin Account</p>
            <p>Thanks,</p>
            <p>Kids Connect Team!</p>
            <p><strong>Disclaimer:</strong> This email was sent to you because you are registered with Kids Connect.com. This is a system-generated mail. Please don't reply to this email. If you have any queries, please reach out to us at <a href='mailto:support@freshersworld.com'>support@kidconnect.com</a>.</p>
        ", function ($message) use ($user) {
            $message->to($user->email)
            ->subject('Your forgot password Information ' . $user->username)
            ->from(config('mail.from.address'), config('mail.from.name')); // Set the subject and from address
        });
            return back()->with('status', 'Password sent to your email.');
        } else {
            $errorMessage = 'User not found.';
            return back()->withErrors(['email' => $errorMessage]);
        }
    }


    // change password code

    public function showChangeForm()
    {
        return view('security/changePassword');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            
            'current_password' => 'required',
            'new_password' => 'required|min:8|',
            'confirm_password' =>'required|min:8|same:new_password'

        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Current password is incorrect');
        }

        $user->password = Hash::make($request->new_password);
        $user->plain_password = $request->new_password;
        $user->save();

        

        return back()->with('status', 'Password updated successfully.');
    }
}
