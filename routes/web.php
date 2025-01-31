<?php

use App\Http\Controllers\Dashboard\StaffUserAuthController;
use App\Http\Controllers\Dashboard\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/




    //-----------------------------------Auth Routes ----------------------------------------------------------------------
    Route::view('/login', 'auth.login')->name('login.view');
    Route::post('/login', [StaffUserAuthController::class, 'login'])->name('login');
    Route::post('/logout', [StaffUserAuthController::class, 'logout'])->name('logout');

    //--------------------------------Dashboard Routes -------------------------------------------------------------
    // Route::group(['middleware' => 'auth:staff_users'], function () {

        Route::view('/','index')->name('dashboard.index');
        //-------------------------- Manage Users Routes -----------------------------
        Route::group(['prefix' => 'users'], function () {
            Route::get('/', [UserController::class, 'index'])
                ->name('users.index');
            Route::get('/create', [UserController::class, 'create'])
                ->name('users.create');
            Route::post('/store', [UserController::class, 'store'])
                ->name('users.store');
            Route::get('/edit/{userId}', [UserController::class, 'edit'])
                ->name('users.edit');
            // Route::get('/{userId}', [UserController::class, 'show'])
            //     ->name('users.show');
            Route::put('/update/{user}', [UserController::class, 'update'])
                ->name('users.update');
            Route::delete('/destroy/{user}', [UserController::class, 'destroy'])
                ->name('users.destroy');
        });

    // });

