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

    // public function verify(Request $request){

    //     $entryVerificationCode = $request->input('code');

    //     $verifyingCode = RegCodes::where('code_number', $entryVerificationCode)->first();
       

    //     if($verifyingCode){
    //         $type = $verifyingCode->code_type_id;
    //         $verifyingCodeType = CodeTypes::where('id', $type)->first();
    //         return response()->json([

    //             'status' => 200,
    //             'message' => "Code Matched",
    //             'The entry code type is: '=>$verifyingCodeType->CodeName


    //         ], 200);
    //     }else{
    //         return response()->json([

    //             'status' => 403,
    //             'message' => "Code Not Matched"

    //         ], 403);
    //     }

    // }
    // public function verify(Request $request){
    //     $entryVerificationCode = $request->input('code');
    
    //     $verifyingCode = RegCodes::where('code_number', $entryVerificationCode)->first();
       
    //     if($verifyingCode){
    //         // Get the Ref_Inv_By ID from the RegCodes table
    //         $refInvById = $verifyingCode->code_type_id;
    
    //         // Assuming $request contains other necessary subscriber data
    //         $subscriberData = $request->all();
    //         // Insert the verifying code ID and Ref_Inv_By ID into the subscriber data
    //         $subscriberData['EntryCode'] = $verifyingCode->id;
    //         $subscriberData['Ref_Inv_By'] = $refInvById;
            
    //         // Create or update the subscriber
    //         $subscriber = subscriberlogins::create($subscriberData);
    
    //         $type = $verifyingCode->code_type_id;
    //         $verifyingCodeType = CodeTypes::where('id', $type)->first();
    //         return response()->json([
    //             'status' => 200,
    //             'message' => "Code Matched",
    //             'The entry code type is: ' => $verifyingCodeType->CodeName,
    //             'subscriber_email' => $subscriber->Email,
    //             'subscriber_password' => 'password' // You can return password here if needed
    //         ], 200);
    //     }else{
    //         return response()->json([
    //             'status' => 403,
    //             'message' => "Code Not Matched"
    //         ], 403);
    //     }
    // }
    
 // Verify Endpoint
public function verify(Request $request){
    $entryVerificationCode = $request->input('code');

    $verifyingCode = RegCodes::where('code_number', $entryVerificationCode)->first();
   
    if($verifyingCode){
        // Get the Ref_Inv_By ID from the RegCodes table
        $refInvById = $verifyingCode->code_type_id;

        // Assuming $request contains other necessary subscriber data
        $subscriberData = $request->all();
        // Insert the verifying code ID and Ref_Inv_By ID into the subscriber data
        $subscriberData['EntryCode'] = $verifyingCode->id;
        $subscriberData['Ref_Inv_By'] = $refInvById;
        
        // Create the subscriber
        $subscriber = subscriberlogins::create($subscriberData);

        $type = $verifyingCode->code_type_id;
        $verifyingCodeType = CodeTypes::where('id', $type)->first();
        return response()->json([
            'status' => 200,
            'message' => "Code Matched",
            'The entry code type is: ' => $verifyingCodeType->CodeName,
            'subscriber_id' => $subscriber->id // Return the subscriber ID
        ], 200);
    }else{
        return response()->json([
            'status' => 403,
            'message' => "Code Not Matched"
        ], 403);
    }
}

// Update Endpoint
public function update(Request $request, $subscriber_id){
    // Find the subscriber by ID
    $subscriber = subscriberlogins::findOrFail($subscriber_id);

    // Update email and password
    $subscriber->update([
        'Email' => $request->input('email'),
        'Password' => bcrypt($request->input('password'))
    ]);

    return response()->json([
        'status' => 200,
        'message' => "Subscriber updated successfully"
    ], 200);
}
   
}
