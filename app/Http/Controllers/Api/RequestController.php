<?php

namespace App\Http\Controllers\API;

use App\Models\RequestModel;
use App\Models\defaultStatus;
use App\Models\subscriberlogins;
use App\Models\subscribersModel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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

        // Create a new request instance with the provided data
        $newRequest = RequestModel::create([
            'Subject' => $request->Subject,
            'SubscriberId' => $subscriberId,
            'SubschildId' => $request->SubschildId,
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


}
