<?php

namespace App\Http\Controllers\Api;

use App\Models\subscriberlogins;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

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
        }
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'FirstName' => 'required | string',
            'LastName' => 'required | string',
            'email' => 'required | email',
            'Dob' => 'required | date',
            'Gender' => 'required | in:Male,Female,Others',
            'PhoneNumber' => 'required | numeric | size:10',
            'SSN' => 'required',
            'Password' => 'required | min:8',
            'About' => 'required',
            'Address' => 'required',
            // given size in kilobites - 2048 -2MB
            'ProfileImage' => 'required | image | mimes:jpeg,jpg,png | max: 2048',
            'Keywords' => '',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 403,
                'message' => "Validation error occur",
                'data' => $validator->errors()->all()
            ], 403);
        } else {
            $sub_login = subscriberlogins::firstOrCreate([
                'FirstName' => $request->FirstName,
                'LastName' => 'required | string',
                'email' => 'required | email',
                'Dob' => 'required | date',
                'Gender' => 'required | in:Male,Female,Others',
                'PhoneNumber' => 'required | numeric | size:10',
                'SSN' => 'required',
                'Password' => 'required | min:8',
                'About' => 'required',
                'Address' => 'required',
                'ProfileImage' => 'required | image | mimes:jpeg,jpg,png | max: 2048',
                'Keywords' => '',
            ]);
        }
    }
}
