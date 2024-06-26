<?php

namespace App\Http\Controllers\API;

use App\Models\RequestChat;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RequestSentTo;


class RequestChatController extends Controller
{
    public function index()
    {
        //
        $requsetchat = RequestChat::all();
        if ($requsetchat->count() > 0) {
            return response()->json([
                'status' => 200,
                'data' => $requsetchat
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
        // Get the RequestSent instance based on RequestSentId
        $requestSent = RequestSentTo::find($request->input('RequestId'));

        if (!$requestSent) {
            return response()->json([
                'status' => 404,
                'message' => 'Request Sent not found',
            ], 404);
        }

        // Create a new request chat instance with the provided data
        $newRequestChat = RequestChat::create([
            'RequestId' => $requestSent->RequestId,
            'RequestSentId' => $request->input('RequestSentId'),
            'ChatSenderId' => $request->input('ChatSenderId'),
            'message' => $request->input('message'),
            'attachment' => $request->input('attachment'),
            'CreatedDate' => now(), // Assuming you want to use the current date and time
        ]);

        // Return the response based on whether the request chat was successfully created
        if ($newRequestChat) {
            return response()->json([
                'status' => 200,
                'message' => 'Request chat created successfully',
                'data' => $newRequestChat
            ], 200);
        } else {
            return response()->json([
                'status' => 500,
                'message' => 'Failed to create request chat'
            ], 500);
        }
    }
}
