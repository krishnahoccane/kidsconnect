<?php

namespace App\Http\Controllers\API;

use App\Models\RequestChat;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RequestChatController extends Controller
{
    public function index()
    {
        //
        $requsetchat = RequestChat::all();
        if ($requsetchat->count() > 0) {
            return response()->json([
                'status' => 200,
                'data' => $requsetchat
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No Data Found'
            ], 404);
        }
    }
}
