<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\subscriberlogins;
use App\Models\subscribersModel;
use App\Http\Controllers\Controller;

class subscriberController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        // comment - vishal - Here pluk is used to get perticular column of table and toArray() used to fetch in array form,
        // After fetching the id from subscriber table passing that into the subscriberlogin table and fetching sepecfic condition
        // MainsubscriberId is Subscriber table id & IsMain 1 - Selected fields are Id, FName, LName, Email, RID, CreatedDate

        $subscriberIds = subscribersModel::pluck('id')->toArray();
        $subscriberLoginData = subscriberlogins::whereIn('MainSubscriberId', $subscriberIds)->where('IsMain', 1)
            ->select('id', 'FirstName', 'LastName', 'Email', 'RoleId', 'created_at')
            ->get();

        if ($subscriberLoginData->count() > 0) {
            return response()->json([
                'status' => 200,
                'data' => $subscriberLoginData
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No Data Found'
            ], 404);
        }

    }

    /**
     * Store a newly created resource in storage.
     */
    public function create(Request $request)
    {
        //
        $subscriber = subscribersModel::firstOrCreate([
            'RoleId' => $request->RoleId,
            // 'ProfileStatus' => $request->ProfileStatus,
            // 'ApprovedOn' => $request->ApprovedOn,
            // 'ApprovedBy' => $request->ApprovedBy,
            // 'DeniedOn' => $request->DeniedOn,
            // 'DeniedBy' => $request->DeniedBy
        ]);

        if ($subscriber->wasRecentlyCreated) {
            return response()->json([
                'status' => 200,
                'message' => 'Subscriber created succsessfully',
                'data' => $subscriber
            ], 200);
        } else {
            return response()->json([
                'status' => 403,
                'message' => 'Subscriber already existed'
            ], 403);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
