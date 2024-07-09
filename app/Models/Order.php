<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        "user_id",
        "driver_id",
        "items",
        "from_address",
        "to_address",
        "shipping_cost",
        "order_status_id",
        "driver_accept_at",
        "complete_at",
        "user_note",
        "receiver",
        "driver_rate",
        "distance"
    ];

    public function driver() {
        return $this->belongsTo(Driver::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function orderStatus() {
        return $this->belongsTo(OrderStatus::class);
    }

    public function messageDrivers() {
        return $this->hasMany(MessagesDriver::class);
    }

    public function messageUsers() {
        return $this->hasMany(MessagesUser::class);
    }
}