<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CircleMember;
use App\Models\SubscriberLogins;
use App\Models\SubscribersKidModel;
use Carbon\Carbon;

class CircleMemberController extends Controller
{
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
    
        // Handle additional logic based on status if needed
        if ($status === 4) { // Assuming 4 represents 'accept'
            // Update circles for both sender and receiver
            $this->updateCircleForSenderAndReceiver($circleRequest->senderId, $circleRequest->receiverId);
        }
    
        // Fetch updated circle members for both sender and receiver
        $updatedSenderCircleMembers = CircleMember::where('senderId', $circleRequest->senderId)
            ->where('status', 4) // Fetch only accepted members
            ->with('receiver') // Assuming 'receiver' is the relationship in CircleMember model
            ->get();
    
        $updatedReceiverCircleMembers = CircleMember::where('senderId', $circleRequest->receiverId)
            ->where('status', 4) // Fetch only accepted members
            ->with('receiver') // Assuming 'receiver' is the relationship in CircleMember model
            ->get();
    
        return response()->json([
            'status' => 200,
            'message' => 'Friend request updated successfully',
            'data' => [
                'updatedSenderCircleMembers' => $updatedSenderCircleMembers,
                'updatedReceiverCircleMembers' => $updatedReceiverCircleMembers,
            ],
        ], 200);
    }
    
    private function updateCircleForSenderAndReceiver($senderId, $receiverId)
    {
        // Fetch sender's families and kids
        $senderFamilies = $this->fetchFamiliesAndKids($senderId);
    
        // Fetch receiver's families and kids
        $receiverFamilies = $this->fetchFamiliesAndKids($receiverId);
    
        // Add sender's families and kids to receiver's circle
        foreach ($senderFamilies as $familyMember) {
            CircleMember::create([
                'senderId' => $receiverId,
                'receiverId' => $familyMember->id,
                'profileType' => 'family',
                'status' => 4, // Accept status
                'created_by' => 'system',
                'updated_by' => 'system',
            ]);
        }
    
        // Add receiver's families and kids to sender's circle
        foreach ($receiverFamilies as $familyMember) {
            CircleMember::create([
                'senderId' => $senderId,
                'receiverId' => $familyMember->id,
                'profileType' => 'family',
                'status' => 4, // Accept status
                'created_by' => 'system',
                'updated_by' => 'system',
            ]);
        }
    }
    
    private function fetchFamiliesAndKids($userId)
    {
        $receiver = SubscriberLogins::find($userId);
    
        if (!$receiver) {
            return []; // Handle appropriately if receiver not found
        }
    
        // Fetch only the sender's families and kids
        if ($receiver->RoleId == 1) { // If the receiver is a primary parent
            $family = SubscriberLogins::where('MainSubscriberId', $receiver->id)->get();
            $kids = SubscribersKidModel::where('MainSubscriberId', $receiver->id)->get();
        } else { // If the receiver is a secondary parent
            $primaryParent = SubscriberLogins::find($receiver->MainSubscriberId);
            $family = SubscriberLogins::where('MainSubscriberId', $primaryParent->id)->get();
            $kids = SubscribersKidModel::where('MainSubscriberId', $primaryParent->id)->get();
        }
    
        return $family->merge($kids); // Merge both family members and kids
    }
    

}
