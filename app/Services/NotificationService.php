<?php

namespace App\Services;

use App\Models\FcmToken;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Factory;

class NotificationService
{
    /**
     * handle user device fcm tokens
     * @param mixed $user
     * @param mixed $data
     */
    public function updateDeviceToken($user, $data)
    {
        $fcmToken = FcmToken::where('user_id', $user->id)
            ->where('device_id', '=', $data['device_id'])
            ->first();
        if ($fcmToken) {
            $fcmToken->update([
                'token' => $data['fcm_token'],
            ]);
        } else {
            FcmToken::create([
                'user_id' => $user->id,
                'token' => $data['fcm_token'],
                'device_id' => $data['device_id'],
                'device_type' => $data['device_type'],
            ]);
        }
    }
    /**
     * delete user device fcm token
     * @param mixed $user
     * @param mixed $device_id
     */
    public function deleteDeviceToken($user, $device_id)
    {
        $fcmToken = FcmToken::where('user_id', $user->id)
            ->where('device_id', '=', $device_id)
            ->first();
        if ($fcmToken) {
            $fcmToken->delete();
        }
    }

    /**
     * send notification to topic
     *
     * @param array $notification
     * @param string $topic
     * @return void
     */
    public static function sendToTopic($notification, $topic)
    {
        $payloads = [
            'content_available' => true,
            "mutable_content" => true,
            'priority' => 'high',
            'data' => [
                // "image" => $data['img_url'],
            ],
            'header' => [],
        ];
        $notification = array_merge($notification, ['sound' => 'default']);

        $payloads['notification'] = $notification;
        $payloads['topic'] = $topic;
        self::callFcmAPI($payloads);
    }
    /**
     * call fcm api
     * @param $payloads
     */
    private static function callFcmAPI($payloads)
    {
        $firebase = (new Factory)->withServiceAccount(__DIR__ . '/firebase_credentials.json');

        $messaging = $firebase->createMessaging();
        $message = CloudMessage::fromArray($payloads);
        try {
            $messaging->send($message);
        } catch (\Exception $e) {
            // throw error
            throw new \Exception("Error sending notification: " . $e->getMessage());
        }
    }
}
