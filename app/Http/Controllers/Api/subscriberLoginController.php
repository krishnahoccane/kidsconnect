<?php

namespace App\Http\Controllers\Api;

use App\Models\subscriberlogins;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class subscriberLoginController extends Controller
{
    //
    public function index()
    {
        $sub_login = subscriberlogins::all();

        if ($sub_login->count() > 0) {
            return response()->json([
                'status' => 200,
                'data' => $sub_login
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => "No Data Found"
            ], 404);
        }
    }

    public function create(Request $request)
    {
        
            $sub_login = subscriberlogins::firstOrCreate([
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
                'SSNimage'=>$request->SSNimage
                // 'Keywords' => $request->Keywords,
            ]);

            if ($sub_login->wasRecentlyCreated) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Subscriber Registered successfully',
                    'data' => $sub_login
                ], 200);
            } else {
                return response()->json([
                    'status' => 409,
                    'message' => 'Subscriber already exists'
                ], 409);
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
            'SSNimage' => ['string']

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
                    'SSNimage'=>$request->SSNimage
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

    public function delete(Request $request, int $id){

        $sub_login = subscriberlogins::find($id);

        if (!$sub_login) {
            return response()->json([
                'status' => 403,
                'message' => "Requested Id Data NotFound"
            ], 403);
        } 

        $sub_login->delete();

        return response()->json(['message' => 'Subscriber deleted successfully'],200);

    }


}
