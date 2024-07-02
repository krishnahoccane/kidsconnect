<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

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

    public function sendNotification($deviceToken, $title, $body)
    {
        $notification = Notification::create($title, $body);
        $message = CloudMessage::withTarget('token', $deviceToken)
            ->withNotification($notification);

        try {
            $this->messaging->send($message);
        } catch (\Exception $e) {
            // Log the exception for debugging
            \Log::error('Error sending FCM notification: ' . $e->getMessage());
            throw $e;
        }
    }
}
