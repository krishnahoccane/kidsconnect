<?php

namespace App\Http\Controllers\API;

use App\Models\RequestSentTo;
use App\Http\Controllers\Controller;
use App\Models\subscriberlogins;
use Illuminate\Http\Request;
use App\Models\RequestModel;



class RequestSentController extends Controller
{
    public function index()
    {
        //
        $requsetsent = RequestSentController::all();
        if ($requsetsent->count() > 0) {
            return response()->json([
                'status' => 200,
                'data' => $requsetsent
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No Data Found'
            ], 404);
        }
    }

    public function store(Request $request)
    {
        // Retrieve the request details using the provided request ID
        $requestDetails = RequestModel::find($request->id);   
        

        
        // Check if the request exists
        if (!$requestDetails) {
            return response()->json([
                'status' => 404,
                'message' => 'Request not found'
            ], 404);
        }
    
       
        // Create a new requestsentTo instance with the provided data
        $newRequestSentTo = RequestSentTo::create([
            'RequestId' => $requestDetails,
            'RequestFromId' => $request->RequestFromId,
            'RequestToId' => $request->requesttoid,
            'ReceiverStatus' => $request->ReceiverStatus,
            'RecStatusDate' => $request->RecStatusDate,
            'SenderStatus' => $request->SenderStatus,
            'SenderStatusDate' => $request->SenderStatusDate,
            'ReceiverFeedback' => $request->ReceiverFeedback,
            'RecFeedbackDate' => $request->RecFeedbackDate,
            'SenderFeedback' => $request->SenderFeedback,
            'SenderFeedbackDate' => $request->SenderFeedbackDate,
            'status' => $request->status,
        ]);
    
        // Return the response based on whether the request was successful
        if ($newRequestSentTo) {
            return response()->json([
                'status' => 201,
                'message' => 'Request sent successfully',
                'data' => $newRequestSentTo
            ], 201);
        } else {
            return response()->json([
                'status' => 500,
                'message' => 'Failed to send request'
            ], 500);
        }
    }
    
}
