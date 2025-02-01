<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Notifications\SendNotificationRequest;
use App\Models\User;
use App\Notifications\NotificationSender;
use App\Services\NotificationService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class NotificationController extends Controller
{
    /**
     * return create notification form
     */
    public function create()
    {
        return view('notifications.create');
    }
    /**
     * send notification
     * @param SendNotificationRequest $request
     */
    public function send(SendNotificationRequest $request)
    {
        try {
            // Get the validated notification data
            $notificationData = $request->only(['title', 'body']);

            // Retrieve users based on topic
            $usersQuery = match ($request->topic) {
                'all-users' => User::query(),
                'deliveries' => User::delivery(),
                'users' => User::where('user_type', 'user'),
                default => User::query(),
            };

            $users = $usersQuery->get();

            if ($users->isEmpty()) {
                return ApiResponseTrait::errorResponse('No users found for the specified topic.');
            }

            // store notifications
            Notification::send($users, new NotificationSender($notificationData));
            // send firebase notifications
            NotificationService::sendToTopic($notificationData, $request->topic);

            return ApiResponseTrait::successResponse(null,'Notification sent successfully.');
        } catch (\Throwable $th) {
            Log::error('Notification Error: ' . $th->getMessage());
            return ApiResponseTrait::errorResponse('Error sending notification, please try again later.');
        }
    }
}
