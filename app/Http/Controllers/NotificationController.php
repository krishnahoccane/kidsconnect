<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FirebaseService;

class NotificationController extends Controller
{
    protected $firebaseService;

    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }

    public function sendFirebaseNotification(Request $request)
    {
        $deviceToken = $request->input('device_token');
        $title = $request->input('title');
        $body = $request->input('body');

        try {
            $this->firebaseService->sendNotification($deviceToken, $title, $body);
            return response()->json(['message' => 'Notification sent successfully.']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to send notification.', 'error' => $e->getMessage()], 500);
        }
    }
}
