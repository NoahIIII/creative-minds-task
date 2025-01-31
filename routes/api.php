<?php

use App\Http\Controllers\Public\AuthController;
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
Route::group(['prefix'=>'auth'],function(){
    Route::post('/signup',[AuthController::class,'signUp']);
    Route::post('/signin',[AuthController::class,'login']);
    Route::post('/verify-phone',[AuthController::class,'verifyOTP']);
});
