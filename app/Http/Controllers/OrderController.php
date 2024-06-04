<?php

namespace App\Http\Controllers;

use App\Events\PlaceAnOrder;
use App\Http\Requests\OrderStoreRequest;
use App\Http\Requests\User\OrderUpdateRequest;
use App\Models\Driver;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    private $noti;

    public function __construct(NotificationController $noti) {
        $this->noti = $noti;
    }

    // "2024-05-18T06:04:55.878094Z"
    public function store(OrderStoreRequest $request) {
        $data = $request->validated();

        if(!isset($data['driver_id'])) {
            $data['driver_id'] = 1;
        }
        $order = Order::create($data);
        $order->load('user');
        $order->load('driver');
        $order->load('orderStatus');

        $order->user->userNotifications()->createMany([
            [
                'title' => 'Thông báo hệ thống',
                'body' => 'Bạn đặt đơn hàng thành công'
            ]
        ]);

        //TODO Send OTP code to user's device
        $this->noti->notify(
            'Thông báo hệ thống',
            'Bạn đặt đơn hàng thành công',
            $order->user->fcm_token
        );

        //TODO Send noti to receive driver
        broadcast(new PlaceAnOrder($order, 2));

        $this->noti->notify(
            'Thông báo hệ thống',
            'Bạn có đơn hàng mới',
            Driver::find(2)->fcm_token
        );

        return $this->success(
            $order,
            'The order has been placed successfully'
        );
    }

    public function update(Order $order, OrderUpdateRequest $request) {
        $data = $request->validated();
        
        $order->user_id = $data['user_id'] ?? $order->user_id;
        $order->driver_id = $data['driver_id'] ?? $order->driver_id;
        $order->items = $data['items'] ?? $order->items;
        $order->from_address = $data['from_address'] ?? $order->from_address;
        $order->to_address = $data['to_address'] ?? $order->to_address;
        $order->shipping_cost = $data['shipping_cost'] ?? $order->shipping_cost;
        $order->order_status_id = $data['order_status_id'] ?? $order->order_status_id;
        $order->driver_accept_at = $data['driver_accept_at'] ?? $order->driver_accept_at;
        $order->complete_at = $data['complete_at'] ?? $order->complete_at;
        $order->user_note = $data['user_note'] ?? $order->user_note;
        $order->receiver = $data['receiver'] ?? $order->receiver;
        $order->driver_rate = $data['driver_rate'] ?? $order->driver_rate;
        $order->distance = $data['distance'] ?? $order->distance;
        $order->save();

        $order->load('user');
        $order->load('driver');
        $order->load('orderStatus');
        
        $driver = Driver::find($order->driver_id);
        $driver->driverNotifications()->createMany([
            [
                'title' => 'Thông báo hệ thống',
                'body' => 'Bạn có đơn hàng mới'
            ]
        ]);
        User::find($order->user_id)->userNotifications()->createMany([
            [
                'title' => 'Thông báo hệ thống',
                'body' => 'Tài xế ' . $driver->name . ' đã nhận đơn hàng'
            ]
        ]);

        return $this->success(
            $order,
            'The order has been updated successfully'
        );
    }
}
