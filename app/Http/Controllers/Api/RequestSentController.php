<?php

namespace App\Http\Controllers\API;

use App\Models\RequestSentTo;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
    // Create a new requestsentTo instance with the provided data
    $newRequestSentTo = RequestSentTo::create([
        'RequestId' => $request->RequestId,
        'RequestFromId' => $request->RequestFromId,
        'RequestToId' => $request->RequestToId,
        'ReceiverStatus' => $request->ReceiverStatus,
        'RecStatusDate' => $request->RecStatusDate,
        'SenderStatus' => $request->SenderStatus,
        'SenderStatusDate' => $request->SenderStatusDate,
        'ReceiverFeedback' => $request->ReceiverFeedback,
        'RecFeedbackDate' => $request->RecFeedbackDate,
        'SenderFeedback' => $request->SenderFeedback,
        'SenderFeedbackDate' => $request->SenderFeedbackDate,
        'status' => $request->status,
        'CreatedDate' => now(), // Assuming you want to set the current date/time
        'UpdatedBy' => $request->user()->id, // Assuming you want to store the ID of the user who made the request
        'UpdatedDate' => now(), // Assuming you want to set the current date/time
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
