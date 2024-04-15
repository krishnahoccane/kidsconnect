<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class imageUpload extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'ProfileImage' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // max 2MB
        ]);

        if ($request->hasFile('ProfileImage')) {
            $profileImage = $request->file('ProfileImage');
            $path = 'uploads/profiles/';
            $fileName = time() . '_' . uniqid() . '.' . $profileImage->getClientOriginalExtension();
            $profileImage->move($path, $fileName);
            $profileImagePath = $path . $fileName;
            return response()->json([
                'data' => $profileImagePath 
            ], 200);
        } else {
            return response()->json([
                'error' => 'Unacceptable format',
            ], 400);
        }

        
    }
}
