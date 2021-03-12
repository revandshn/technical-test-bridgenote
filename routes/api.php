<?php

use App\Http\Controllers\API\Auth\AuthController;
use App\Http\Controllers\API\Auth\UserController;
use App\Http\Controllers\API\Auth\UserDetailController;
use App\Http\Middleware\Administrator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'v1'], function () {

    Route::post('signup', [AuthController::class, 'signup']);
    Route::post('signin', [AuthController::class, 'signin']);

    Route::middleware('auth:api')->group(function () {
        // Profile
        Route::get('signout', [AuthController::class, 'signout']);
        Route::get('get-profile', [UserController::class, 'profile']);
        Route::post('update-profile', [UserController::class, 'update']);

        // Administrator only
        Route::middleware([Administrator::class])->group(function () {
            // Profile
            Route::get('get-profile/{user_id}', [UserController::class, 'show']);
            Route::get('user-profile', [UserController::class, 'index']);
            Route::delete('del-profile/{user_id}', [UserController::class, 'destroy']);
            Route::post('restore-profile', [UserController::class, 'restore']);

            // Detail
            Route::post('user-detail/{user_id}', [UserDetailController::class, 'store']);
            Route::get('user-detail/{user_id}', [UserDetailController::class, 'show']);
            Route::put('user-detail/{user_id}', [UserDetailController::class, 'update']);
            Route::delete('user-detail/{user_id}', [UserDetailController::class, 'destroy']);
        });
    });
});
