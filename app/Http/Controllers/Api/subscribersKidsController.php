<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\subscriberlogins;
use App\Models\subscribersKidModel;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class subscribersKidsController extends Controller
{
    //
    public function index($id = null)
    {
        if ($id !== null) {
            try {
                $subKids = subscribersKidModel::findOrFail($id);
            } catch (ModelNotFoundException $e) {
                return response()->json([
                    'status' => 404,
                    'message' => "Given Id is not available"
                ], 404);
            }

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

    public function getKidsBySubscriberId($subscriberId)
    {
        // Retrieve kid data based on subscriber ID
        $kidMainSubId = subscribersKidModel::where('MainSubscriberId', $subscriberId)->get();
        if ($kidMainSubId->isEmpty()) {
            return response()->json([
                'status' => 404,
                'message' => 'No kids found for the given subscriber ID'
            ], 404);
        }

        return response()->json([
            'status' => 200,
            'data' => $kidMainSubId
        ], 200);
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
            'MainSubscriberId' => $request->MainSubscriberId
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

    public function show($id)
    {
        // Find the subscriber kid by its ID
        $subKid = subscribersKidModel::find($id);

        // Check if the subscriber kid exists
        if (!$subKid) {
            return response()->json([
                'status' => 404,
                'message' => 'Subscriber Kid Profile not found'
            ], 404);
        }

        // Return the response with the subscriber kid details
        return response()->json([
            'status' => 200,
            'data' => $subKid
        ], 200);
    }
    //update the kids profile by id

    public function update(Request $request, $id)
 {
    // Find the subscriber kid by its ID
    $subKid = subscribersKidModel::find($id);

        // Check if the subscriber kid exists
        if (!$subKid) {
            return response()->json([
                'status' => 404,
                'message' => 'Subscriber Kid Profile not found'
            ], 404);
        }

        // Update the subscriber kid instance with the provided data
        $subKid->update([
            'FirstName' => $request->input('FirstName', $subKid->FirstName),
            'LastName' => $request->input('LastName', $subKid->LastName),
            'Email' => $request->input('Email', $subKid->Email),
            'Dob' => $request->input('Dob', $subKid->Dob),
            'Gender' => $request->input('Gender', $subKid->Gender),
            'PhoneNumber' => $request->input('PhoneNumber', $subKid->PhoneNumber),
            'SSN' => $request->input('SSN', $subKid->SSN),
            'Password' => $request->input('Password', $subKid->Password),
            'About' => $request->input('About', $subKid->About),
            'Address' => $request->input('Address', $subKid->Address),
            'ProfileImage' => $request->input('ProfileImage', $subKid->ProfileImage),
            'Keywords' => $request->input('Keywords', $subKid->Keywords),
            'LoginType' => $request->input('LoginType', $subKid->LoginType),
            'MainSubscriberId' => $request->input('MainSubscriberId', $subKid->MainSubscriberId),
        ]);

        // Return the response based on whether the subscriber kid was successfully updated
        if ($subKid) {
            return response()->json([
                'status' => 200,
                'message' => 'Subscriber Kid Profile Updated Successfully',
                'data' => $subKid
            ], 200);
        } else {
            return response()->json([
                'status' => 500,
                'message' => 'Failed to update Subscriber Kid Profile'
            ], 500);
        }
    }



}
