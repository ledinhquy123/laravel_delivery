<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::controller(AdminController::class)->name('admin-auth.')->group(function() {
    Route::get('/login', 'login')->name('login');

    Route::post('/login', 'handleLogin')->name('handle-login');
});

Route::prefix('admin/')->name('admin.')->controller(AdminController::class)->group(function() {
    Route::get('/', 'dashboard')->name('dashboard');

    Route::prefix('users')->name('users.')->group(function() {
        Route::get('/', 'users')->name('user-list');
        
        Route::get('/create', 'createUser')->name('user-create');
        Route::post('/create', 'handleCreateUser')->name('handle-user-create');
        Route::get('/delete/{user}', 'deleteUser')->name('delete-user');
        
        Route::get('/{user}', 'updateUser')->name('user-update');
        Route::post('/{user}', 'handleUpdateUser')->name('handle-user-update');
    });

    Route::prefix('drivers/')->name('drivers.')->group(function() {
        Route::get('/', 'drivers')->name('driver-list'); 

        // Route::get('/{driver}', 'updateDriver')->name('driver-update');
        // Route::post('/{driver}', 'handleUpdateDriver')->name('handle-driver-update');

        Route::get('/create', 'createDriver')->name('driver-create');
        Route::post('/create', 'handleCreateDriver')->name('handle-driver-create');
        Route::get('/delete/{driver}', 'deleteDriver')->name('delete-driver');
    });

    Route::prefix('orders/')->name('orders.')->group(function() {
        Route::get('/', 'orders')->name('order-list'); 

        Route::get('/{order}', 'updateOrder')->name('order-update');
        Route::post('/{order}', 'handleUpdateOrder')->name('handle-order-update');

        // Route::get('/delete/{orderStatus}', 'deleteOrderStatus')->name('delete-order-status');
    });    

    Route::prefix('order-status/')->name('order-status.')->group(function() {
        Route::get('/', 'orderStatus')->name('order-status-list'); 

        // Route::get('/{orderStatus}', 'updateOrderStatus')->name('order-status-update');
        // Route::post('/{orderStatus}', 'handleUpdateOrderStatus')->name('handle-order-status-update');

        Route::get('/create', 'createOrderStatus')->name('order-status-create');
        Route::post('/create', 'handleCreateOrderStatus')->name('handle-order-status-create');
        Route::get('/delete/{orderStatus}', 'deleteOrderStatus')->name('delete-order-status');
    });
});