<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\DriverController as DriveAuth;
use App\Http\Controllers\Auth\UserController as UserAuth;
use App\Http\Controllers\Driver\DriverController;
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
    Route::prefix('orders')->controller(OrderController::class) ->group(function() {
        Route::post('/', 'store');
    });

    Route::controller(UserController::class)->group(function() {
        Route::put('/{user}', 'update');

    });
});

Route::prefix('drivers')->name('drivers.')->middleware('auth:driver_jwt')->group(function() {
    Route::prefix('orders')->controller(OrderController::class)->group(function() {
        Route::put('/{order}', 'update');
    });

    Route::controller(DriverController::class)->group(function() {
        Route::put('/{driver}','update');

    });

});