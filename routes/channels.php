<?php

use App\Models\Driver;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('place-an-order.{receive_driver_id}', function(Driver $driver, $receive_driver_id) {
    return (int) $driver->id === (int) $receive_driver_id;
}); 