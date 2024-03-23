<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;

class bannerController extends Controller
{
    // public function store(Request $request)
    // {
    //     // Validate request data
    //     // $request->validate([
    //     //     'name' => 'required',
    //     // ]);

    //     // Check if a file is present in the request
    //     if ($request->hasFile('image')) {
    //         // Upload image file to the storage directory
    //         $imagePath = $request->file('file')->store('public/ui/assets/img/backgrounds/');

    //         // Create new banner record
    //         $banner = new Banner();
    //         $banner->image = $imagePath;
    //         $banner->save();

    //         return response()->json([
    //             'status' => 200,
    //             'message' => 'Banner inserted successfully',
    //             'data' => $banner
    //         ], 200);
    //     } else {
    //         return response()->json([
    //             'status' => 400,
    //             'message' => 'No image file uploaded'
    //         ], 400);
    //     }
    // }
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


         $request->validate([
            'images.*' => 'required|image|mimes:jpg,jpeg,png,webp',
        ]);
        $imageData = [];
        if ($files = $request->file('images')) {
            $now = now();
            foreach ($files as $file) {
                
                $extension = $file->getClientOriginalExtension();
                $fileName = time() . '_' . uniqid() . '.' . $extension;

                $path = "uploads/banners/";
                $file->move($path, $fileName);

                $imageData[] = [
                    'image' => $path . $fileName,
                    'created_at'=>now(),
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
