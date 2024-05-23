<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\subscriberlogins;
use App\Models\subscribersModel;
use App\Http\Controllers\Controller;

class subscriberController extends Controller
{
   
    public function index()
    {
        // comment - vishal - Here pluk is used to get perticular column of table and toArray() used to fetch in array form,
        // After fetching the id from subscriber table passing that into the subscriberlogin table and fetching sepecfic condition
        // MainsubscriberId is Subscriber table id & IsMain 1 - Selected fields are Id, FName, LName, Email, RID, CreatedDate

        $subscriberIds = subscribersModel::pluck('id')->toArray();
        $subscriberLoginData = subscriberlogins::whereIn('MainSubscriberId', $subscriberIds)->where('IsMain', 1)
            ->select('id', 'FirstName', 'LastName', 'Email', 'RoleId', 'created_at')
            ->get();

        if ($subscriberLoginData->count() > 0) {
            return response()->json([
                'status' => 200,
                'data' => $subscriberLoginData
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
        // $id = $request->id;
        $email = $request->Email;
        $entryCodeId = $request->EntryCode;
        // $DeviceId = $request->DeviceId;
        $phoneNumber = $request->phoneNumber;

        if (empty($email)) {
            $phoneNumberExist = subscriberlogins::where('phoneNumber', $phoneNumber)->first();
            if ($phoneNumberExist) {
                return response()->json([
                    'status' => 200,
                    'data' => $phoneNumberExist,
                ], 200);
            } else {
                // Create subscriber record with phone number
                $this->createSubscriberData($request, null, $entryCodeId, $phoneNumber);
            }
        } elseif (empty($phoneNumber)) {
            $emailExist = subscriberlogins::where('Email', $email)->first();
            if ($emailExist) {
                return response()->json([
                    'status' => 200,
                    'data' => $emailExist
                ], 200);
            } else {
                // Create subscriber record with email
                $this->createSubscriberData($request, $email, $entryCodeId, null);
            }
        }
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

}
