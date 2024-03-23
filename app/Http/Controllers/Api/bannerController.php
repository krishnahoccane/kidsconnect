<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;

class bannerController extends Controller
{
    public function index()
    {
        $banners = Banner::all();

        if ($banners->count() > 0) {
            return response()->json([
                'status' => 200,
                'data' => $banners
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No Data Is Found'
            ], 404);
        }
    }

    public function store(Request $request)
    {
        // Here we are taking the images array and validating them
        $request->validate([
            'images.*' => 'required|image|mimes:jpg,jpeg,png,webp',
        ]);
        $imageData = [];//declaring an empty array to store  the Images 
        if ($files = $request->file('images')) {
            // Looping the file and storing in the folder
            foreach ($files as $file) {
                $extension = $file->getClientOriginalExtension();
                $fileName = time() . '_' . uniqid() . '.' . $extension;

                $path = "uploads/banners/";
                $file->move($path, $fileName);
                //gving the column data in array of imageData
                $imageData[] = [
                    'image' => $path . $fileName,
                    'created_at' => now(),
                ];
            }

        }
        $banners = Banner::insert($imageData);
        if ($banners) {
            return redirect()->back()->with('status', 'File Uploaded Succseefully');
        }
    }

    public function show()
    {

    }

    public function destroy()
    {

    }


}
