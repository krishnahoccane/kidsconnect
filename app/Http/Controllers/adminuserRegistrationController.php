<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class adminuserRegistrationController extends Controller
{
    //

    public function index()
    {
        return view('security/registration');
    }

    public function view(Request $request)
    {
        $request->validate(
            [
                'username' => 'required',
                'email' => 'required|email',
                'password' => 'required'
            ]
        );

        echo "<pre>";
        print_r($request->all());
    }
}
