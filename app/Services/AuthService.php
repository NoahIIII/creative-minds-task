<?php

namespace App\Services;

use App\Models\FcmToken;
use App\Models\OTP;
use App\Models\User;
use App\Traits\ApiResponseTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Intervention\Image\Laravel\Facades\Image;
use Intervention\Image\ImageManager;

class AuthService
{

    /**
     * store new user
     * @param $data
     */
    public function handleRegisterData($data)
    {
        $userData = array_merge($data, [
            'password' => Hash::make($data['password']),
            'status' => 1,
        ]);
        //unset unnecessary data
        unset($userData['fcm_token']);
        unset($userData['device_id']);
        unset($userData['device_type']);
        // store the profile image & create thumbnail
        if (isset($data['profile_image'])) {
            $userData['profile_image'] = StorageService::storeImage($data['profile_image'], 'users/profile', 'user_');
            $userData['thumbnail_image'] = StorageService::createThumbnail($data['profile_image'], 'users/thumbnail', 'user_');
        }
        return $userData;
    }
    /**
     * send verification code
     */
    public function sendVerificationCode($phone)
    {
        // Check if the OTP was sent in the last minute
        $otp = OTP::where('phone', $phone)->first();
        if ($otp) {
            if (Carbon::parse($otp->created_at)->gt(now()->subMinute())) {
                return ApiResponseTrait::errorResponse('You can only request one OTP per minute. Please wait a moment and try again.');
            } else {
                $otp->delete();
            }
        }

        // send the code
        try {
            $to = '+' . $phone;
            // generate the otp
            $code = rand(1000, 9999);
            // store the otp in the database
            OTP::create([
                'phone' => $phone,
                'code' => Hash::make($code),
                'expires_at' => now()->addMinutes(5)
            ]);
            $twilio = new TwilioService();
            $twilio->sendOTP($to, $code);
        } catch (\Throwable $e) {
            Log::error('OTP generation failed: ' . $e->getMessage());
            throw new \Exception('An error occurred while sending the OTP.');
        }
    }
    /**
     * verify otp
     */
    public function verifyOTP($phone, $code)
    {
        $user = User::where('phone', $phone)
            ->first();
        if (!$user) {
            return ApiResponseTrait::errorResponse(__('messages.not-found'), 404);
        }
        $otp = OTP::where('phone', $user->phone)->latest()->first();

        if (!$otp || !Hash::check($code, $otp->code)) {
            return ApiResponseTrait::errorResponse(__('messages.invalid-otp'), 400);
        }

        // $otp->expires_at is a string, convert it to a Carbon instance
        $expiresAt = Carbon::parse($otp->expires_at);

        // Check if the OTP is expired
        if ($expiresAt->isBefore(now())) {
            return ApiResponseTrait::errorResponse(__('messages.expired-otp'), 400);
        }

        // delete the otp
        $otp->delete();

        // verify user phone number
        $user->update(['phone_verified_at' => now()]);

        return $user;
    }

    /**
     * apply rate limit
     */
    public function applyRateLimit($request)
    {
        $key = $request->ip();
        $maxAttempts = 5;
        $decaySeconds = env('RATE_LIMIT_DECAY_SECONDS', 60);
        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            return ApiResponseTrait::errorResponse(
                __('auth.too_many_requests'),
                429,
                ['retry_after' => RateLimiter::availableIn($key)]
            );
        }
        RateLimiter::hit($key, $decaySeconds);
    }
    /**
     * handle store user data
     * @param $request
     * @param $userData
     */
    public function handleStoreUserData($request, $userData)
    {
        // handle image
        if ($request->hasFile('profile_image')) {
            $userData['profile_image'] = StorageService::storeImage($request->file('profile_image'), 'users/profile', 'user_');
            $userData['thumbnail_image'] = StorageService::createThumbnail($request->file('profile_image'), 'users/thumbnail', 'user_');
        }

        // phone verification
        $userData['phone_verified_at'] = $request->phone_verified ? now() : null;
        unset($userData['phone_verified']);

        // hash the password
        $userData['password'] = Hash::make($request->password);

        return $userData;
    }
    /**
     * handle update user data
     */
    public function handleUpdateUserData($request, $userData, $user)
    {
        // hash the password
        $userData['password'] = $request->filled('password') ? Hash::make($request->password) : $user->password;

        // handle images
        if ($request->hasFile('profile_image')) {
            // delete the old image
            if (!empty($user->profile_image)) {
                StorageService::deleteImage($user->profile_image);
            }
            if (!empty($user->thumbnail_image)) {
                StorageService::deleteImage($user->thumbnail_image);
            }
            // store the new image
            $userData['profile_image'] = StorageService::storeImage($request->file('profile_image'), 'users/profile', 'user_');
            $userData['thumbnail_image'] = StorageService::createThumbnail($request->file('profile_image'), 'users/thumbnail', 'user_');
        }

        // phone verification
        if ($request->phone_verified) {
            $userData['phone_verified_at'] = $user->phone_verified_at ?? now();
        } else {
            $userData['phone_verified_at'] = null;
        }

        unset($userData['phone_verified']);

        // return user data
        return $userData;
    }
}
