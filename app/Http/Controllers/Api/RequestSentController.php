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

    public function getByRequestToId(Request $request, $requestToId)
    {

        $findLoginId = RequestSentTo::where('RequestToId',$requestToId)->get();
        $loginUserReqeuestId = $findLoginId->pluck('RequestId');
        
        $requestData = RequestModel::where('id',$loginUserReqeuestId)->get();
        $getSubscriberId = $requestData->pluck('SubscriberId');
        $getSubscriberKidId = $requestData->pluck('SubscribersKidId');

        $subscriberData = subscriberlogins::where('id',$getSubscriberId)->get();
        $subscriberKid = subscribersKidModel::where('id',$getSubscriberKidId)->get();


        if ($findLoginId) {
            return response()->json([
                'status' => 200,
                'RequestData' => $requestData,
                'SubscriberData'=>$subscriberData,
                'KidsData'=>$subscriberKid
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No data found for the specified RequestToId'
            ], 404);
        }


        // $requestToId = $request->input('RequestToId');

        // $requestSentTo = RequestSentTo::find($requestToId);

        // if ($requestSentTo->count() > 0) {
        //     return response()->json([
        //         'status' => 200,
        //         'data' => $requestSentTo['RequestId']
        //     ], 200);
        // } else {
        //     return response()->json([
        //         'status' => 404,
        //         'message' => 'No data found for the specified RequestToId'
        //     ], 404);
        // }
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

    public function getPreviousEvents(Request $request)
    {
        $requestId = $request->RequestId;
        $requestToId = $request->RequestToId;
        $requestFromId = $request->RequestFromId;
        $currentDateTime = Carbon::now(); // Get the current date and time

        // Fetch the details with joins
        $events = DB::table('request_sent_to')
            ->join('EventRequests', 'request_sent_to.RequestId', '=', 'EventRequests.id')
            ->join('subscribers_kids as from_kid', 'request_sent_to.RequestFromId', '=', 'from_kid.id')
            ->join('subscribers_kids as to_kid', 'request_sent_to.RequestToId', '=', 'to_kid.id')
            ->join('subscriber_logins as from_parent', 'from_kid.MainSubscriberId', '=', 'from_parent.id')
            ->join('subscriber_logins as to_parent', 'to_kid.MainSubscriberId', '=', 'to_parent.id')
            ->where('request_sent_to.RequestId', $requestId)
            ->where('request_sent_to.RequestToId', $requestToId)
            ->where('request_sent_to.RequestFromId', $requestFromId)
            ->where('EventRequests.StatusId', 6) // Check if status is 6
            ->where(function ($query) use ($currentDateTime) {
                $query->where('EventRequests.EventEndDate', '<', $currentDateTime->toDateString())
                    ->orWhere(function ($query) use ($currentDateTime) {
                        $query->where('EventRequests.EventEndDate', '=', $currentDateTime->toDateString())
                            ->where('EventRequests.EventEndTime', '<', $currentDateTime->toTimeString());
                    });
            })
            ->select(
                'request_sent_to.*',
                'EventRequests.EventName',
                'EventRequests.EventType',
                'EventRequests.EventStartDate',
                'EventRequests.EventEndDate',
                'EventRequests.EventStartTime',
                'EventRequests.EventEndTime',
                'EventRequests.EventLocation',
                'EventRequests.EventInfo',
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
                'from_parent.FirstName as FromParentFirstName',
                'from_parent.LastName as FromParentLastName',
                'from_parent.Email as FromParentEmail',
                'from_parent.PhoneNumber as FromParentPhoneNumber',
                'to_parent.FirstName as ToParentFirstName',
                'to_parent.LastName as ToParentLastName',
                'to_parent.Email as ToParentEmail',
                'to_parent.PhoneNumber as ToParentPhoneNumber'
            )
            ->get();

        if ($events->count() > 0) {
            return response()->json([
                'status' => 200,
                'data' => $events
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No data found for the specified parameters'
            ], 404);
        }
    }



}
