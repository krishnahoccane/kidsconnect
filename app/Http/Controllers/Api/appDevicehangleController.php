<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\subscriberlogins;
use Illuminate\Http\Request;

class appDevicehangleController extends Controller
{
    //
    public function show(){
        $appDevices = subscriberlogins::pluck('DeviceId')->filter()->values();

        return response()->json([
            'status'=>200,
            'data'=>$appDevices
        ],200);
    }
}
