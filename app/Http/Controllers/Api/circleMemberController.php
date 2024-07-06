<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CircleMember;
use App\Models\SubscriberLogins;
use Carbon\Carbon;
use App\Services\SubscriberService;
use Illuminate\Support\Facades\Log;

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

        if ($AllMember->count() > 0) {
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
        // Fetch the current user and their main subscriber ID
        $currentUser = SubscriberLogins::find($id);
    
        // Check if the current user is a secondary parent
        $mainSubscriberId = $currentUser && $currentUser->Entry_code_type == 2 ? $currentUser->MainSubscriberId : null;
    
        // Check if the current user is a primary parent
        $secondaryIds = SubscriberLogins::where('MainSubscriberId', $id)->pluck('id')->toArray();
    
        // Collect IDs for both primary and secondary parents
        $userIds = array_filter(array_merge([$id, $mainSubscriberId], $secondaryIds));
    
        // Log for debugging
        \Log::info('User IDs for fetching friends: ', $userIds);
    
        // Fetch friend requests where current user or their primary/secondary parent is the sender
        $sentRequests = CircleMember::whereIn('senderId', $userIds)->where('status', 4)->get();
    
        // Fetch friend requests where current user or their primary/secondary parent is the receiver
        $receivedRequests = CircleMember::whereIn('receiverId', $userIds)->where('status', 4)->get();
    
        // Log fetched data for debugging
        \Log::info('Sent Requests: ', $sentRequests->toArray());
        \Log::info('Received Requests: ', $receivedRequests->toArray());
    
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
                'senderOrReceiver' => 'sender',
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
    
            if (!in_array($friend->senderId, $userIds)) {
                $result[] = [
                    'id' => $friend->id,
                    'receiverId' => $friend->receiverId,
                    'profiletype' => $profileType,
                    'senderOrReceiver' => 'receiver',
                    'senderData' => $senderData,
                ];
            }
        }
    
        if (count($result) > 0) {
            return response()->json([
                'data' => $result
            ], 200);
        } else {
            \Log::info('No friends found for user IDs: ', $userIds);
            return response()->json([
                'message' => 'No friends found'
            ], 200);
        }
    }

    public function getReceivedPendingRequests(Request $request, $id)
    {
        $pendingRequests = CircleMember::where('receiverId', $id)->where('status', 3)->get();


        $result = [];

        foreach ($pendingRequests as $pending) {
            $profileType = $pending->profileType;
            $senderData = null;

            // if ($profileType === 'parent') {
                $senderData = $this->subscriberService->getSubscriberDetails($pending->senderId);
            // } elseif ($profileType === 'kid') {
                // $senderData = $this->subscriberService->showKidParent($pending->senderId);
            // }

            $result[] = [
                'id' => $pending->id,
                'senderId' => $pending->senderId,
                'profileType' => $profileType,
                'senderData' => $senderData,
            ];
        }

        return response()->json([
            'status' => 200,
            'data' => $result
        ], 200);
    }
}
