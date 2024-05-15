<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\countrycodeModel;
use Illuminate\Http\Request;

class CountryCodeController extends Controller
{
    //

    public function index()
    {
        $countriesWithCodes = countrycodeModel::all();

        return response()->json(['satus' => 200, 'data' => $countriesWithCodes], 200);
    }
}
