<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Registration;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('security.login');
    }

    public function login(Request $request)
    {
        dd($request->all());
       $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        if(\Auth::attempt($request->only('email','password')))
        {
            return redirect()->route('/dashboard');
        }
        $status = "Invalid username or password.";
        return redirect('/')->with('error', $status);
        // Retrieve username and password from the request
        // $username = $request->input('username');
        // $password = $request->input('password');

        // // Attempt to authenticate the user
        // if (Auth::attempt(['name' => $username, 'password' => $password])) {
        //     // Authentication succeeded, redirect to the dashboard
        //     return redirect()->route('/dashboard');
        // } else {
        //     // Authentication failed, redirect back with error message
        //     $status = "Invalid username or password.";
        //     return redirect('/')->with('error', $status);
        // }
    }
}
