<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use App\Models\FcmModel;

class FirebaseService
{
    protected $messaging;

    public function __construct()
    {
        $serviceAccountPath = storage_path('app/kidsconnect-4d47e-firebase-adminsdk-pvydj-9f6f769f93.json');
        $firebase = (new Factory)
            ->withServiceAccount($serviceAccountPath);

        $this->messaging = $firebase->createMessaging();
    }

    // public function sendNotification($deviceToken, $title, $body)
    // {
    //     $notification = Notification::create($title, $body);
    //     $message = CloudMessage::withTarget('token', $deviceToken)
    //         ->withNotification($notification);

    //     try {
    //         $this->messaging->send($message);
    //     } catch (\Exception $e) {
    //         // Log the exception for debugging
    //         \Log::error('Error sending FCM notification: ' . $e->getMessage());
    //         throw $e;
    //     }
    // }

    public function sendNotificationToMultipleDevices($userId, $title, $body)
    {
        // Fetch all FCM tokens for the user
        $tokens = FcmModel::where('subscriberId', $userId)->pluck('fcm_token')->toArray();

        // Create the notification
        $notification = Notification::create($title, $body);

        // Send notification to each token
        foreach ($tokens as $token) {
            $message = CloudMessage::withTarget('token', $token)->withNotification($notification);

            try {
                $this->messaging->send($message);
            } catch (\Exception $e) {
                Log::error('Error sending FCM notification: ' . $e->getMessage(), [
                    'token' => $token,
                    'title' => $title,
                    'body' => $body,
                    'exception' => $e,
                ]);
            }
        }
    }

}
