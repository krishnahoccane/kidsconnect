<?php

namespace App\Http\Controllers\API;

use App\Models\SubsCircles;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SubsCirclesController extends Controller
{
    public function index()
    {
        //
        $subcircles = SubsCircles::all();
        if ($subcircles->count() > 0) {
            return response()->json([
                'status' => 200,
                'data' => $subcircles
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No Data Found'
            ], 404);
        }
    }
}
