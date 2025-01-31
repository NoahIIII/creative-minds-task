<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * get current logged in user profile data
     */
    public function getProfileData()
    {
        return ApiResponseTrait::successResponse(['user'=>auth('users')->user()]);
    }
}
