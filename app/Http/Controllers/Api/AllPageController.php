<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\pages;



class AllPageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $page = pages::all();

        if($page->count() > 0){
            return response()->json([
                'status'=>200,
                'data'=>$page
            ],200);
        }else{
            return response()->json([
                'status'=>403,
                'message'=>'No Data Found'
            ],403);
        }
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $validator=validator::make($request->all(),[
        //     'Pagetitle'=>'required',
        //     'Pagecontent'=>'required'
        // ]);
        
    //   if ($validator->fails()) {
    //         return response()->json([
    //             'status' => 403,
    //             'message' => 'Validation error occurred',
    //             'errors' => $validator->errors()->all()
    //         ], 403);
    //     } 
          
            $pages = pages::firstOrCreate([
                
                'Pagetitle' => $request->Pagetitle,
                'Pagecontent'=> $request->PageContent
            ]);

            if ($pages->wasRecentlyCreated) {
                return response()->json([
                    'status' => 200,
                    'message' => 'About Inserted successfully',
                    'data' => $pages
                ], 200);
            } else {
                return response()->json([
                    'status' => 409,
                    'message' => 'About already exists'
                ], 409);
            }
        
    
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $page = pages::find($id);

        if ($page) {
            return response()->json([
                'status' => 200,
                'data' => $page
            ], 200);
        } else {
            return response()->json([
                'status' => 403,
                'message' => "Requested Id Data NotFound"
            ], 403);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
            $page_content = pages::find($id);
            

            if ($page_content) {

                $page_content->update([
                    'id' => $request->aboutId,
                    'Pagecontent'=> $request->Pagecontent
                ]);

                return response()->json([
                    'status' => 200,
                    'message' => 'About is Updated successfully',
                    'data' => $page_content
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
        $page = pages::find($id);

        if (!$page) {
            return response()->json([
                'status' => 403,
                'message' => "Requested Id Data NotFound"
            ], 403);
        } 

        $page->delete();

        return response()->json(['message' => 'About deleted successfully'],200);

    }
    
}
