<?php
namespace App\Services;

use App\Models\FcmToken;

class NotificationService
{
 /**
     * handle user device fcm tokens
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
}
