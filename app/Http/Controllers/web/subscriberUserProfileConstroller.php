<?php

namespace App\Http\Controllers\web;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\subscriberlogins;
use App\Http\Controllers\Controller;

class subscriberUserProfileConstroller extends Controller
{
    //
    public function show($id)
    {

        $sub_login = subscriberlogins::find($id);


        $sub_login_array = $sub_login->toArray();

        // Pass the data to the view
        return view('users/userProfile', ['sub_login' => $sub_login_array]);


    }


    public function approve(Request $request, int $id)
    {
        $subscriber = subscriberlogins::find($id);

        if (!$subscriber) {
            return response()->json([
                'status' => 404,
                'message' => 'Subscriber not found'
            ], 404);
        }

        $subscriber->update([
            'ProfileStatus'=>"1",
            'IsApproved' => "1",
            'ApprovedOn' => Carbon::now(),
            'ApprovedBy' => session('username')
        ]);

        // return back()->with('success','Subscriber Approved Successfully');

        return response()->json([
            'status' => 200,
            'message' => 'Subscriber Approved Successfully'
        ], 200);
    }

    public function deny(Request $request, int $id)
    {
        $subscriber = subscriberlogins::find($id);

        if (!$subscriber) {
            return response()->json([
                'status' => 404,
                'message' => 'Subscriber not found'
            ], 404);
        }

        $subscriber->update([
            'ProfileStatus'=>"0",
            'IsApproved' => "0",
            'ApprovedOn' => Carbon::now(),
            'ApprovedBy' => session('username')
        ]);

        return response()->json([
            'status' => 200,
            'message' => 'Subscriber Denied Successfully'
        ], 200);
    }

    //     return back()->with('success','Subscriber Approved Successfully');
    // } else {
    //     return back()->with('error','Subscriber Not Approved');
    // }

    // public function delete(Request $request, int $id)
    // {

    //     $sub_login = subscriberlogins::find($id);

    //     if (!$sub_login) {
    //         return response()->json([
    //             'status' => 403,
    //             'message' => "Requested Id Data NotFound"
    //         ], 403);
    //     }

    //     $sub_login->delete();

    //     return response()->json(['message' => 'Subscriber deleted successfully'], 200);

    // }
}
