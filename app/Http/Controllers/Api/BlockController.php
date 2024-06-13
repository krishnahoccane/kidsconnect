<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\RequestSentTo;
use Illuminate\Http\Request;

class RequestSentController extends Controller
{
    public function blockUser(Request $request)
    {
        $validatedData = $request->validate([
            'request_sent_to_id' => 'required|exists:request_sent_to,id',
            'blocked_by' => 'required|exists:subscriber_logins,id',
        ]);

        // Retrieve the RequestSentTo instance
        $requestSentTo = RequestSentTo::findOrFail($validatedData['request_sent_to_id']);

        // Update the status to indicate blocking
        $requestSentTo->update([
            'blocked' => true,
            'blocked_by' => $validatedData['blocked_by'],
        ]);

        return response()->json([
            'status' => 200,
            'message' => 'User blocked successfully',
            'data' => $requestSentTo,
        ], 200);
    }
}
