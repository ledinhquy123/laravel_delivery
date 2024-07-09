<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\DriverController as DriveAuth;
use App\Http\Controllers\Auth\UserController as UserAuth;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\Driver\DriverController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\User\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->name('auth.')->group(function() {
    Route::post('login', [AuthController::class, 'login']);

    Route::prefix('users')->name('users.')->controller(UserAuth::class)->group(function() {
        Route::post('register', 'register');

        Route::post('logout', 'logout')->middleware('auth:user_jwt');
    });

    Route::prefix('drivers')->name('drivers.')->controller(DriveAuth::class)->group(function() {
        Route::post('register', 'register');

        Route::post('logout', 'logout')->middleware('auth:driver_jwt');
    });
});

Route::prefix('users')->name('users.')->middleware('auth:user_jwt')->group(function() {
    Route::get('/income-statistic-user', [OrderController::class, 'incomeStatisticUser']);

    Route::prefix('orders')->controller(OrderController::class) ->group(function() {
        Route::get('/', 'index');

        Route::post('/', 'store');

        Route::put('/{order}', 'driverRate');
        
        Route::get('/{id}', 'show');

    });

    Route::controller(UserController::class)->group(function() {
        Route::put('/{user}', 'update');

    });

    Route::prefix('drivers')->controller(DriverController::class)->group(function() {
        Route::get('/', 'index');
    });

    Route::prefix('chats')->controller(ChatController::class)->group(function() {
        Route::post('/', 'store');
    });
});

Route::prefix('drivers')->name('drivers.')->middleware('auth:driver_jwt')->group(function() {
    Route::prefix('orders')->controller(OrderController::class)->group(function() {
        Route::get('/', 'index');

        Route::put('/{order}', 'update');

        Route::get('income-statistic', 'incomeStatistic');

        Route::post('update-driver-location', 'updateDriverLocation');
    });

    Route::controller(DriverController::class)->group(function() {
        Route::put('/{driver}','update');

    });

    Route::prefix('chats')->controller(ChatController::class)->group(function() {
        Route::post('/', 'store');
    });
});

Route::prefix('notifications')->controller(NotificationController::class)->name('notifications.')->group(function() {
    Route::get('/', 'index');
});

Route::prefix('chats')->controller(ChatController::class)->group(function() {
    Route::get('/', 'index');
});