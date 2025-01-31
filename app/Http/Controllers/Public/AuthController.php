<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\Public\Auth\LoginRequest;
use App\Http\Requests\Public\Auth\RegisterRequest;
use App\Http\Requests\Public\Auth\VerifyOTPRequest;
use App\Models\User;
use App\Services\AuthService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;

class AuthController extends Controller
{
    public function __construct(private AuthService $authService) {}
    /**
     * Register a new user.
     * @param RegisterRequest $request
     */
    public function signUp(RegisterRequest $request)
    {
        // handle the request data
        $userData = $this->authService->handleRegisterData($request->validated());
        // store the user
        $user = User::create($userData);
        // verify the user phone number
        $this->authService->sendVerificationCode($user->phone);
        return ApiResponseTrait::successResponse([], __('messages.otp_sent'));
    }
    /**
     * login a user
     * @param LoginRequest $request
     */
    public function login(LoginRequest $request)
    {
        // get credentials
        $credentials = $request->validated();
        // get the user
        $user = User::where('phone', $credentials['phone'])->first();
        // check if the user exists & password is correct
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return ApiResponseTrait::errorResponse(__('auth.invalid_credentials'));
        }

        // check if the user phone number is verified
        if (!$user->phone_verified_at) {
            $sendVerificationCode = $this->authService->sendVerificationCode($user->phone);
            if ($sendVerificationCode instanceof JsonResponse) return $sendVerificationCode;
            return ApiResponseTrait::errorResponse(__('auth.phone_not_verified'));
        }

        // generate the token
        $token = auth('users')->login($user);
        return ApiResponseTrait::successResponse(['token' => $token]);
    }

    /**
     * Verify User Phone Number
     * @param Request $request
     */
    public function verifyOTP(VerifyOTPRequest $request)
    {
        // Rate Limiting
        $rateLimit = $this->authService->applyRateLimit($request);
        if ($rateLimit instanceof JsonResponse) return $rateLimit;

        // verify the otp code
        $user = $this->authService->verifyOTP($request->validated()['phone'], $request->validated()['code']);
        if ($user instanceof JsonResponse) return $user;

        // login the user
        $token = auth('users')->login($user);
        return ApiResponseTrait::successResponse(['token' => $token]);
    }
}
