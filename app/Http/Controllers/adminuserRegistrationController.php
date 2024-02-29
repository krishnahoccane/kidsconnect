<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Registration;

class adminuserRegistrationController extends Controller
{
    //

    public function index()
    {
        return view('security/registration');
    }

    public function view(Request $request)
{
    $request->validate([
        'username' => 'required',
        'email' => 'required|email',
        'password' => 'required'
    ]);

    // Create a new instance of the Registration model
    $registration = new Registration();

    // Assign values to the model properties
    $registration->name = $request->input('username');
    $registration->email = $request->input('email');
    $registration->password = $request->input('password');

    // Save the model to the database
    $registration->save();

    // You might want to redirect or return a response here
    // For example:
    // return redirect()->back()->with('success', 'Registration successful!');

    return redirect('/')->with('success', 'Registration successful! You can now login.');
}
}
