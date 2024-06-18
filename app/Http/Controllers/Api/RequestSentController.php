<?php

namespace App\Http\Controllers\API;

use App\Models\RequestSentTo;
use App\Http\Controllers\Controller;
use App\Models\subscriberlogins;
use App\Models\subscribersKidModel;
use Illuminate\Http\Request;
use App\Models\RequestModel;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon; // Include Carbon for date handling


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

    // public function getByRequestToId(Request $request, $requestToId)
    // {
    //     $findLoginId = RequestSentTo::where('RequestToId',$requestToId)->get();
    //     $loginUserReqeuestId = $findLoginId->pluck('RequestId');
        
    //     $requestData = RequestModel::where('id',$loginUserReqeuestId)->get();
    //     $getSubscriberId = $requestData->pluck('SubscriberId');
    //     $getSubscriberKidId = $requestData->pluck('SubscribersKidId');

    //     $subscriberData = subscriberlogins::where('id',$getSubscriberId)->get();
    //     $subscriberKid = subscribersKidModel::where('id',$getSubscriberKidId)->get();


    //     if ($findLoginId) {
    //         return response()->json([
    //             'status' => 200,
    //             'RequestData' => $requestData,
    //             'SubscriberData'=>$subscriberData,
    //             'KidsData'=>$subscriberKid
    //         ], 200);
    //     } else {
    //         return response()->json([
    //             'status' => 404,
    //             'message' => 'No data found for the specified RequestToId'
    //         ], 404);
    //     }
    // }

    public function getByRequestToId(Request $request, $requestToId)
{
    // Find the RequestSentTo entries where RequestToId matches
    $findLoginId = RequestSentTo::where('RequestToId', $requestToId)->get();
    if ($findLoginId->isEmpty()) {
        return response()->json([
            'status' => 404,
            'message' => 'No data found for the specified RequestToId'
        ], 404);
    }

    // Get Request IDs from the found entries
    $loginUserRequestIds = $findLoginId->pluck('RequestId');

    // Fetch Request data
    $requestData = RequestModel::whereIn('id', $loginUserRequestIds)->get();

    // Get Subscriber and SubscriberKid IDs
    $getSubscriberIds = $requestData->pluck('SubscriberId')->unique();
    $getSubscriberKidIds = $requestData->pluck('SubscribersKidId')->unique();

    // Fetch Subscriber and SubscriberKid data
    $subscribers = subscriberlogins::whereIn('id', $getSubscriberIds)->get();
    $kids = subscribersKidModel::whereIn('id', $getSubscriberKidIds)->get();

    // Structure the response data
    $data = [];

    foreach ($findLoginId as $login) {
        $request = $requestData->where('id', $login->RequestId)->first();
        
        // Find Subscriber data
        $subscriber = $subscribers->where('id', $request->SubscriberId)->first();
        
        // Find Kid data
        $kid = $kids->where('id', $request->SubscribersKidId)->first();
        
        if ($request && $subscriber && $kid) {
            // Fetch details of the RequestToId itself (Kid details)
            $requestToIdDetails = subscribersKidModel::find($requestToId); // Assuming 'Kid' model has 'MainSubscriberId' field
            if (!$requestToIdDetails) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Details not found for the specified RequestToId'
                ], 404);
            }

            // Fetch primary parent details of the Kid based on MainSubscriberId
            $primaryParentDetails = subscriberlogins::find($requestToIdDetails->MainSubscriberId);
            if (!$primaryParentDetails) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Primary parent details not found for the specified RequestToId'
                ], 404);
            }

            // Fetch all secondary parents for all kids associated with the same MainSubscriberId
            $secondaryParents = subscriberlogins::where('MainSubscriberId', $requestToIdDetails->MainSubscriberId)
                                                 ->where('id', '<>', $primaryParentDetails->id)
                                                 ->get();

            // Prepare data for response
            $kidData = [
                'Request' => $request,
                'RequestDetails' => [
                    'RequestFromId' => $login->RequestFromId,
                    'RequestToId' => $login->RequestToId,
                    'ReceiverStatus' => $login->Receiverstatus,
                    'ReceiverStatusDate' => $login->ReceiverStatusDate,
                    'RequestCreatedOn' => $login->created_at,
                    'RequestUpdatedBy' => $login->UpdatedBy
                ],
                'RequestToIdDetails' => [
                    'Kid' => $requestToIdDetails,
                    'PrimaryParent' => $primaryParentDetails,
                    'SecondaryParents' => $secondaryParents, // Include all secondary parents here
                ],
                'Subscriber' => $subscriber,
                'Kid' => $kid,
                
            ];

            $data[] = $kidData;
        }
    }

    return response()->json([
        'status' => 200,
        'data' => $data
    ], 200);
}


