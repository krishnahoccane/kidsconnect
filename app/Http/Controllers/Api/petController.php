<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\petModel;
use App\Models\subscriberlogins;

use Illuminate\Http\Request;

class petController extends Controller
{
    //
    public function index(){
        
        $subKids = petModel::all();

        if ($subKids->count() > 0) {
            return response()->json([
                'status' => 200,
                'data' => $subKids
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No Data Found'
            ], 404);
        }
    }

    public function getKidsBySubscriberId($subscriberId)
    {
        // Retrieve kid data based on subscriber ID
        $kidMainSubId = petModel::where('MainSubscriberId', $subscriberId)->get();
        if ($kidMainSubId->isEmpty()) {
            return response()->json([
                'status' => 404,
                'message' => 'No kids found for the given subscriber ID'
            ], 404);
        }
    
        return response()->json([
            'status' => 200,
            'data' => $kidMainSubId
        ], 200);
    }

    
}
