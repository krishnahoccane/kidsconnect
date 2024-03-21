<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;

class bannerController extends Controller
{
    public function store(Request $request)
    {
        // Validate request data
        // $request->validate([
        //     'name' => 'required',
        // ]);
    
        // Check if a file is present in the request
        if ($request->hasFile('image')) {
            // Upload image file to the storage directory
            $imagePath = $request->file('file')->store('public/ui/assets/img/backgrounds/');
    
            // Create new banner record
            $banner = new Banner();
            $banner->image = $imagePath;
            $banner->save();
    
            return response()->json([
                'status' => 200,
                'message' => 'Banner inserted successfully',
                'data' => $banner
            ], 200);
        } else {
            return response()->json([
                'status' => 400,
                'message' => 'No image file uploaded'
            ], 400);
        }
    }
    

}
