<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\subscribersKidModel;
use Illuminate\Http\Request;

class subscribersKidsController extends Controller
{
    //
    public function index(){
        
        $subKids = subscribersKidModel::all();

        if ($subKids->count() > 0) {
            return response()->json([
                'status' => 200,
                'data' => $subKids
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No Data Found'
            ], 404);
        }
    }

    public function create(Request $request)
    {

        $subKids = subscribersKidModel::all();

        $subKids = subscribersKidModel::firstOrCreate([
            'FirstName' => $request->FirstName,
            'LastName' => $request->LastName,
            'Email' => $request->Email,
            'Dob' => $request->Dob,
            'Gender' => $request->Gender,
            'PhoneNumber' => $request->PhoneNumber,
            'SSN' => $request->SSN,
            'Password' => $request->Password,
            'About' => $request->About,
            'Address' => $request->Address,
            'ProfileImage' => $request->ProfileImage,
            'Keywords' => $request->Keywords,
            'LoginType' => $request->LoginType,
        ]);

        if ($subKids->wasRecentlyCreated) {
            return response()->json([
                'status' => 200,
                'message' => 'Kids Profile Created Successfully'
            ], 200);
        } else {
            return response()->json([
                'status' => 403,
                'message' => 'Kids Profile Already Exists'
            ], 403);
        }

    }
}
