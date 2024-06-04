<?php

namespace App\Http\Controllers\Api;

use App\Models\RegCodes;
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

        $sub_login = subscriberlogins::all();

        if ($sub_login) {
            return response()->json([
                'status' => 200,
                'data' => $sub_login
            ], 200);
        } else {
            return response()->json([
                'status' => 403,
                'message' => "No Data Found"
            ], 403);
        }
    }


    public function create(Request $request)
    {
        $email = $request->Email;
        $entryCodeId = $request->EntryCode;
        $phoneNumber = $request->phoneNumber;

        // If both email and phone number are missing, return an error response.
        // if (empty($email) && empty($phoneNumber)) {
        //     return response()->json([
        //         'status' => 400,
        //         'message' => 'Email or phone number is required.',
        //     ], 400);
        // }

        if (!empty($email)) {
            // Check if a record with the given email exists.
            $emailExist = subscriberlogins::where('Email', $email)->first();
            if ($emailExist) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Email already exists.',
                    'data' => $emailExist
                ], 200);
            } else {
                $ref_inv_by = RegCodes::where('code_number', $entryCodeId)->select('id', 'user_id')->first();
                $subscriber = new subscriberlogins();
                $subscriber->Email = $email;
                $subscriber->Ref_Inv_By = $ref_inv_by ? $ref_inv_by->id : null;
                $subscriber->phoneNumber = null;
                $subscriber->MainsubscriberId = $ref_inv_by ? $ref_inv_by->user_id: null;
                // Add other necessary fields here from $request if needed
                $subscriber->save();

                return response()->json([
                    'status' => 201,
                    'message' => 'Subscriber created successfully with email.',
                    'data' => $subscriber
                ], 201);
            }
        }

        if (!empty($phoneNumber)) {
            // Check if a record with the given phone number exists.
            $phoneNumberExist = subscriberlogins::where('phoneNumber', $phoneNumber)->first();
            if ($phoneNumberExist) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Phone number already exists.',
                    'data' => $phoneNumberExist,
                ], 200);
            } else {
                // Create subscriber record with phone number.
                $ref_inv_by = RegCodes::where('code_number', $entryCodeId)->select('id', 'user_id')->first();
                $subscriber = new subscriberlogins();
                $subscriber->Email = null;
                $subscriber->Ref_Inv_By = $ref_inv_by ? $ref_inv_by->id : null;
                $subscriber->MainsubscriberId = $ref_inv_by ? $ref_inv_by->user_id: null;
                $subscriber->phoneNumber = $phoneNumber;
                // Add other necessary fields here from $request if needed
                $subscriber->save();

                return response()->json([
                    'status' => 201,
                    'message' => 'Subscriber created successfully with phone number.',
                    'data' => $subscriber
                ], 201);
            }
        }

        // Fallback response if something unexpected happens
        return response()->json([
            'status' => 500,
            'message' => 'An unexpected error occurred.',
        ], 500);
    }





    public function createSubscriberData(Request $request, $email, $entryCodeId, $phoneNumber)
    {
        $subscriberData = [
            'EntryCode' => $entryCodeId,
        ];

        // Check whether to store email or phone number
        if ($email) {
            $subscriberData['Email'] = $email;
        }
        if ($phoneNumber) {
            $subscriberData['PhoneNumber'] = $phoneNumber;
        }

        // Create subscriber record
        $subscriber = subscriberlogins::create($subscriberData);

        // Check if subscriber was created successfully
        if ($subscriber) {
            return response()->json([
                'status' => 200,
                'message' => 'Profile created successfully',
                'data' => $subscriber,
            ], 200);
        } else {
            return response()->json([
                'status' => 500,
                'message' => 'Failed to create profile',
            ], 500);
        }
    }



    public function update(Request $request, $id)
    {
        // Find the subscriber by ID
        $subscriber = SubscriberLogins::find($id);
        $password = password_hash('Glansa@2024', PASSWORD_BCRYPT);
        // If the subscriber with the given ID exists
        if ($subscriber) {
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
                $profileImagePath = $subscriber->ProfileImage;
            }

            // Update the subscriber's profile fields with the new values
            $subscriber->update([
                // 'DeviceId' => $request->input('DeviceId'),
                'FirstName' => $request->input('FirstName'),
                'LastName' => $request->input('LastName'),
                'BirthYear' => $request->input('BirthYear'),
                'Gender' => $request->input('Gender'),
                'PhoneNumber' => $request->input('PhoneNumber'),
                'Email' => $request->input('Email'),
                'password' => $password,
                'About' => $request->input('About'),
                'Address' => $request->input('Address'),
                'City' => $request->input('City'),
                'State' => $request->input('State'),
                'Zipcode' => $request->input('Zipcode'),
                'Country' => $request->input('Country'),
                'ProfileImage' => $profileImagePath,
                'Keywords' => $request->input('Keywords'),
                'LoginType' => "2",
                'RoleId' => $request->input('RoleId'),
                'MainSubscriberId' => $subscriber->id,
            ]);
            // Return a success response

            if ($subscriber) {


                $entryRefType = 1;
                $Refcode = $this->generateUniqueCode();
                $entryInvType = 2;
                $Invcode = $this->generateUniqueCode();
                $RegfcodeEntry = RegCodes::firstOrCreate([
                    'code_type_id' => $entryRefType,
                    'code_number' => $Refcode,
                    'user_id' => $id
                ]);
                if ($RegfcodeEntry) {
                    $InvcodeEntry = RegCodes::firstOrCreate([
                        'code_type_id' => $entryInvType,
                        'code_number' => $Invcode,
                        'user_id' => $id
                    ]);

                    // return response()->json([
                    //     'status' => 200,
                    //     'message' => 'Thank you for registration',
                    //     'data' => $subEntryData
                    // ]);
                }



                return response()->json([
                    'status' => 200,
                    'message' => 'Profile updated successfully',
                    'data' => $subscriber,
                ], 200);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => 'Profile update unsuccessfull.. Something went wrong'
                ], 404);
            }

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
        // Find the subscriber login by ID
        $sub_login = subscriberlogins::find($id);

        // Check if subscriber login was found
        if ($sub_login) {
            $fetchingEntryId = $sub_login->id;
            $mainsubscriberfromref = $sub_login->MainSubscriberId;
            $subscriberDetails = $this->getMainSubscriber($mainsubscriberfromref);
            $regCodes = RegCodes::where('user_id', $fetchingEntryId)->get();

            // Check if RegCodes entries were found
            if ($regCodes->isNotEmpty()) {
                // Iterate through each regCode
                foreach ($regCodes as $regCode) {
                    $checkingCodeType = $regCode->code_type_id;

                    if ($checkingCodeType == 2) {
                        $mainId = $regCode->id;
                        $mainSubscriberId = $regCode->user_id;
                        $userDetails = subscriberlogins::where('Ref_Inv_By', $mainId)->get();
                        $getMainSubscriber = subscriberlogins::where('MainSubscriberId', $mainSubscriberId)->get();
                        if ($userDetails) {
                            return response()->json([
                                'status' => 200,
                                'loginUserData' => $sub_login,
                                'SecondaryData' => $userDetails,
                                'MainSubsciberData' => $subscriberDetails
                            ], 200);
                        } else {
                            return response()->json([
                                'status' => 404,
                                'message' => "User details not found."
                            ], 404);
                        }
                    }
                }

                // If no regCode with code_type_id 2 was found
                return response()->json([
                    'status' => 202,
                    'message' => "No Secondary Data Available"
                ], 202);
            } else {
                // No RegCodes entries found
                return response()->json([
                    'status' => 404,
                    'message' => "RegCode not found."
                ], 404);
            }
        } else {
            // No subscriber login entry found
            return response()->json([
                'status' => 404,
                'message' => "Requested ID data not found."
            ], 404);
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




    private function generateUniqueCode()
    {
        // Generate a random 4-digit code
        $code = str_pad(mt_rand(10000, 99999), 5, '0', STR_PAD_LEFT);


        // Check if the code already exists in the database
        $existingCode = RegCodes::where('code_number', $code)->exists();

        // If the code already exists, recursively call the function to generate a new code
        if ($existingCode) {
            return $this->generateUniqueCode();
        }

        // If the code doesn't exist, return it
        return $code;
    }

    function getMainSubscriber($id)
    {
        // $subscriberLogin = new SubscriberLogin();

        // Fetch subscriber details by ID
        $subscriberDetails = subscriberlogins::find($id);

        return $subscriberDetails;
    }

}
