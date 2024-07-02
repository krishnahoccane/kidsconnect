<?php 
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\FcmModel;

class fcmtokenController extends Controller
{
    public function loginToken(Request $request)
    {
        // Directly get FCM token and device ID from the request
        $fcmToken = $request->input('fcm_token');
        $deviceId = $request->input('device_id');
        $subscriberId = $request->input('subscriber_id'); // Assuming you get subscriber_id directly from request

        // Save or update the FCM token with the device ID
        FcmModel::updateOrCreate(
            ['subscriberId' => $subscriberId, 'deviceId' => $deviceId],
            ['fcm_token' => $fcmToken]
        );

        // Return a response
        return response()->json(['message' => 'FCM token saved successfully']);
    }

    // logout and delete FCM token
    public function logoutToken(Request $request)
    {
        // Get device ID and subscriber ID from the request
        $fcmToken = $request->input('fcm_token');
        $deviceId = $request->input('device_id');
        $subscriberId = $request->input('subscriber_id'); // Assuming you get subscriber_id directly from request

        // Delete the FCM token associated with the device ID and subscriber ID
        $deleted = FcmModel::where('subscriberId', $subscriberId)
                           ->where('deviceId', $deviceId)
                           ->where('fcm_token',$fcmToken )
                           ->delete();

        if ($deleted) {
            return response()->json(['message' => 'FCM token and associated record deleted successfully']);
        } else {
            return response()->json(['message' => 'No matching record found']);
        }
    }

}
