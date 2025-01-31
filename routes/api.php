<?php

use App\Http\Controllers\Public\AuthController;
use App\Http\Controllers\Public\DeliveryController;
use App\Http\Controllers\Public\NotificationController;
use App\Http\Controllers\Public\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//---------------------------- User Auth Routes --------------------------------
Route::group(['prefix' => 'auth', 'middleware' => 'throttle:60,1'], function () {
    Route::post('/signup', [AuthController::class, 'signUp']);
    Route::post('/signin', [AuthController::class, 'login']);
    Route::post('/verify-phone', [AuthController::class, 'verifyOTP']);
});

//----------------------------- User Profile Routes --------------------------------
Route::group(['prefix' => 'profile', 'middleware' => 'user_authentication'], function () {
    Route::get('/', [ProfileController::class, 'getProfileData']);
});

//---------------------------- Delivery Routes --------------------------------
Route::group(['prefix' => 'delivery', 'middleware' => 'user_authentication'], function () {
    Route::get('/nearest', [DeliveryController::class, 'getNearestDeliveries']);
});
//----------------------------- Notification Routes --------------------------------
Route::group(['prefix' => 'notifications', 'middleware' => 'user_authentication'], function () {
    Route::patch('/update-token', [NotificationController::class, 'updateDeviceToken']);
});
