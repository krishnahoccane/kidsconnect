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
            $subKids = subscribersKidModel::all();

            if ($subKids->isEmpty()) {
                return response()->json([
                    'status' => 404,
                    'message' => 'No Data Found'
                ], 404);
            } else {
                return response()->json([
                    'status' => 200,
                    'data' => $subKids
                ], 200);
            }
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

        if ($subKid) {
            // Check if the request has a profile image file
            if ($request->hasFile('ProfileImage')) {
                // Upload and save the profile image
                $profileImage = $request->file('ProfileImage');
                $path = 'uploads/profiles/';
                $fileName = time() . '_' . uniqid() . '.' . $profileImage->getClientOriginalExtension();
                $profileImage->move($path, $fileName);
                $profileImagePath = $path . $fileName;
            } else {
                // If no profile image is provided, keep the existing profile image path
                $profileImagePath = null;
            }
    
        // Update the subscriber's profile fields with the new values
       
        // Update the subscriber kid instance with the provided data
        $subKid->update([
            'FirstName' => $request->input('FirstName'),
            'LastName' => $request->input('LastName'),
            'Dob' => $request->input('Dob'),
            'Gender' => $request->input('Gender'),
            'About' => $request->input('About'),
            'Address' => $request->input('Address'),
            'ProfileImage' => $profileImagePath,
            'Keywords' => $request->input('Keywords'),
            'LoginType' => $request->input('LoginType'),
            'MainSubscriberId' => $request->input('MainSubscriberId'),
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
}
