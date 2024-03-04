<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Registration;
use Illuminate\Support\Facades\Hash; // Import the Hash facade
use App\Exports\RegistrationsExport;

use Maatwebsite\Excel\Facades\Excel;


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

        $hashedPassword = Hash::make($request->input('password'));
        // Create a new instance of the Registration model
        $registration = new Registration();

        // Assign values to the model properties
        $registration->username = $request->input('username');
        $registration->email = $request->input('email');
        $registration->password = $hashedPassword; // Store hashed password

        // Save the model to the database
        $registration->save();

        $status = "User Registered Successfully";
        return redirect('/')->with('status', $status);
    }
    public function showLoginForm()
    {
        return view('security.login');
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return redirect()->intended('dashboard'); // Redirect to the intended URL after successful authentication
        }

        // Authentication failed...
        return redirect()->back()->withErrors(['email' => 'Invalid email or password']);
    }
        
        public function dashboard(){
            
            return view('dashboard');
        }
        public function export()
    {
        return Excel::download(new RegistrationsExport(), 'registrations.xlsx');
    }
}
