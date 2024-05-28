<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\RequestModel;
use Illuminate\Http\Request;

class RequestFavoriteController extends Controller
{
    //showing all requests that are favorite
    public function index()
    {

        $event_data = RequestModel::where('isEventFav', '1')->get();

        return response()->json([
            'status' => 200,
            'message' => "you are going fine",
            'data' => $event_data
        ], 200);
    }

    public function show(int $sub_id)
    {

        $event_data = RequestModel::where('SubscriberId', $sub_id)->where('isEventFav', "1")->get();

        // Check if requests_data is empty before returning
        if ($event_data->isEmpty()) {
            return response()->json([
                'status' => 404,
                'message' => 'Events are not available'
            ], 404);
        }

        // Return JSON response with success status
        return response()->json([
            'status' => 200,
            'data' => $event_data
        ], 200);
    }


    public function makeFav(Request $request, int $evn_id)
    {
        $event_id = RequestModel::find($evn_id);

        if (empty($event_id)) {
            return response()->json([
                'status' => 404,
                'message' => 'Events are not available'
            ], 404);
        } else {

            // $activeEvent = "1";

            return response()->json([
                'status' => 200,
                'data' => $event_id
            ], 200);

        }


    }
}
