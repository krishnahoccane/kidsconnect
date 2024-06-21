<?php

namespace App\Http\Controllers\API;

use App\Models\RequestSentTo;
use App\Http\Controllers\Controller;
use App\Models\subscriberlogins;
use App\Models\subscribersKidModel;
use Illuminate\Http\Request;
use App\Models\RequestModel;
use Carbon\Carbon; // Include Carbon for date handling


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
        $requestDetails = RequestModel::find($request->RequestId);
    
        // Check if the request exists
        if (!$requestDetails) {
            return response()->json([
                'status' => 404,
                'message' => 'Request not found',
                'RequestId' => $request->RequestId // Include the RequestId in the response for debugging
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
    $data = new \stdClass(); // Create an empty object to hold all data

    $data->requests = [];

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
            $kidData = new \stdClass(); // Create an empty object for this kid's data
            $kidData->Request = $request;
            $kidData->RequestDetails = [
                'id' =>$login->id,
                'RequestFromId' => $login->RequestFromId,
                'RequestToId' => $login->RequestToId,
                'ReceiverStatus' => $login->Receiverstatus,
                'ReceiverStatusDate' => $login->ReceiverStatusDate,
                'RequestCreatedOn' => $login->created_at,
                'RequestUpdatedBy' => $login->UpdatedBy
            ];
            $kidData->RequestToIdDetails = new \stdClass();
            $kidData->RequestToIdDetails->Kid = $requestToIdDetails;
            $kidData->RequestToIdDetails->PrimaryParent = $primaryParentDetails;
            $kidData->RequestToIdDetails->SecondaryParents = $secondaryParents; // Include all secondary parents here
            $kidData->Subscriber = $subscriber;
            $kidData->Kid = $kid;

            // Add to the main data object's requests array
            $data->requests[] = $kidData;
        }
    }

    return response()->json([
        'status' => 200,
        'data' => $data
    ], 200);
}


public function getRequestsByRequestFromId(Request $request, $requestFromId)
{
    try {
        // Find the RequestSentTo entries where RequestFromId matches
        $findRequests = RequestSentTo::where('RequestFromId', $requestFromId)->get();
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

        // Initialize an empty object for response data
        $data = new \stdClass();
        $data->requests = [];

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

                // Prepare data for response as an object
                $kidData = new \stdClass();
                $kidData->Request = $request;
                $kidData->RequestDetails = [
                    'id' => $requestEntry->id,
                    'RequestFromId' => $requestEntry->RequestFromId,
                    'RequestToId' => $requestEntry->RequestToId,
                    'ReceiverStatus' => $requestEntry->Receiverstatus,
                    'ReceiverStatusDate' => $requestEntry->ReceiverStatusDate,
                    'RequestCreatedOn' => $requestEntry->created_at,
                    'RequestUpdatedBy' => $requestEntry->UpdatedBy
                ];
                $kidData->RequestToIdDetails = new \stdClass();
                $kidData->RequestToIdDetails->Kid = $requestToIdDetails;
                $kidData->RequestToIdDetails->PrimaryParent = $primaryParentDetails;
                $kidData->RequestToIdDetails->SecondaryParents = $secondaryParents; // Include all secondary parents here
                $kidData->Subscriber = $subscriber;
                $kidData->Kid = $kid;

                // Add to the main data object with request ID as key
                $data->requests[] = $kidData;
            }
        }

        return response()->json([
            'status' => 200,
            'data' => $data
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'status' => $e->getCode() ?: 500,
            'message' => $e->getMessage(),
        ], $e->getCode() ?: 500);
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
            $requestSentTo->UpdatedBy = $request->UpdatedBy;
            $requestSentTo->ReceiverStatusDate = Carbon::now(); // Set to current date and time
            $requestSentTo->save();
    
            return response()->json([
                'status' => 200,
                'message' => 'Receiver status updated successfully',
                'data' => $requestSentTo
            ], 200);
        }

        public function destroy($id)
    {
        $allstatus = RequestSentTo::find($id);

        if ($allstatus) {
            $allstatus->delete();
            return response()->json([
                'status' => 200,
                'message' => 'Record deleted successfully'
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No Request Sent found'
            ], 404);
        }
    }

}
