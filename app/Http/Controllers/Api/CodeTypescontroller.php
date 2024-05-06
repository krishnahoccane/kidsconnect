<?php

namespace App\Http\Controllers\Api;

use App\Models\CodeTypes;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CodeTypescontroller extends Controller
{
    //
    public function index()
    {
        $codeTypes = CodeTypes::all();
        if ($codeTypes->count() > 0) {
            return response()->json([
                'status' => 200,
                'data' => $codeTypes
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => "Data Not Available"
            ], 404);
        }

    }
}
