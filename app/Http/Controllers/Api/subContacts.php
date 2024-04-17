<?php

namespace App\Http\Controllers\Api;

use App\Models\subscriberlogins;
use App\Models\subscribersKidModel;
use Illuminate\Http\Request;
use App\Models\defaultStatus;
use App\Http\Controllers\Controller;
use App\Models\subScriberContactModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class subContacts extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id = null)
    {

        if ($id !== null) {
            // Handle requests with specific ID
            try {
                $query = subScriberContactModel::where('subscriberId', $id)->where('status', "4")->get();

                $responseData = [];
                foreach ($query as $contact) {
                    $contactedId = $contact->contactedId;

                    // Fetch kid data for each contactedId
                    $kidData = subscribersKidModel::where('id', $contactedId)->get()->toArray();

                    // Fetch main subscriber data for each kid
                    $mainSubscriberIDs = [];
                    foreach ($kidData as $kid) {
                        $mainSubscriberIDs[] = $kid['MainSubscriberId'];
                    }

                    // $mainData = subscriberlogins::whereIn('MainSubscriberId', $mainSubscriberIDs)->get()->toArray();

                    // Assemble the response data
                    // $responseData[] = [
                    //     'id' => $contact->id,
                    //     'contactedId' => $contactedId,
                    //     'kidData' => $kidData, // printing the data in array of object - Kids data
                    //     // 'mainData' => $mainData, // printing the data in array of object - Parents data
                    //     'status' => $contact->status,
                    //     'created_at' => $contact->created_at,
                    //     'updated_at' => $contact->updated_at,
                    // ];
                }

                return response()->json([
                    'status' => 200,
                    'data' => $kidData
                ], 200);
            } catch (\Exception $e) {
                // Handle exceptions
                return response()->json([
                    'status' => 500,
                    'message' => 'Error: ' . $e->getMessage()
                ], 500);
            }

        } else {

            $allcontacts = subScriberContactModel::all();

            if ($allcontacts->count() > 0) {
                $responseData = [];

                foreach ($allcontacts as $contact) {

                    $status = $contact->status;

                    $statusName = defaultStatus::where('id', $status)->value('name');

                    $responseData[] = [
                        'id' => $contact->id,
                        'subscriberId' => $contact->subscriberId,
                        'contactedId' => $contact->contactedId,
                        'status' => $statusName,
                        'created_at' => $contact->created_at,
                        'updated_at' => $contact->updated_at,
                    ];
                }

                return response()->json($responseData);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => "allcontacts table is empty"
                ], 404);
            }
        }





    }


    // Getting SubscriberId - Contacted Data

    public function getSubContactedData($subscriberId)
    {


        $subsciberContactedTo = subScriberContactModel::where('subscriberId', $subscriberId)->get();

        if ($subsciberContactedTo->count() > 0) {
            $responseData = [];

            foreach ($subsciberContactedTo as $contact) {

                $status = $contact->status;

                $statusName = defaultStatus::where('id', $status)->value('name');

                $responseData[] = [
                    'id' => $contact->id,
                    'subscriberId' => $contact->subscriberId,
                    'contactedId' => $contact->contactedId,
                    'status' => $statusName,
                    'created_at' => $contact->created_at,
                    'updated_at' => $contact->updated_at,
                ];
            }

            return response()->json($responseData);
        } else {
            return response()->json([
                'status' => 404,
                'message' => "allcontacts table is empty"
            ], 404);
        }
    }


    // Getting ContactedID - to find out how many subscribers have contacted them


    public function getContactedData($contactedId, $id = null)
    {
        if ($id !== null) {
            try {
                //**findOrFail()** is used here to check whether the given id is avialable or not
                //because we are writing 2 conditions is 1-contactedId and 2-Id of subscriber_contacts table to find out perticular subContact id and get the info - comment [vishal]

                $contactedBySubscribers = subScriberContactModel::findOrFail($id);
            } catch (ModelNotFoundException $e) {
                return response()->json([
                    'status' => 404,
                    'message' => "Given Id is not available"
                ], 404);
            }

            $statusId = $contactedBySubscribers->status;
            $statusName = defaultStatus::where('id', $statusId)->value('name');

            $contactedBySubscribers->status = $statusName;

            return response()->json([
                'status' => 200,
                'message' => $contactedBySubscribers
            ], 200);
        } else {
            $contactedBySubscribers = subScriberContactModel::where('contactedId', $contactedId)->get();
            $kidsData = subscribersKidModel::find($contactedId);
            if ($contactedBySubscribers->isEmpty()) {
                return response()->json([
                    'status' => 404,
                    'message' => "No records found for the provided contactedId"
                ], 404);
            }

            $responseData = [];

            foreach ($contactedBySubscribers as $contact) {
                $statusName = defaultStatus::where('id', $contact->status)->value('name');
                $mainsubscriberId = $kidsData['MainSubscriberId'];
                $mainData = subscriberlogins::where('MainSubscriberId', $mainsubscriberId)->get();

                $responseData[] = [
                    'id' => $contact->id,
                    'subscriberId' => $contact->subscriberId,
                    'contactedId' => $contact->contactedId,
                    'status' => $statusName,
                    'created_at' => $contact->created_at,
                    'updated_at' => $contact->updated_at,
                    'KidName' => $kidsData['FirstName'],
                    'MainData' => $mainData
                ];
            }
            // 

            // return response()->json([
            //     'status' => 200,
            //     'message' => $kidsData
            // ], 200);

            return response()->json($responseData);
        }
    }










    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $requestData = $request->all();

        $subscriberContactData = subScriberContactModel::create($requestData);

        if ($subscriberContactData) {
            return response()->json([
                'status' => 200,
                'message' => 'Contact added susccessfully waiting for approval'
            ], 200);
        } else {
            return response()->json([
                'status' => 403,
                'message' => "Data is not added"
            ], 403);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
