<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\subscriberlogins;
use Illuminate\Http\Request;

class appDevicehangleController extends Controller
{
    //
    public function show()
    {
        $appDevices = subscriberlogins::pluck('DeviceId')->filter()->values();

        return response()->json([
            'status' => 200,
            'data' => $appDevices
        ], 200);
    }

    public function DeviceValidate(Request $request)
    {
        $deviceId = $request->DeviceId;
        $deviceIdVerification = subscriberlogins::where('DeviceId', $deviceId)->get();
        if ($deviceIdVerification->count() > 0) {
            return response()->json([
                'status' => 200,
                'data' => $deviceIdVerification
            ], 200);
        } else {
            return response()->json([
                'status' => 201,
                'message' => 'Welcome to KidConnect',
                'code' => 99999
            ], 201);
        }

    }

}
