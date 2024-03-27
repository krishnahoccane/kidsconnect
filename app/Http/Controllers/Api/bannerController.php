<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Void_;
use Storage;

class bannerController extends Controller
{
    public function index()
    {
        $banners = Banner::where('status', '1')->get();

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

    public function show(string $id)
    {
        $banner = Banner::find($id);

        if ($banner) {
            return response()->json([
                'status' => 200,
                'data' => $banner
            ], 200);
        } else {
            return response()->json([
                'status' => 403,
                'message' => "Requested Id Data NotFound"
            ], 403);
        }
    }

    public function update(Request $request, string $id)
    {
        $banner = Banner::find($id);


        if ($banner) {

            $banner->update([
                'status' => $request->status
            ]);

            return response()->json([
                'status' => 200,
                'message' => 'Banner Status is Updated successfully',
                'data' => $banner
            ], 200);

        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No data found'
            ], 404);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $banner = Banner::find($id);

        if (!$banner) {
            return response()->json([
                'status' => 403,
                'message' => "Requested Id Data NotFound"
            ], 403);
        }

        if ($banner->delete()) {
            return response()->json(['message' => 'Banner deleted successfully'], 200);
        } else {
            return response()->json([
                'status' => 500,
                'message' => "Failed to delete banner"
            ], 500);
        }

    }

    public function destroyall()
    {

        try {
            Banner::truncate();

            return response()->json([
                'status' => 200,
                'message' => "All Banners deleted successfully"
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => "An error occurred while deleting banners: " . $e->getMessage()
            ], 500);
        }
    }


}
