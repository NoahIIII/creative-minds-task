<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\Public\Notifications\StoreFcmTokenRequest;
use App\Services\NotificationService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct(private NotificationService $notificationService) {}
    /**
     * Store/Update User Device Token
     */
    public function updateDeviceToken(StoreFcmTokenRequest $request)
    {
        $deviceTokenData = $request->validated();
        $this->notificationService->updateDeviceToken(auth('users')->user(), $deviceTokenData);
        return ApiResponseTrait::successResponse(__('messages.updated'));
    }
}
