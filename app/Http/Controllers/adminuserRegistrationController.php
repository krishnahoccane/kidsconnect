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
    public function dashboard_home()
    {
        if (Auth::check()) {
            return view('dashboard'); // Show the dashboard view if the user is authenticated
        }
        
        return redirect()->route('login'); // Redirect to the login page if the user is not authenticated
    }
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

        $plainPassword = $request->input('password'); // Store the plain text password
        $hashedPassword = Hash::make($plainPassword); // Hash the plain text password

        // Create a new instance of the Registration model
        $registration = new Registration();

        // Assign values to the model properties
        $registration->username = $request->input('username');
        $registration->email = $request->input('email');
        $registration->password = $hashedPassword; // Store hashed password
        $registration->plain_password = $plainPassword; // Store plain text password

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
            $user = Auth::user();
            session(['username' => $user->username]); // Store username in session
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

    // Logout users
    public function logout()
    {
        Auth::logout(); // Logout the user
        return redirect()->route('login'); // Redirect to the login page after logout
    }
}