public function getRequestsByRequestFromId(Request $request, $requestFromId)
{
    // Find the RequestSentTo entries where RequestFromId matches
    $findRequests = RequestSentTo::where('RequestFromId', $requestFromId)->get();
    
    // Check if no data found
    if ($findRequests->isEmpty()) {
        return response()->json([
            'status' => 404,
            'message' => 'No data found for the specified RequestFromId'
        ], 404);
    }

    // Get Request IDs from the found entries
    $requestIds = $findRequests->pluck('RequestId');

    // Fetch Request data
    $requestData = RequestModel::whereIn('id', $requestIds)->get();

    // Get Subscriber and SubscriberKid IDs
    $getSubscriberIds = $requestData->pluck('SubscriberId')->unique();
    $getSubscriberKidIds = $requestData->pluck('SubscribersKidId')->unique();

    // Fetch Subscriber and SubscriberKid data
    $subscribers = subscriberlogins::whereIn('id', $getSubscriberIds)->get();
    $kids = subscribersKidModel::whereIn('id', $getSubscriberKidIds)->get();

    // Structure the response data as an array
    $data = [];

    foreach ($findRequests as $requestEntry) {
        $request = $requestData->where('id', $requestEntry->RequestId)->first();
        
        // Find Subscriber data
        $subscriber = $subscribers->where('id', $request->SubscriberId)->first();
        
        // Find Kid data
        $kid = $kids->where('id', $request->SubscribersKidId)->first();
        
        if ($request && $subscriber && $kid) {
            // Fetch details of the RequestToId itself (Kid details)
            $requestToIdDetails = subscribersKidModel::find($requestEntry->RequestToId);
            if (!$requestToIdDetails) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Details not found for the specified RequestToId'
                ], 404);
            }

            // Fetch primary parent details of the Kid based on MainSubscriberId
            $primaryParentDetails = subscriberlogins::find($requestToIdDetails->MainSubscriberId);
            if (!$primaryParentDetails) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Primary parent details not found for the specified RequestToId'
                ], 404);
            }

            // Fetch all secondary parents for all kids associated with the same MainSubscriberId
            $secondaryParents = subscriberlogins::where('MainSubscriberId', $requestToIdDetails->MainSubscriberId)
                                                 ->where('id', '<>', $primaryParentDetails->id)
                                                 ->get();

            // Prepare data for response
            $kidData = [
                'Request' => $request,
                'RequestDetails' => [
                    'RequestFromId' => $requestEntry->RequestFromId,
                    'RequestToId' => $requestEntry->RequestToId,
                    'ReceiverStatus' => $requestEntry->Receiverstatus,
                    'ReceiverStatusDate' => $requestEntry->ReceiverStatusDate,
                    'RequestCreatedOn' => $requestEntry->created_at,
                    'RequestUpdatedBy' => $requestEntry->UpdatedBy
                ],
                'RequestToIdDetails' => [
                    'Kid' => $requestToIdDetails,
                    'PrimaryParent' => $primaryParentDetails,
                    'SecondaryParents' => $secondaryParents, // Include all secondary parents here
                ],
                'Subscriber' => $subscriber,
                'Kid' => $kid,
                
            ];

            $data[] = $kidData;
        }
    }

    return response()->json([
        'status' => 200,
        'data' => $data
    ], 200);
}

    


    public function getByRequestFromId($requestFromId)
    {
        $requests = DB::table('request_sent_to')
            ->join('subscribers_kids as from_kid', 'request_sent_to.RequestFromId', '=', 'from_kid.id')
            ->join('subscribers_kids as to_kid', 'request_sent_to.RequestToId', '=', 'to_kid.id')
            ->join('EventRequests', 'request_sent_to.RequestId', '=', 'EventRequests.Id') // Join with EventRequests table
            ->where('request_sent_to.RequestFromId', $requestFromId)
            ->select(
                'request_sent_to.*',
                'from_kid.FirstName as FromKidFirstName',
                'from_kid.LastName as FromKidLastName',
                'from_kid.Email as FromKidEmail',
                'from_kid.Dob as FromKidDob',
                'from_kid.Gender as FromKidGender',
                'from_kid.ProfileImage as FromKidProfileImage',
                'from_kid.About as FromKidAbout',
                'from_kid.RoleId as FromKidRoleId',
                'from_kid.Keywords as FromKidKeywords',
                'from_kid.Address as FromKidAddress',
                'from_kid.City as FromKidCity',
                'from_kid.State as FromKidState',
                'from_kid.Country as FromKidCountry',
                'from_kid.ZipCode as FromKidZipCode',
                'to_kid.FirstName as ToKidFirstName',
                'to_kid.LastName as ToKidLastName',
                'to_kid.Email as ToKidEmail',
                'to_kid.Dob as ToKidDob',
                'to_kid.Gender as ToKidGender',
                'to_kid.ProfileImage as ToKidProfileImage',
                'to_kid.About as ToKidAbout',
                'to_kid.RoleId as ToKidRoleId',
                'to_kid.Keywords as ToKidKeywords',
                'to_kid.Address as ToKidAddress',
                'to_kid.City as ToKidCity',
                'to_kid.State as ToKidState',
                'to_kid.Country as ToKidCountry',
                'to_kid.ZipCode as ToKidZipCode',
                'EventRequests.EventName',       // Additional fields from EventRequests
                'EventRequests.EventType',
                'EventRequests.EventStartDate',
                'EventRequests.EventEndDate',
                'EventRequests.EventStartTime',
                'EventRequests.EventEndTime',
                'EventRequests.EventLocation',
                'EventRequests.EventInfo'
            )
            ->get();

        if ($requests->count() > 0) {
            return response()->json([
                'status' => 200,
                'data' => $requests
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No data found for the specified RequestFromId'
            ], 404);
        }
    }



    public function updatestatus(Request $request, $id)
    {
        // Find the request sent entry
        $requestSentTo = RequestSentTo::find($id);

        if (!$requestSentTo) {
            return response()->json([
                'status' => 404,
                'message' => 'Request not found'
            ], 404);
        }

        // Update the receiver status and receiver status date
        $requestSentTo->Receiverstatus = $request->Receiverstatus;
        $requestSentTo->ReceiverStatusDate = Carbon::now(); // Set to current date and time
        $requestSentTo->save();

        return response()->json([
            'status' => 200,
            'message' => 'Receiver status updated successfully',
            'data' => $requestSentTo
        ], 200);
    }

   


}
