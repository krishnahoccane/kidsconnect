<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\subscriberlogins;
use Illuminate\Http\Request;


class AddSecondary extends Controller
{
    public function index()
    {
        $subscribers = subscriberlogins::all();

        return response()->json([
            'status' => 200,
            'data' => $subscribers
        ], 200);
    }

    public function addsecondary(Request $request, $primaryId)
    {
        $checkPrimaryIdExist = subscriberlogins::find($primaryId);

        if ($checkPrimaryIdExist) {
            if ($request->hasFile('ProfileImage')) {
                // Upload and save the profile image
                $profileImage = $request->file('ProfileImage');
                $path = 'uploads/profiles/';
                $fileName = time() . '_' . uniqid() . '.' . $profileImage->getClientOriginalExtension();
                $profileImage->move($path, $fileName);
                $profileImagePath = $path . $fileName;
            } else {
                // If no profile image is provided, keep the existing profile image path
                $profileImagePath = $checkPrimaryIdExist->ProfileImage;
            }
            $primaryIdEmail = $checkPrimaryIdExist->Email;
            $primaryIdMain = $checkPrimaryIdExist->id;


            if ($primaryIdEmail) {
                $secondaryCreate = subscriberlogins::create([
                    'MainSubscriberId' => $primaryIdMain,
                    'DeviceId' => $request->input('DeviceId'),
                    'FirstName' => $request->input('FirstName'),
                    'LastName' => $request->input('LastName'),
                    'BirthYear' => $request->input('BirthYear'),
                    'Gender' => $request->input('Gender'),
                    'PhoneNumber' => $request->input('PhoneNumber'),
                    'About' => $request->input('About'),
                    'Address' => $request->input('Address'),
                    'City' => $request->input('City'),
                    'State' => $request->input('State'),
                    'Zipcode' => $request->input('Zipcode'),
                    'Country' => $request->input('Country'),
                    'ProfileImage' => $profileImagePath,
                    'Keywords' => $request->input('Keywords'),
                    'LoginType' => $request->input('LoginType'),
                    'RoleId' => $request->input('RoleId')
                ]);

                if ($secondaryCreate) {
                    return response()->json([
                        'status' => 200,
                        'message' => 'Secondary Profile Created Successfully',
                        'data' => $secondaryCreate
                    ], 200);
                } else {
                    return response()->json([
                        'status' => 404,
                        'message' => 'Secondary Profile Not Created'
                    ], 404);
                }
            }
            // 
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'Email Id Not Registered'
            ], 404);
        }



    }
}
