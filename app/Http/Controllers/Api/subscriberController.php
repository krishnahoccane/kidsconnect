<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\subscribersModel;
use App\Http\Controllers\Controller;

class subscriberController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        //
        $subscriber = subscribersModel::all();
        if ($subscriber->count() > 0) {
            return response()->json([
                'status' => 200,
                'data' => $subscriber
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
