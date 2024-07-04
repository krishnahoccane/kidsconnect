<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CircleMember;
use App\Models\SubscriberLogins;
use App\Models\SubscribersKidModel;
use Carbon\Carbon;
use App\Services\SubscriberService;

class CircleMemberController extends Controller
{

    protected $subscriberService;

    public function __construct(SubscriberService $subscriberService)
    {
        $this->subscriberService = $subscriberService;
    }

    public function allMember()
    {

        $AllMember = CircleMember::all();

        if ($AllMember) {
            return response()->json([
                'status' => 200,
                'data' => $AllMember
            ], 200);
        } else {
            return response()->json([
                'status' => 403,
                'message' => "No Data Found"
            ], 403);
        }
    }
    public function addFriend(Request $request)
    {
        $circleRequest = CircleMember::create([
            'senderId' => $request->senderId,
            'receiverId' => $request->receiverId,
            'profileType' => $request->profileType,
            'created_by' => $request->senderId,
            'updated_by' => null,
            'updated_at' => null,
        ]);

        return response()->json([
            'status' => 200,
            'message' => 'Friend request sent successfully',
            'data' => $circleRequest,
        ], 200);
    }

    public function acceptFriend(Request $request, $id)
{
    $circleRequest = CircleMember::find($id);

    if (!$circleRequest) {
        return response()->json([
            'status' => 404,
            'message' => 'Friend request not found',
        ], 404);
    }

    // Convert status to integer
    $status = (int) $request->status;

    // Validate status against enum values
    if (!in_array($status, [3, 4, 8])) {
        return response()->json([
            'status' => 400,
            'message' => 'Invalid status value. Accepted values are 3, 4, 8.',
        ], 400);
    }

    $circleRequest->update([
        'status' => $status,
        'updated_by' => $circleRequest->receiverId,
        'updated_at' => Carbon::now(),
    ]);

    return response()->json([
        'status' => 200,
        'message' => 'Friend request status updated successfully',
        'data' => $circleRequest,
    ], 200);
}


public function getFriendList(Request $request, $id)
    {
        // Fetch friend requests where current user is sender
        $sentRequests = CircleMember::where('senderId', $id)->where('status', 4)->get();

        // Fetch friend requests where current user is receiver
        $receivedRequests = CircleMember::where('receiverId', $id)->where('status', 4)->get();

        $result = [];

        // Process sent requests
        foreach ($sentRequests as $friend) {
            $profileType = $friend->profileType;
            $receiverData = null;

            if ($profileType === 'parent') {
                $receiverData = $this->subscriberService->getSubscriberDetails($friend->receiverId);
            } elseif ($profileType === 'kid') {
                $receiverData = $this->subscriberService->showKidParent($friend->receiverId);
            }

            $result[] = [
                'id' => $friend->id,
                'receiverId' => $friend->receiverId,
                'profiletype' => $profileType,
                'senderOrReceiver' => 'sender', // Indicate this is a sent request
                'receiverData' => $receiverData,
            ];
        }

        // Process received requests
        foreach ($receivedRequests as $friend) {
            $profileType = $friend->profileType;
            $senderData = null;

            if ($profileType === 'parent') {
                $senderData = $this->subscriberService->getSubscriberDetails($friend->senderId);
            } elseif ($profileType === 'kid') {
                $senderData = $this->subscriberService->showKidParent($friend->senderId);
            }

            // Check if the received request sender is not the current user
            if ($friend->senderId != $id) {
                $result[] = [
                    'id' => $friend->id,
                    'receiverId' => $friend->receiverId,
                    'profiletype' => $profileType,
                    'senderOrReceiver' => 'receiver', // Indicate this is a received request
                    'senderData' => $senderData,
                ];
            }
        }

        return response()->json($result);
    }


    
    
}
