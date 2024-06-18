<?php

namespace App\Http\Controllers\API;

use App\Models\RequestModel;
use App\Models\defaultStatus;
use App\Models\RequestSentTo;
use App\Models\subscriberlogins;
use App\Models\subscribersKidModel;
use App\Models\subscribersModel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class RequestController extends Controller
{
    public function index()
    {
        // Fetch EventRequests with necessary joins
        $requests = DB::table('EventRequests')
            ->join('subscribers_kids', 'EventRequests.SubscribersKidId', '=', 'subscribers_kids.id')
            ->join('subscriber_logins as creator', 'EventRequests.CreatedBy', '=', 'creator.id')
            ->select(
                'EventRequests.*',
                'subscribers_kids.FirstName as KidFirstName',
                'subscribers_kids.LastName as KidLastName',
                'subscribers_kids.Email as KidEmail',
                'subscribers_kids.Dob as KidDob',
                'subscribers_kids.Gender as KidGender',
                'subscribers_kids.ProfileImage as KidProfileImage',
                'subscribers_kids.About as KidAbout',
                'subscribers_kids.RoleId as KidRoleId',
                'subscribers_kids.Keywords as KidKeywords',
               
    
                'creator.FirstName as CreatedByName',
                'creator.LastName as CreatedByLastName',
                'creator.Email as CreatedByEmail',
                'creator.PhoneNumber as CreatedByPhoneNumber',
                'creator.ProfileImage as CreatedByProfileImage',
                'creator.About as CreatedByAbout',
                'creator.BirthYear as CreatedByBirthYear',
                'creator.Gender as CreatedByGender',
                'creator.Address as CreatedByAddress',
                'creator.City as CreatedByCity',
                'creator.State as CreatedByState',
                'creator.Zipcode as CreatedByZipcode',
                'creator.Country as CreatedByCountry'
            )
            ->get();
    
        if ($requests->isEmpty()) {
            return response()->json([
                'status' => 404,
                'message' => 'No Data Found'
            ], 404);
        }
    
        // Prepare the response data structure
        $data = [];
    
        foreach ($requests as $request) {
            $eventRequest = new \stdClass();
            $eventRequest->EventRequest = new \stdClass();
            $eventRequest->EventRequest->id = $request->id;
            $eventRequest->EventRequest->SubscriberId = $request->SubscriberId;
            $eventRequest->EventRequest->SubscribersKidId = $request->SubscribersKidId;
            $eventRequest->EventRequest->EventName = $request->EventName;
            $eventRequest->EventRequest->EventType = $request->EventType;
            $eventRequest->EventRequest->EventStartDate = $request->EventStartDate;
            $eventRequest->EventRequest->EventEndDate = $request->EventEndDate;
            $eventRequest->EventRequest->EventStartTime = $request->EventStartTime;
            $eventRequest->EventRequest->EventEndTime = $request->EventEndTime;
            $eventRequest->EventRequest->EventLocation = $request->EventLocation;
            $eventRequest->EventRequest->RecordType = $request->RecordType;
            $eventRequest->EventRequest->EventInfo = $request->EventInfo;
            $eventRequest->EventRequest->created_at = $request->created_at;
            // Add more fields as needed
    
            $eventRequest->KidDetails = new \stdClass();
            $eventRequest->KidDetails->FirstName = $request->KidFirstName;
            $eventRequest->KidDetails->LastName = $request->KidLastName;
            $eventRequest->KidDetails->Email = $request->KidEmail;
            $eventRequest->KidDetails->Dob = $request->KidDob;
            $eventRequest->KidDetails->Gender = $request->KidGender;
            $eventRequest->KidDetails->ProfileImage = $request->KidProfileImage;
            $eventRequest->KidDetails->About = $request->KidAbout;
            $eventRequest->KidDetails->RoleId = $request->KidRoleId;
            $eventRequest->KidDetails->Keywords = $request->KidKeywords;
    
            // Fetch primary parent
            $primaryParent = DB::table('subscriber_logins')
                ->where('id', $request->CreatedBy)
                ->first();
    
            $eventRequest->KidDetails->PrimaryParent = $primaryParent;
    
            // Fetch secondary parents
            $secondaryParents = DB::table('subscriber_logins')
                ->where('MainSubscriberId', $request->SubscriberId)
                ->where('id', '!=', $request->CreatedBy) // Exclude primary parent
                ->get();
    
            $eventRequest->KidDetails->SecondaryParents = $secondaryParents;
    
            $data[] = $eventRequest;
        }
    
        return response()->json([
            'status' => 200,
            'data' => $data
        ], 200);
    }


    public function create(Request $request)
{
    // Retrieve the status based on the provided Statusid
    $status = DefaultStatus::find($request->Statusid);

    // Ensure that status exists
    if (!$status) {
        return response()->json([
            'status' => 404,
            'message' => 'Status not found'
        ], 404);
    }

    // Retrieve the subscriber based on the provided id
    $subscriberId = $request->input('SubscriberId');

    $keywords = $request->has('Keywords') ? $request->input('Keywords') : [];

    $serializedKeywords = json_encode($keywords);

    // Create a new request instance with the provided data
    $newRequest = RequestModel::create([
        'SubscriberId' => $subscriberId, // Required
        'SubscribersKidId' => $request->SubscribersKidId, // Required
        'EventName' => $request->EventName, // Required
        'EventType' => $request->EventType, // Ensure this matches the column type in DB
        'EventFor' => $request->EventFor, // Ensure this matches the column type in DB
        'EventStartDate' => $request->EventStartDate, // Required
        'EventEndDate' => $request->EventEndDate, // Required
        'EventStartTime' => $request->EventStartTime, // Required
        'EventEndTime' => $request->EventEndTime, // Required
        'Keywords' => $serializedKeywords, // Required
        'RecordType' => $request->RecordType,
        'Statusid' => $status->id,
        'LocationType' => $request->LocationType,
        'EventLocation' => $request->EventLocation,
        'EventInfo' => $request->EventInfo,
        'PickupLocation' => $request->PickupLocation,
        'DropLocation' => $request->DropLocation,
        'PrimaryResponsibleId' => $request->PrimaryResponsibleId,
        'ActivityType' => $request->ActivityType,
        'areGroupMemberVisible' => $request->areGroupMemberVisible,
        'IsGroupChat' => $request->IsGroupChat,
        'CreatedBy' => $subscriberId,
        'UpdatedBy' => $subscriberId // Assuming this should be updated
    ]);

    // Return the response based on whether the request was successful
    if ($newRequest) {
        return response()->json([
            'status' => 200,
            'message' => 'Request created successfully',
            'data' => $newRequest
        ], 200);
    } else {
        return response()->json([
            'status' => 500,
            'message' => 'Failed to create request'
        ], 500);
    }
}

    public function show($id)
    {
        // Find the request by its ID
        $request = RequestModel::find($id);
        $arr = [];
        $keywords = $request['Keywords'];

        array_push($arr, $keywords);

        $request['Keywords'] = $arr;

        array_push($arr, $request['Keywords']);

        // Check if the request exists
        if (!$request) {
            return response()->json([
                'status' => 404,
                'message' => 'Request not found'
            ], 404);

        }

        // Return the response with the request data
        return response()->json([
            'status' => 200,
            'message' => 'Request found',
            'data' => $request
        ], 200);
    }

    public function getRequestList($subscriberId)
    {
        // Fetch all requests for the given SubscriberId
        $requestList = RequestModel::where('SubscriberId', $subscriberId)
            ->get();

        // Check if any requests were found
        if ($requestList->isNotEmpty()) {
            return response()->json([
                'status' => 200,
                'data' => $requestList
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No Requests Found for this Subscriber'
            ], 404);
        }
    }


    // public function show($id)
    // {
    //     // Find the request by its ID
    //     $request = RequestModel::find($id);

    //     // Check if the request exists
    //     if (!$request) {
    //         return response()->json([
    //             'status' => 404,
    //             'message' => 'Request not found'
    //         ], 404);
    //     }

    //     // Initialize the array to hold keywords
    //     $arr = [];

    //     // Check if Keywords field exists and is not null
    //     if (!is_null($request->Keywords)) {
    //         $keywords = $request->Keywords;
    //         array_push($arr, $keywords);
    //         $request->Keywords = $arr;
    //     } else {
    //         $request->Keywords = $arr; // Assign empty array if Keywords is null
    //     }

    //     // Return the response with the request data
    //     return response()->json([
    //         'status' => 200,
    //         'message' => 'Request found',
    //         'data' => $request
    //     ], 200);
    // }

    public function update(Request $request, $id)
    {
        // Find the request by its ID
        $existingRequest = RequestModel::find($id);

        // Check if the request exists
        if (!$existingRequest) {
            return response()->json([
                'status' => 404,
                'message' => 'Request not found'
            ], 404);
        }

        // Retrieve the status based on the provided Statusid
        $status = DefaultStatus::find($request->Statusid);

        // Ensure that status exists
        if (!$status) {
            return response()->json([
                'status' => 404,
                'message' => 'Status not found'
            ], 404);
        }

        // Update the request instance with the provided data
        $existingRequest->update([
            'SubscriberId' => $request->SubscriberId,
            'SubscribersKidId' => $request->SubscribersKidId,
            'EventName' => $request->EventName,
            'EventType' => $request->EventType,
            'EventFor' => $request->EventFor,
            'EventStartDate' => $request->EventStartDate,
            'EventEndDate' => $request->EventEndDate,
            'EventStartTime' => $request->EventStartTime,
            'EventEndTime' => $request->EventEndTime,
            'Keywords' => $request->Keywords,
            'RecordType' => $request->RecordType,
            'Statusid' => $status->id,
            'LocationType' => $request->LocationType,
            'EventLocation' => $request->EventLocation,
            'EventInfo' => $request->EventInfo,
            'PickupLocation' => $request->PickupLocation,
            'DropLocation' => $request->DropLocation,
            'PrimaryResponsibleId' => $request->PrimaryResponsibleId,
            'ActivityType' => $request->ActivityType,
            'areGroupMemberVisible' => $request->areGroupMemberVisible,
            'IsGroupChat' => $request->IsGroupChat,
            'CreatedBy' => $request->SubscriberId,
            'UpdatedBy' => $request->SubscriberId // Assuming this should be updated
        ]);

        // Return the response based on whether the request was successfully updated
        if ($existingRequest) {
            return response()->json([
                'status' => 200,
                'message' => 'Request updated successfully',
                'data' => $existingRequest
            ], 200);
        } else {
            return response()->json([
                'status' => 500,
                'message' => 'Failed to update request'
            ], 500);
        }
    }

    public function previousEvent(Request $request, $requestFromId)
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
            $data->finishedEvents = [];
    
            $currentDate = now();
    
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
    
                    // Check if both EventEndDate and EventEndTime are less than the current date
                    $eventEndDateTime = \Carbon\Carbon::parse($request->EventEndDate . ' ' . $request->EventEndTime);
                    if ($eventEndDateTime < $currentDate) {
                        $data->finishedEvents[] = $kidData;
                    }
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
    

    // upcoming Event

    public function upcomingEvent(Request $request, $requestFromId)
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
        $data->upcomingEvents = [];

        $currentDate = now();

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

                // Check if EventStartDate and EventStartTime are greater than the current date
                $eventStartDateTime = \Carbon\Carbon::parse($request->EventStartDate . ' ' . $request->EventStartTime);
                if ($eventStartDateTime > $currentDate) {
                    $data->upcomingEvents[] = $kidData;
                }
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


// Active Event

public function ActiveEvent(Request $request, $requestFromId)
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
        $data->activeEvents = [];

        // Current date and time
        $currentDateTime = now();

        // Format current time in 24-hour format
        $currentTime = $currentDateTime->format('H:i:s');

        // Debugging output for current time
        \Log::info("Current Time: $currentTime");

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

                // Parse and format event start and end times from 12-hour to 24-hour format
                $eventStartDate = \Carbon\Carbon::parse($request->EventStartDate);
                $eventStartTime = \Carbon\Carbon::createFromFormat('g:i A', $request->EventStartTime)->format('H:i:s');
                $eventEndDate = \Carbon\Carbon::parse($request->EventEndDate);
                $eventEndTime = \Carbon\Carbon::createFromFormat('g:i A', $request->EventEndTime)->format('H:i:s');

                // Debugging output for event times
                \Log::info("Event Start Time: $eventStartTime, Event End Time: $eventEndTime");

                // Check if current date is within the event date range
                if ($currentDateTime->between($eventStartDate->startOfDay(), $eventEndDate->endOfDay())) {
                    // Check if current time is within the event start and end times
                    if ($currentTime >= $eventStartTime && $currentTime <= $eventEndTime) {
                        $data->activeEvents[] = $kidData;
                    }
                }
            }
        }

        // Debugging output for active events count
        \Log::info("Active Events Count: " . count($data->activeEvents));

        return response()->json([
            'status' => 200,
            'data' => $data
        ], 200);

    } catch (\Exception $e) {
        // Debugging output for exception
        \Log::error("Exception occurred: " . $e->getMessage());

        return response()->json([
            'status' => $e->getCode() ?: 500,
            'message' => $e->getMessage(),
        ], $e->getCode() ?: 500);
    }
}







    public function getEventDatesAutoUpdate()
    {
        // Fetch all event data
        $eventData = RequestModel::select('id', 'EventStartDate', 'EventEndDate', 'EventStartTime', 'EventEndTime', 'Statusid')->get();
    
        // Define current date and time
        $currentDateTime = now(); // Assuming you're using Carbon or similar for dates/times
    
        // Iterate through each event to determine its status
        $eventData = $eventData->map(function($item) use ($currentDateTime) {
            // Convert event start date/time to DateTime objects
            $startDate = \Carbon\Carbon::parse($item->EventStartDate . ' ' . $item->EventStartTime);
            $endDate = \Carbon\Carbon::parse($item->EventEndDate . ' ' . $item->EventEndTime);
    
            // Determine event status based on current date/time
            if ($currentDateTime < $startDate) {
                $item->Statusid = 'Upcoming';
            } elseif ($currentDateTime >= $startDate && $currentDateTime <= $endDate) {
                $item->Statusid = 'Ongoing';
            } elseif ($currentDateTime > $endDate) {
                $item->Statusid = 'Completed';
            } else {
                $item->Statusid = 'Unknown'; // Handle any edge cases
            }
    
            // Optionally, you can remove the original date and time fields from the response
            unset($item->EventStartDate);
            unset($item->EventEndDate);
            unset($item->EventStartTime);
            unset($item->EventEndTime);
    
            return $item;
        });
    
        // Return the event data with updated statuses
        return response()->json([
            'eventData' => $eventData,
        ]);
    }
    


    public function FavOrNot(Request $request, $event_id)
    {
        // Find the existingEvent by its ID
        $existingEvent = RequestModel::find($event_id);
        $FavOrNot = $request->isEventFav;

        // Check if the Event exists
        if (!$existingEvent) {
            return response()->json([
                'status' => 404,
                'message' => 'Event Not Found'
            ], 404);
        } else {

            if ($FavOrNot === "0") {
                $existingEvent->update([

                    'isEventFav' => $FavOrNot
                    // 'UpdatedBy' => $request->SubscriberId // Assuming this should be updated
                ]);
                if ($existingEvent) {
                    return response()->json([
                        'status' => 200,
                        'message' => 'Event Removed From Favorite List',
                        'data' => $existingEvent
                    ], 200);
                } else {
                    return response()->json([
                        'status' => 500,
                        'message' => 'Failed To Removed From Favorite List'
                    ], 500);
                }
            } else if ($FavOrNot === "1") {

                $existingEvent->update([

                    'isEventFav' => $FavOrNot
                    // 'UpdatedBy' => $request->SubscriberId // Assuming this should be updated
                ]);
                if ($existingEvent) {
                    return response()->json([
                        'status' => 200,
                        'message' => 'Event Updated As Favorite',
                        'data' => $existingEvent
                    ], 200);
                } else {
                    return response()->json([
                        'status' => 500,
                        'message' => 'Failed to update Event As Favorite'
                    ], 500);
                }
            } else {
                return response()->json([
                    'status' => 503,
                    'message' => 'Here You Can Only Make Events Un Favorite'
                ], 503);
            }

        }

    }

    public function destroy($id)
    {
        // Find the request by its ID
        $existingRequest = RequestModel::find($id);

        // Check if the request exists
        if (!$existingRequest) {
            return response()->json([
                'status' => 404,
                'message' => 'Request not found'
            ], 404);
        }

        // Attempt to delete the request
        if ($existingRequest->delete()) {
            return response()->json([
                'status' => 200,
                'message' => 'Request deleted successfully'
            ], 200);
        } else {
            return response()->json([
                'status' => 500,
                'message' => 'Failed to delete request'
            ], 500);
        }
    }
    


}
