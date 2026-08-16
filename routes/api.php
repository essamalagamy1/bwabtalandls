<?php

use App\Http\Controllers\Api\DataController;
use App\Http\Controllers\Api\GuestController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\ProfileController;
use Illuminate\Support\Facades\Route;

// guest routes
Route::controller(GuestController::class)->middleware('throttle:10,1')->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
    Route::post('/social-login', 'socialLogin');
    Route::post('/send/code', 'sendCode')->middleware('throttle:5,1');
    Route::post('/verify-code', 'verifyCode')->middleware('throttle:5,1');
    Route::post('/reset/password', 'resetPassword');
});

// data routes
Route::controller(DataController::class)->group(function () {
    Route::get('/categories', 'categories');
    Route::get('/sub-categories', 'subCategories');
});

Route::middleware('auth:sanctum')->group(function () {
    // notifications
    Route::prefix('notifications')->controller(NotificationController::class)->group(function () {
        Route::get('/', 'index');
        Route::put('/read', 'read');
        Route::put('/read-all', 'readAll');
        Route::delete('/delete', 'delete');
        Route::get('/unread/count', 'unreadNotificationCount');
        Route::put('/disable', 'disable');
    });

    // profile
    Route::prefix('profile')->controller(ProfileController::class)->group(function () {
        Route::get('/', 'index');
        Route::put('/update', 'update');
        Route::put('/update-password', 'updatePassword');
        Route::put('/update-language', 'updateLanguage');
        Route::post('/logout', 'logout');
        Route::delete('/delete-account', 'deleteAccount');
    });
});
