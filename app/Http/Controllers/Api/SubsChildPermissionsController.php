<?php

namespace App\Http\Controllers\API;

use App\Models\SubsChildPermission;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SubsChildPermissionsController extends Controller
{
    public function index()
    {
        //
        $subpermission = SubsChildPermission::all();
        if ($subpermission->count() > 0) {
            return response()->json([
                'status' => 200,
                'data' => $subpermission
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No Data Found'
            ], 404);
        }
    }
}
