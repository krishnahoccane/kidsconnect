<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\subscriberlogins;
use App\Models\subscribersModel;
use App\Http\Controllers\Controller;

class subscriberController extends Controller
{
   
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

}
