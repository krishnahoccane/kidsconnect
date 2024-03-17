<?php

namespace App\Http\Controllers\API;

use App\Models\RequestSentTo;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RequestSentController extends Controller
{
    public function index()
    {
        //
        $requsetsent = RequestSentController::all();
        if ($requsetsent->count() > 0) {
            return response()->json([
                'status' => 200,
                'data' => $requsetsent
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No Data Found'
            ], 404);
        }
    }
}
