<?php

namespace App\Services;

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
        $userData['name'] = $data['name'];
        $userData['phone'] = $data['phone'];
        $userData['password'] = Hash::make($data['password']);
        $userData['user_type'] = $data['user_type'];
        $userData['status'] = 1;
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
    public function verifyOTP($phone,$code)
    {
        $user = User::where('phone', $phone)->first();
        if(!$user){
            return ApiResponseTrait::errorResponse(__('messages.not-found'),404);
        }
        $otp = OTP::where('phone', $user->phone)->latest()->first();

        if (!$otp || !Hash::check($code, $otp->code)) {
            return ApiResponseTrait::errorResponse(__('messages.invalid-otp'),400);
        }

        // $otp->expires_at is a string, convert it to a Carbon instance
        $expiresAt = Carbon::parse($otp->expires_at);

        // Check if the OTP is expired
        if ($expiresAt->isBefore(now())) {
            return ApiResponseTrait::errorResponse(__('messages.expired-otp'),400);
        }

        // delete the otp
        $otp->delete();

        // verify user phone number
        $user->phone_verified_at = now();
        $user->save();

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
}
