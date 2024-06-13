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
        $requsetsent = RequestSentTo::all();
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
        $requestDetails = RequestModel::find($request->RequestId);
    
        // Check if the request exists
        if (!$requestDetails) {
            return response()->json([
                'status' => 404,
                'message' => 'Request not found'
            ], 404);
        }
    
        // Create a new RequestSentTo instance with the provided data
        $newRequestSentTo = RequestSentTo::create([
            'RequestId' => $requestDetails->id, // Use the ID from the request details
            'RequestFromId' => $request->RequestFromId,
            'RequestToId' => $request->RequestToId,
            'Receiverstatus' => $request->Receiverstatus,
            'ReceiverStatusDate' => $request->ReceiverStatusDate,
            'EventReqStatus' => $request->SenderStatus,
            'EventReqStatusDate' => $request->SenderStatusDate,
            'ReceiverFeedBack' => $request->ReceiverFeedBack,
            'RecFeedbackDate' => $request->RecFeedbackDate,
            'SenderFeedBack' => $request->SenderFeedBack,
            'SenderFeedBackDate' => $request->SenderFeedBackDate,
            'StatusId' => $request->StatusId,
            'UpdatedBy' => $request->UpdatedBy,
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

    public function getByRequestToId($requestToId)
    {
        $requests = RequestSentTo::where('RequestToId', $requestToId)->get();

        if ($requests->count() > 0) {
            return response()->json([
                'status' => 200,
                'data' => $requests
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No data found for the specified RequestToId'
            ], 404);
        }
    }


    
}
