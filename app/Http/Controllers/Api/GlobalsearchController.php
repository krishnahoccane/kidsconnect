<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\subscriberlogins;
use App\Models\subscribersKidModel;
use Illuminate\Http\Request;

class GlobalsearchController extends Controller
{
    //

    public function searchglobal($name, $user = 'parent')
    {
        // Initialize the result variable
        $results = [];

        // Perform search based on user type
        if ($user === 'parent') {
            // Search in Subscriberlogin's firstname column
            $results = subscriberlogins::where('FirstName', 'LIKE', '%' . $name . '%')->get();
        } elseif ($user === 'kid') {
            // Search in Subscriber_kid's Firstname column
            $results = subscribersKidModel::where('FirstName', 'LIKE', '%' . $name . '%')->get();
        }

        // Return the results as JSON
        return response()->json($results);
    }
}
