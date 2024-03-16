<?php

namespace App\Http\Controllers\API;

    use App\Models\SubsCirclesMember;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SubsCirclesMemberController extends Controller
{
    public function index()
    {
        //
        $submember= SubsCirclesMember::all();
        if ($submember->count() > 0) {
            return response()->json([
                'status' => 200,
                'data' => $submember
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No Data Found'
            ], 404);
        }
    }
}
