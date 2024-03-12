<?php

namespace App\Http\Controllers\web;

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

        // return session('username');

        $role = subscriberlogins::find($id);

        if ($role) {

            $role->update([
                ''
            ]);

            return response()->json([
                'status' => 200,
                'message' => 'Subscriber Approved Successfully'
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'Somthing suspecious happend'
            ], 404);

        }

    }

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
