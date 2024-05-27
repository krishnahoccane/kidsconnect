<?php

namespace App\Http\Controllers\API;

use App\Models\RequestModel;
use App\Models\defaultStatus;
use App\Models\subscriberlogins;
use App\Models\subscribersModel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;


class RequestController extends Controller
{
    public function index()
    {
        //
        $requset = RequestModel::all();
        if ($requset->count() > 0) {
            return response()->json([
                'status' => 200,
                'data' => $requset
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
            'Subject' => $request->Subject,
            'SubscriberId' => $subscriberId,
            'SubscribersKidId' => $request->SubschildId,
            'RequestFor' => $request->RequestFor,
            'EventFrom' => $request->EventFrom,
            'EventTo' => $request->EventTo,
            'Keywords' => $request->Keywords,
            'RecordType' => $status->name, // Assuming you want to use the name of the status
            'Statusid' => $status->id, // Assuming you also want to store the status ID
            'LocationType' => $request->LocationType,
            'Location' => $request->Location,
            'PickDropInfo' => $request->PickDropInfo,
            'SpecialNotes' => $request->SpecialNotes,
            'PrimaryResponsibleId' => $request->PrimaryResponsibleId,
            'ActivityType' => $request->ActivityType,
            'areGroupMemberVisible' => $request->areGroupMemberVisible,
            'IsGroupChat' => $request->IsGroupChat,
            'CreatedBy' => $subscriberId,
            'UpdatedBy' => $subscriberId
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


    // public function show($id)
    // {
    //     // Find the request by its ID
    //     $request = RequestModel::find($id);
    //     $arr = [];
    //     $keywords = $request['Keywords'];

    //     array_push($arr, $keywords);

    //     $request['Keywords'] = $arr;

    //     array_push($arr, $request['Keywords']);

    //     // Check if the request exists
    //     if (!$request) {
    //         return response()->json([
    //             'status' => 404,
    //             'message' => 'Request not found'
    //         ], 404);

    //     }

    //     // Return the response with the request data
    //     return response()->json([
    //         'status' => 200,
    //         'message' => 'Request found',
    //         'data' => $request
    //     ], 200);
    // }

    public function show($id)
    {
        // Find the request by its ID
        $request = RequestModel::find($id);

        // Check if the request exists
        if (!$request) {
            return response()->json([
                'status' => 404,
                'message' => 'Request not found'
            ], 404);
        }

        // Initialize the array to hold keywords
        $arr = [];

        // Check if Keywords field exists and is not null
        if (!is_null($request->Keywords)) {
            $keywords = $request->Keywords;
            array_push($arr, $keywords);
            $request->Keywords = $arr;
        } else {
            $request->Keywords = $arr; // Assign empty array if Keywords is null
        }

        // Return the response with the request data
        return response()->json([
            'status' => 200,
            'message' => 'Request found',
            'data' => $request
        ], 200);
    }

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
            'Subject' => $request->Subject,
            'SubscriberId' => $request->SubscriberId,
            'SubschildId' => $request->SubschildId,
            'RequestFor' => $request->RequestFor,
            'EventFrom' => $request->EventFrom,
            'EventTo' => $request->EventTo,
            'Keywords' => $request->Keywords,
            'RecordType' => $status->name,
            'Statusid' => $status->id,
            'LocationType' => $request->LocationType,
            'Location' => $request->Location,
            'PickDropInfo' => $request->PickDropInfo,
            'SpecialNotes' => $request->SpecialNotes,
            'PrimaryResponsibleId' => $request->PrimaryResponsibleId,
            'ActivityType' => $request->ActivityType,
            'areGroupMemberVisible' => $request->areGroupMemberVisible,
            'IsGroupChat' => $request->IsGroupChat,
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

    public function previousevent($subscriberId)
    {
        // Fetch the latest request for the given SubscriberId where Statusid is '6', ordered by creation date
        $completedRequest = RequestModel::where('SubscriberId', $subscriberId)
            ->where('Statusid', 6)
            ->latest()
            ->first();

        // Check if a completed request was found
        if ($completedRequest) {
            return response()->json([
                'status' => 200,
                'data' => $completedRequest
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No Completed Request Found for this Subscriber'
            ], 404);
        }
    }

    public function activeEvent($subscriberId)
    {
        // Get the current time
        $currentTime = now()->toTimeString();

        // Fetch the latest active event for the given SubscriberId
        $activeEvent = RequestModel::where('SubscriberId', $subscriberId)
            ->where('Statusid', 1)
            ->whereRaw('? BETWEEN TIME(EventStartTime) AND TIME(EventEndTime)', [$currentTime]) // Check if the current time is between the event's start and end time
            ->latest()
            ->first();

        // Check if an active event was found
        if ($activeEvent) {
            return response()->json([
                'status' => 200,
                'data' => $activeEvent
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No Active Event Found for this Subscriber'
            ], 404);
        }
    }


    public function updateToFav(Request $request, $event_id)
    {
        // Find the existingEvent by its ID
        $existingEvent = RequestModel::find($event_id);

        // Check if the Event exists
        if (!$existingEvent) {
            return response()->json([
                'status' => 404,
                'message' => 'Request not found'
            ], 404);
        } else {


            $existingEvent->update([

                'isEventFav' => $request->isEventFav,
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
        }

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




}
