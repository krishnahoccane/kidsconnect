<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CodeTypes;
use App\Models\RegCodes;
use App\Models\subscriberlogins;
use Illuminate\Http\Request;

class RegCodeController extends Controller
{
    //
    public function index($id = null)
    {

        if ($id) {
            $regCode_data_individual = RegCodes::where('id', $id)->get();

            // $subscriberUserIndividual = subscriberlogins::find($id);

            if ($regCode_data_individual->count() > 0) {
                return response()->json([

                    'status' => 200,
                    'data' => $regCode_data_individual

                ], 200);
            } else {
                return response()->json([

                    'status' => 404,
                    'message' => "Data Not Avialable"

                ], 404);
            }
        } else {
            $regCode_data = RegCodes::all();

            if ($regCode_data->count() > 0) {
                return response()->json([

                    'status' => 200,
                    'data' => $regCode_data

                ], 200);
            } else {
                return response()->json([

                    'status' => 404,
                    'message' => "Data Not Avialable"

                ], 404);
            }
        }


    }


    public function show($id = null, $user_id = null)
    {
        if ($id) {
            $subscriberUserIndividual = subscriberlogins::find($user_id);
            return response()->json([

                'status' => 200,
                'data' => $subscriberUserIndividual

            ], 200);
        } else {
            return "nothing given";
        }
    }

    public function verify(Request $request)
    {

        $entryVerificationCode = $request->input('code');

        $verifyingCode = RegCodes::where('code_number', $entryVerificationCode)->first();


        if ($verifyingCode) {
            $type = $verifyingCode->code_type_id;
            $verifyingCodeType = CodeTypes::where('id', $type)->first();

            $entryRefType = 1;
            $Refcode = $this->generateUniqueCode();
            $entryInvType = 2;
            $Invcode = $this->generateUniqueCode();
            $RegfcodeEntry = RegCodes::firstOrCreate([
                'code_type_id' => $entryRefType,
                'code_number' => $Refcode,
            ]);
            if ($RegfcodeEntry) {
                $InvcodeEntry = RegCodes::firstOrCreate([
                    'code_type_id' => $entryInvType,
                    'code_number' => $Invcode,
                ]);
                return response()->json([
                    'status' => 200,
                    'message' => "Code Matched",
                    'The entry code type is: ' => $verifyingCodeType->CodeName
                ], 200);
            }


        } else {
            return response()->json([

                'status' => 403,
                'message' => "Code Not Matched"

            ], 403);
        }

    }


    private function generateUniqueCode()
    {
        // Generate a random 4-digit code
        $code = str_pad(mt_rand(0, 9999), 5, '0', STR_PAD_LEFT);

        // Check if the code already exists in the database
        $existingCode = RegCodes::where('code_number', $code)->exists();

        // If the code already exists, recursively call the function to generate a new code
        if ($existingCode) {
            return $this->generateUniqueCode();
        }

        // If the code doesn't exist, return it
        return $code;
    }
}
