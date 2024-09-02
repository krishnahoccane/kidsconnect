<?php

namespace App\Http\Controllers;

use App\Mail\ContactUsMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

class ContactUsController extends Controller
{
    // Show the contact form
    public function show()
    {
        return view('contactus');
    }

    // Handle form submission
    public function submit(Request $request)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'message' => 'required|string|max:5000',
        ]);

        // Prepare the details for the email
        $details = [
            'name' => $request->name,
            'email' => $request->email,
            'message' => $request->message,
        ];

        // Send the email
        Mail::to('anita@glansa.in')->send(new ContactUsMail($details));

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Your message has been sent!');
    }
}
