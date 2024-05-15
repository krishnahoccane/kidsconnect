<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\defaultStatus;
use Illuminate\Validation\Rule;
use App\Models\subscriberlogins;
use App\Models\subscribersModel;
use App\Models\petModel;
use App\Http\Controllers\Controller;
use App\Models\subscribersKidModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class subscriberLoginController extends Controller
{
    //
    public function index()
    {

        $user = Auth::guard('api')->user();

        // print_r($user);
        if ($user) {
            return response()->json([
                'status' => 200,
                'data' => $user
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => "Subscriber not found"
            ], 404);
        }
    }


    public function create(Request $request)
    {

        $id = $request->id;
        $Email = $request->Email;
        $DeviceId = $request->DeviceId;
        $hashPassword = Hash::make($request->Password);
        $EntryCodeId = $request->EntryCode;
        $sub_login = subscriberlogins::firstOrCreate([

            'Email' => $Email,

            'Password' => $hashPassword,

            'EntryCode' => $EntryCodeId,

            'DeviceId' => $DeviceId

        ]);

        if ($sub_login->wasRecentlyCreated) {
            $EntryId = $sub_login->id;
            return redirect()->route('verifyAndCreate', ['entryId' => $EntryId]);

        } else {
            return response()->json([
                'status' => 409,
                'message' => 'Subscriber already exists'
            ], 409);
        }
    }
    public function update(Request $request, $id)
    {
        // Find the subscriber by ID
        $subscriber = SubscriberLogins::find($id);
    
        // If the subscriber with the given ID exists
        if ($subscriber) {
            // Check if the request has a profile image file
            // if ($request->hasFile('ProfileImage')) {
            //     // Upload and save the profile image
            //     $profileImage = $request->file('ProfileImage');
            //     $path = 'uploads/profiles/';
            //     $fileName = time() . '_' . uniqid() . '.' . $profileImage->getClientOriginalExtension();
            //     $profileImage->move($path, $fileName);
            //     $profileImagePath = $path . $fileName;
            // } else {
            //     // If no profile image is provided, keep the existing profile image path
            //     $profileImagePath = null;
            // }
    
        // Update the subscriber's profile fields with the new values
        $subscriber->update([
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
            // 'ProfileImage' => $profileImagePath,
            'Keywords' => $request->input('Keywords'),
            'LoginType' =>"2",
            'RoleId' => $request->input('RoleId'),
            'MainSubscriberId' => $subscriber->id,
        ]);

        // Return a success response
        return response()->json([
            'status' => 200,
            'message' => 'Profile updated successfully',
            'data' => $subscriber,
        ], 200);
    } else {
        // Return an error response if the subscriber with the given ID was not found
        return response()->json([
            'status' => 404,
            'message' => 'Subscriber not found'
        ], 404);
    }
}


    //Created Accounts by Main Subscriber
    public function maincreatedaccount($subscriberId = Null)
    {
        if ($subscriberId) {
            $subscriber = subscriberlogins::find($subscriberId);
            $subscriberLoginData = subscriberlogins::where('IsMain', 0)->where('MainSubscriberId', $subscriberId)->get();

            if ($subscriberLoginData->isEmpty()) {
                return response()->json([
                    'status' => 403,
                    'message' => $subscriber['FirstName'] . ' Yet to add family profiles'
                ], 404);
            } else {
                return response()->json([
                    'status' => 200,
                    'data' => $subscriberLoginData
                ], 200);
            }
        } else {
            // Handle case where $subscriberId is not provided
            // For example, return an error response indicating missing parameter
            $subscriberLoginData = subscriberlogins::where('IsMain', 0)->get();

            if ($subscriberLoginData->isEmpty()) {
                return response()->json([
                    'status' => 404,
                    'message' => 'No Data Found'
                ], 404);
            } else {
                return response()->json([
                    'status' => 200,
                    'data' => $subscriberLoginData
                ], 200);
            }
        }
    }



    public function createAccounts(Request $request, int $id)
    {
        // Find the subscriber data with ID
        $subscriber = subscribersModel::find($id);

        if (!$subscriber) {
            return response()->json([
                'status' => 404,
                'message' => "Subscriber not found",
            ], 404);
        }

        // Find the subscriberLogin data based on the subscriber's ID - here we are comparing the MainsubscriberID
        $subscriberLoginData = subscriberlogins::where('MainSubscriberId', $subscriber->id)->first();

        $keywords = $request->has('Keywords') ? $request->input('Keywords') : [];

        $serializedKeywords = json_encode($keywords);

        $hashPassword = Hash::make($request->Password);

        if ($request->hasFile('ProfileImage')) {
            $profileImage = $request->file('ProfileImage');
            $path = 'uploads/profiles/';
            $fileName = time() . '_' . uniqid() . '.' . $profileImage->getClientOriginalExtension();
            $profileImage->move($path, $fileName);
            $profileImagePath = $path . $fileName;
        } else {
            $profileImagePath = null;
        }

        // Check the RoleId
        if ($request->RoleId == 5) {
            // Create a new account in subscribersKidModel
            $sub_kidNewAccount = subscribersKidModel::create([
                'FirstName' => $request->FirstName,
                'LastName' => $request->LastName,
                'Email' => $request->Email,
                'Dob' => $request->Dob,
                'Gender' => $request->Gender,
                'PhoneNumber' => $request->PhoneNumber,
                'SSN' => $request->SSN,
                'Password' => $hashPassword,
                'About' => $request->About,
                'Address' => $request->Address,
                'ProfileImage' => $profileImagePath,
                'SSNimage' => $request->SSNimage,
                'Keywords' => $serializedKeywords,
                'LoginType' => $request->LoginType,
                'RoleId' => $request->RoleId,
                'MainSubscriberId' => $subscriber->id,
            ]);

            // Check if account was created successfully
            if ($sub_kidNewAccount) {
                return response()->json([
                    'status' => 200,
                    'message' => "A New Kid Account is created by " . $subscriber->Email,
                    'data' => $sub_kidNewAccount,
                ], 200);
            } else {
                return response()->json([
                    'status' => 403,
                    'message' => 'Kid Account not created'
                ], 403);
            }
        } elseif ($request->RoleId == 7) {
            // Create a new account in pet table
            $petNewAccount = petModel::create([
                'MainSubscriberId' => $subscriber->id,
                'RoleId' => $request->RoleId,
                'Name' => $request->Name,
                'gender' => $request->gender,
                'Breed' => $request->Breed,
                'Dob' => $request->Dob,
                'Description' => $request->Description,
                'ProfileImage' => $profileImagePath,
                // Add other columns as needed
            ]);

            // Check if account was created successfully
            if ($petNewAccount) {
                return response()->json([
                    'status' => 200,
                    'message' => "A New Pet Account is created by " . $subscriber->Email,
                    'data' => $petNewAccount,
                ], 200);
            } else {
                return response()->json([
                    'status' => 403,
                    'message' => 'Pet Account not created'
                ], 403);
            }
        } else {
            // Create a new account in subscriberlogins table
            $subscriberloginNewAccount = subscriberlogins::create([
                'FirstName' => $request->FirstName,
                'LastName' => $request->LastName,
                'Email' => $request->Email,
                'Dob' => $request->Dob,
                'Gender' => $request->Gender,
                'PhoneNumber' => $request->PhoneNumber,
                'SSN' => $request->SSN,
                'Password' => $hashPassword,
                'Address' => $request->Address,
                'ProfileImage' => $profileImagePath,
                'SSNimage' => $request->SSNimage,
                'LoginType' => $request->LoginType,
                'RoleId' => $request->RoleId,
                'MainSubscriberId' => $subscriber->id,
            ]);

            // Check if account was created successfully
            if ($subscriberloginNewAccount) {
                return response()->json([
                    'status' => 200,
                    'message' => "A New Subscriber Login Account is created by " . $subscriber->Email,
                    'data' => $subscriberloginNewAccount,
                ], 200);
            } else {
                return response()->json([
                    'status' => 403,
                    'message' => 'Subscriber Login Account not created'
                ], 403);
            }
        }
    }



    public function show($id)
    {

        $sub_login = subscriberlogins::find($id);

        if ($sub_login) {
            return response()->json([
                'status' => 200,
                'data' => $sub_login
            ], 200);
        } else {
            return response()->json([
                'status' => 403,
                'message' => "Requested Id Data NotFound"
            ], 403);
        }

    }
    // public function update(Request $request, int $id)
    // {
    //     $role = subscriberlogins::find($id);

    //     if ($request->hasFile('ProfileImage')) {
    //         $profileImage = $request->file('ProfileImage');
    //         $path = 'uploads/profiles/';
    //         $fileName = time() . '_' . uniqid() . '.' . $profileImage->getClientOriginalExtension();
    //         $profileImage->move($path, $fileName);
    //         $profileImagePath = $path . $fileName;
    //     } else {
    //         $profileImagePath = null;
    //     }


    //     if ($role) {
    //         $updateData = [
    //             'FirstName' => $request->FirstName,
    //             'LastName' => $request->LastName,
    //             'email' => $request->email,
    //             'Dob' => $request->Dob,
    //             'Gender' => $request->Gender,
    //             'PhoneNumber' => $request->PhoneNumber,
    //             'SSN' => $request->SSN,
    //             'About' => $request->About,
    //             'Address' => $request->Address,
    //             'ProfileImage' => $profileImagePath,
    //             'SSNimage' => $request->SSNimage,
    //             'Keywords' => $request->Keywords,
    //             'LoginType' => $request->LoginType,
    //             'IsMain' => $request->IsMain,
    //             'RoleId' => $request->RoleId,
    //             'MainSubscriberId' => $request->MainSubscriberId
    //         ];

    //         // Check if the password field is present in the request
    //         if ($request->has('Password')) {
    //             $hashedPassword = Hash::make($request->Password);
    //             $updateData['Password'] = $hashedPassword;
    //         }

    //         $role->update($updateData);

    //         return response()->json([
    //             'status' => 200,
    //             'message' => 'Subscriber is Updated successfully'
    //         ], 200);
    //     }
    // }

    public function delete(Request $request, int $id)
    {

        $sub_login = subscriberlogins::find($id);

        if (!$sub_login) {
            return response()->json([
                'status' => 403,
                'message' => "Requested Id Data NotFound"
            ], 403);
        }

        $sub_login->delete();

        return response()->json(['message' => 'Subscriber deleted successfully'], 200);

    }

    public function updateAccount(Request $request, int $id)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'FirstName' => 'string',
            'LastName' => 'string',
            'Email' => ['email', Rule::unique('subscriberlogins')->ignore($id)],
            'Dob' => 'date',
            'Gender' => 'numeric',
            'PhoneNumber' => 'numeric',
            'SSN' => ['string', Rule::unique('subscriberlogins')->ignore($id)],
            'Password' => 'string',
            'About' => 'string',
            'Address' => 'string',
            'ProfileImage' => 'string',
            'SSNimage' => 'string',
            'Keywords' => 'string',
            'LoginType' => 'numeric',
            'IsMain' => 'numeric',
            'RoleId' => 'numeric',
            'MainSubscriberId' => 'numeric'
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => $validator->errors()
            ], 422);
        }

        // Find the subscriber login account by ID
        $subscriberLogin = subscriberlogins::find($id);

        // Check if the subscriber login account exists
        if (!$subscriberLogin) {
            return response()->json([
                'status' => 404,
                'message' => 'Subscriber login account not found'
            ], 404);
        }

        // Update the subscriber login account with the provided data
        $subscriberLogin->update([
            'FirstName' => $request->input('FirstName', $subscriberLogin->FirstName),
            'LastName' => $request->input('LastName', $subscriberLogin->LastName),
            'Email' => $request->input('Email', $subscriberLogin->Email),
            'Dob' => $request->input('Dob', $subscriberLogin->Dob),
            'Gender' => $request->input('Gender', $subscriberLogin->Gender),
            'PhoneNumber' => $request->input('PhoneNumber', $subscriberLogin->PhoneNumber),
            'SSN' => $request->input('SSN', $subscriberLogin->SSN),
            'Password' => $request->input('Password', $subscriberLogin->Password),
            'About' => $request->input('About', $subscriberLogin->About),
            'Address' => $request->input('Address', $subscriberLogin->Address),
            'ProfileImage' => $request->input('ProfileImage', $subscriberLogin->ProfileImage),
            'SSNimage' => $request->input('SSNimage', $subscriberLogin->SSNimage),
            'Keywords' => $request->input('Keywords', $subscriberLogin->Keywords),
            'LoginType' => $request->input('LoginType', $subscriberLogin->LoginType),
            'IsMain' => $request->input('IsMain', $subscriberLogin->IsMain),
            'RoleId' => $request->input('RoleId', $subscriberLogin->RoleId),
            'MainSubscriberId' => $request->input('MainSubscriberId', $subscriberLogin->MainSubscriberId),
        ]);

        // Return the response
        return response()->json([
            'status' => 200,
            'message' => 'Subscriber login account updated successfully',
            'data' => $subscriberLogin
        ], 200);
    }

    public function mainSecondary($id = null)
    {

        $sub_login = subscriberlogins::where('MainSubscriberId', $id)->get();

        if ($sub_login) {
            return response()->json([
                'status' => 200,
                'data' => $sub_login
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No Data Found'
            ], 404);
        }

    }



}
