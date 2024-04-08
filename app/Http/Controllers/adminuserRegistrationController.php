<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\registration;
use Illuminate\Support\Facades\Hash; // Import the Hash facade
use App\Exports\RegistrationsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;



class adminuserRegistrationController extends Controller
{
    //
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }
    public function index()
    {
        return view('security/registration');
    }

    public function view(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'email' => 'required|email  ',
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
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);
    
            if (Auth::guard('registration')->attempt($request->only('email', 'password'))) {
                // Authentication succeeded
                $user = Auth::guard('registration')->user();
                // Log successful authentication
                Log::info('User authenticated successfully: ' . $user->email);
                // Store session variables with the data
                session([
                    'username' => $user->username,
                    'email' => $user->email,
                    'userid' => $user->id
                ]);
                return redirect()->route('dashboard');
            } else {
                // Authentication failed
                return redirect()->back()->withErrors(['email' => 'Invalid email or password']);
            }
            
        } catch (\Exception $e) {
            // Log authentication error
            Log::error('Authentication error: ' . $e->getMessage());
            // Redirect back with error message
            return redirect()->back()->withErrors(['email' => 'An error occurred during authentication. Please try again later.']);
        }
    }
    


    public function dashboard()
    {

        return view('dashboard');
    }
    public function export()
    {
        return Excel::download(new RegistrationsExport(), 'registration.xlsx');
    }

    // Logout users
    public function logout(Request $request)
    {
        Auth::logout(); // Logout the user

        // If you're using session, you can flush the session data
        $request->session()->flush();

        // If you're using session, you can regenerate the session ID to prevent session fixation attacks
        $request->session()->regenerate();

        return redirect()->route('showLoginForm'); // Redirect to the login form
    }
}
