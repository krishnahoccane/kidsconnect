<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\subscriberlogins;
use App\Models\subscribersKidModel;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class subscribersKidsController extends Controller
{
    public function showKidParent($kidId)
    {
        // Find the kid by ID
        $kid = subscribersKidModel::find($kidId);

        // Check if the kid exists
        if (!$kid) {
            return response()->json([
                'status' => 404,
                'message' => 'Kid not found'
            ], 404);
        }

        // Fetch primary parent
        $primaryParent = subscriberlogins::where('id', $kid->MainSubscriberId)->first();

        // Fetch secondary parents
        $secondaryParents = subscriberlogins::where('MainSubscriberId', $kid->MainSubscriberId)
            ->where('id', '!=', $primaryParent->id)
            ->get();

        // Prepare response data
        $response = [
            'Kid' => $kid,
            'PrimaryParent' => $primaryParent,
            'SecondaryParents' => $secondaryParents
        ];

        return response()->json([
            'status' => 200,
            'data' => $response
        ], 200);
    }

    public function KidAlldata()
    {

        $Allkid = subscribersKidModel::all();

        if ($Allkid) {
            return response()->json([
                'status' => 200,
                'data' => $Allkid
            ], 200);
        } else {
            return response()->json([
                'status' => 403,
                'message' => "No Data Found"
            ], 403);
        }
    }
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

//     public function getKidsBySubscriberId($subscriberId)
// {
//     // Retrieve the main subscriber ID for the given subscriber ID
//     $subscriber = subscriberlogins::where('MainSubscriberId', $subscriberId)->first();
    
//     if (!$subscriber) {
//         return response()->json([
//             'status' => 404,
//             'message' => 'Subscriber not found'
//         ], 404);
//     }

//     $mainSubscriberId = $subscriber->MainSubscriberId;
    
//     // Retrieve kid data based on main subscriber ID
//     $kidMainSubId = subscribersKidModel::where('MainSubscriberId', $mainSubscriberId)->get();
    
//     if ($kidMainSubId->isEmpty()) {
//         return response()->json([
//             'status' => 404,
//             'message' => 'No kids found for the given subscriber ID'
//         ], 404);
//     }

//     return response()->json([
//         'status' => 200,
//         'data' => $kidMainSubId
//     ], 200);
// }
public function create(Request $request)
{
    // Check if the request has a profile image file
    if ($request->hasFile('ProfileImage')) {
        // Upload and save the profile image
        $profileImage = $request->file('ProfileImage');
        $path = 'uploads/profiles/';
        $fileName = time() . '_' . uniqid() . '.' . $profileImage->getClientOriginalExtension();
        $profileImage->move($path, $fileName);
        $profileImagePath = $path . $fileName;
    } else {
        // If no profile image is provided, set profileImagePath to null
        $profileImagePath = null;
    }

    // Hash the password
    $hashedPassword = bcrypt($request->Password);

    // Create a new subscriber kid model
    $subKid = subscribersKidModel::create([
        'Email' => $request->Email,
        'FirstName' => $request->FirstName,
        'LastName' => $request->LastName,
        'Dob' => $request->Dob,
        'Gender' => $request->Gender,
        'PhoneNumber' => $request->PhoneNumber,
        'Password' => $hashedPassword,
        'About' => $request->About,
        'Address' => $request->Address,
        'Keywords' => $request->Keywords,
        'LoginType' => $request->LoginType,
        'ProfileImage' => $profileImagePath,
        'MainSubscriberId' => $request->MainSubscriberId
    ]);

    return response()->json([
        'status' => 200,
        'message' => 'Kid\'s Profile Created Successfully',
        'data' => $subKid
    ], 200);
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
    $subKid = SubscribersKidModel::find($id);

    // Check if the subscriber kid exists
    if (!$subKid) {
        return response()->json([
            'status' => 404,
            'message' => 'Subscriber Kid Profile not found'
        ], 404);
    }

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
        $profileImagePath = $subKid->ProfileImage;
    }

    // Update the subscriber kid instance with the provided data including the profile image path
    $subKid->update([
        'FirstName' => $request->input('FirstName'),
        'LastName' => $request->input('LastName'),
        'Dob' => $request->input('Dob'),
        'Gender' => $request->input('Gender'),
        'About' => $request->input('About'),
        'Address' => $request->input('Address'),
        'City' => $request->input('City'),
        'State' => $request->input('State'),
        'Zipcode' => $request->input('Zipcode'),
        'Country' => $request->input('Country'),
        'ProfileImage' => $profileImagePath,
        'Keywords' => $request->input('Keywords'),
        'LoginType' => $request->input('LoginType'),
        'MainSubscriberId' => $request->input('MainSubscriberId'),
    ]);

    // Return the response based on whether the subscriber kid was successfully updated
    return response()->json([
        'status' => 200,
        'message' => 'Subscriber Kid Profile Updated Successfully',
        'data' => $subKid
    ], 200);
}
}
