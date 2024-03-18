<?php

namespace App\Http\Controllers\API;

use App\Models\RequestModel;
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
}
