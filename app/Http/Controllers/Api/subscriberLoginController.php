<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\defaultStatus;
use Illuminate\Validation\Rule;
use App\Models\subscriberlogins;
use App\Models\subscribersModel;
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
        $subscibers = subscribersModel::firstOrCreate([
            'RoleId' => $request->RoleId,
            'Email' => $request->Email
        ]);

        $subscriberId = $subscibers->id;
        $hashPassword = Hash::make($request->Password);
        $sub_login = subscriberlogins::firstOrCreate([
            'FirstName' => $request->FirstName,
            'LastName' => $request->LastName,
            'Email' => $request->Email,
            'Dob' => $request->Dob,
            'Gender' => $request->Gender,
            'PhoneNumber' => $request->PhoneNumber,
            'SSN' => $request->SSN,
            'Password' => $hashPassword,
            // 'About' => $request->About,
            'Address' => $request->Address,
            'ProfileImage' => $request->ProfileImage,
            'SSNimage' => $request->SSNimage,
            // 'Keywords' => $request->Keywords,
            'LoginType' => $request->LoginType,
            'IsMain' => 1,
            'RoleId' => $request->RoleId,
            'MainSubscriberId' => $subscriberId,
        ]);

        if ($sub_login->wasRecentlyCreated) {
            return response()->json([
                'status' => 200,
                'message' => ' Subscriber Registered successfully and mainsubscriber id is' . $subscriberId,
                'data' => $sub_login
            ], 200);
        } else {
            return response()->json([
                'status' => 409,
                'message' => 'Subscriber already exists'
            ], 409);
        }
    }


    public function showcreateAccounts()
    {
        $subscriberLoginData = subscriberlogins::where('IsMain', 0)->get();

        if ($subscriberLoginData) {
            return response()->json([
                'status' => 200,
                'data' => $subscriberLoginData
            ], 200);
        } else {
            return response()->json([
                'status' => 403,
                'message' => 'No Data Found'
            ], 403);
        }
    }


    public function showcreateAccount($subscriberId)
    {
        // Find the subscriber
        $subscriber = subscribersModel::find($subscriberId);

        // Check if the subscriber exists
        if (!$subscriber) {
            return response()->json([
                'status' => 404,
                'message' => "Subscriber not found",
            ], 404);
        }

        // Execute the SQL query using raw expressions
        $familyMembers = DB::select("
        SELECT id, FirstName,LastName,RoleId,ProfileImage
        FROM subscriber_logins
        WHERE IsMain = 0 AND MainSubscriberId = $subscriberId
        UNION
        SELECT id, FirstName,LastName,RoleId,ProfileImage
        FROM subscribers_kids
        WHERE MainSubscriberId = $subscriberId
    ");

        // Check if family members exist
        if (empty($familyMembers)) {
            return response()->json([
                'status' => 403,
                'message' => 'No family members found for the subscriber',
            ], 403);
        }

        return response()->json([
            'status' => 200,
            'data' => $familyMembers,
        ], 200);
    }


    public function createAccounts(Request $request, int $id)
    {
        // Find the subscriber data with ID
        $subscriber = subscribersModel::find($id);

        // Check if the subscriber exists or not
        if (!$subscriber) {
            return response()->json([
                'status' => 404,
                'message' => "Subscriber not found",
            ], 404);
        }

        // Find the subscriberLogin data based on the subscriber's ID - here we are comparing the MainsubscriberID
        $subscriberLoginData = subscriberlogins::where('MainSubscriberId', $subscriber->id)->first();
        if ($request->hasFile('ProfileImage')) {
            // Get the uploaded file
            $profileImage = $request->file('ProfileImage');

            // Define the storage path
            $path = 'uploads/profiles/';

            // Generate a unique file name
            $fileName = time() . '_' . uniqid() . '.' . $profileImage->getClientOriginalExtension();

            // Move the uploaded file to the storage path
            $profileImage->move($path, $fileName);

            // Set the profile image path
            $profileImagePath = $path . $fileName;
        } else {
            // If no file is uploaded, set the profile image path to null or any default value
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
                'Password' => $request->Password,
                'About' => $request->About,
                'Address' => $request->Address,
                'ProfileImage' => $profileImagePath,
                'SSNimage' => $request->SSNimage,
                'Keywords' => $request->Keywords,
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
        } else if (in_array($request->RoleId, [2, 3, 4])) {
            // Create a new account in subscriberlogins table
            $subscriberloginNewAccount = subscriberlogins::create([
                'FirstName' => $request->FirstName,
                'LastName' => $request->LastName,
                'Email' => $request->Email,
                'Dob' => $request->Dob,
                'Gender' => $request->Gender,
                'PhoneNumber' => $request->PhoneNumber,
                'SSN' => $request->SSN,
                'Password' => $request->Password,
                // 'About' => $request->About,
                'Address' => $request->Address,
                'ProfileImage' => $profileImagePath,
                'SSNimage' => $request->SSNimage,
                // 'Keywords' => $request->Keywords,
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
        } else {
            return response()->json([
                'status' => 403,
                'message' => 'Invalid RoleId'
            ], 403);
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




    public function update(Request $request, int $id)
    {

        $validate = Validator::make($request->all(), [

            'FirstName' => ['string'],
            'LastName' => ['string'],
            'email' => ['email', Rule::unique('subscriber_logins')->ignore($id)],
            'Dob' => ['date'],
            'Gender' => ['numeric'],
            'PhoneNumber' => ['numeric'],
            'SSN' => ['string', Rule::unique('subscriber_logins')->ignore($id)],
            'Password' => ['string'],
            'About' => ['string'],
            'Address' => ['string'],
            'ProfileImage' => ['string'],
            'SSNimage' => ['string'],
            'Keywords' => ['string'],
            'LoginType' => ['numeric'],
            'IsMain' => ['numeric'],
            'RoleId' => ['numeric'],
            'MainSubscriberId' => ['numeric']
        ]);

        if ($validate->fails()) {

            return response()->json([
                'status' => 422,
                'message' => $validate->messages()
            ], 422);

        } else {

            $role = subscriberlogins::find($id);

            if ($role) {

                $role->update([
                    'FirstName' => $request->FirstName,
                    'LastName' => $request->LastName,
                    'email' => $request->email,
                    'Dob' => $request->Dob,
                    'Gender' => $request->Gender,
                    'PhoneNumber' => $request->PhoneNumber,
                    'SSN' => $request->SSN,
                    'Password' => $request->Password,
                    'About' => $request->About,
                    'Address' => $request->Address,
                    'ProfileImage' => $request->ProfileImage,
                    'SSNimage' => $request->SSNimage,
                    'Keywords' => $request->Keywords,
                    'LoginType' => $request->LoginType,
                    'IsMain' => $request->IsMain,
                    'RoleId' => $request->RoleId,
                    'MainSubscriberId' => $request->MainSubscriberId

                ]);

                return response()->json([
                    'status' => 200,
                    'message' => 'Subscriber is Updated successfully'
                ], 200);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => 'No Subscriber found'
                ], 404);
            }
        }

    }

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




}
