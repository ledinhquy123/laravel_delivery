<?php

namespace App\Http\Controllers;

use App\Events\PlaceAnOrder;
use App\Events\UpdateDriverLocation;
use App\Http\Requests\OrderStoreRequest;
use App\Http\Requests\OrderUpdateRequest;
use App\Http\Requests\UpdateDriverLocationRequest;
use App\Models\Driver;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    private $noti;

    public function __construct(NotificationController $noti)
    {
        $this->noti = $noti;
    }

    // Get list of orders
    public function index(Request $request)
    {
        $date = $request->input('date', Carbon::now()->toDateString());
        $id = $request->input('id');
        $guard = $request->input('guard', 'user');
        if ($guard == 'driver') {
            $orders = Order::where('driver_id', $id)
                ->whereDate('created_at', $date)
                ->with('user')
                ->with('driver')
                ->with('orderStatus')
                ->latest()
                ->get();
        } else if ($guard == 'user') {
            $orders = Order::where('user_id', $id)
                ->whereDate('created_at', $date)
                ->with('user')
                ->with('driver')
                ->with('orderStatus')
                ->latest()
                ->get();
        }

        return $this->success($orders);
    }

    // "2024-05-18T06:04:55.878094Z"
    public function store(OrderStoreRequest $request)
    {
        $data = $request->validated();
        $serverKey = $data['serverKey'];
        unset($data['serverKey']);

        if (!isset($data['driver_id'])) {
            $data['driver_id'] = 1;
        }
        $order = Order::create($data);
        $order->load('user');
        $order->load('driver');
        $order->load('orderStatus');

        if ($order->driver_id == 1) {
            $order->order_status_id = 7;
            $order->save();
            $order->user->userNotifications()->createMany([
                [
                    'title' => 'Thông báo hệ thống',
                    'body' => 'Hiện không có tài xế nhận đơn hàng'
                ]
            ]);
            //TODO Send OTP code to user's device
            $this->noti->notify(
                'Thông báo hệ thống',
                'Hiện không có tài xế nhận đơn hàng',
                $order->user->fcm_token,
                $serverKey
            );
            return $this->success(
                $order,
                'The order hasn\'t been placed'
            );
        }

        $driver = Driver::find($data['driver_id']);

        if ($driver->status) {
            //TODO Send noti to receive driver
            broadcast(new PlaceAnOrder($order, $data['driver_id']));

            $this->noti->notify(
                'Thông báo hệ thống',
                'Bạn có đơn hàng mới',
                $driver->fcm_token,
                $serverKey
            );
        }

        $order->user->userNotifications()->createMany([
            [
                'title' => 'Thông báo hệ thống',
                'body' => 'Bạn đặt đơn hàng thành công'
            ]
        ]);
        broadcast(new PlaceAnOrder($order, $data['user_id']));

        //TODO Send OTP code to user's device
        $this->noti->notify(
            'Thông báo hệ thống',
            'Bạn đặt đơn hàng thành công',
            $order->user->fcm_token,
            $serverKey
        );

        return $this->success(
            $order,
            'The order has been placed successfully'
        );
    }

    public function show($id)
    {
        $order = Order::where('id', $id)
            ->with('user')
            ->with('driver')
            ->with('orderStatus')
            ->first();
        return $this->success($order);
    }

    public function update(Order $order, OrderUpdateRequest $request)
    {
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

        $driver = Driver::find($order->driver_id);
        $user = User::find($order->user_id);
        
        if($order->order_status_id == 7) {
            $driver->driverNotifications()->createMany([
                [
                    'title' => 'Thông báo hệ thống',
                    'body' => 'Bạn đã từ chối đơn hàng của ' . $user->name
                ]
            ]);
            $user->userNotifications()->createMany([
                [
                    'title' => 'Thông báo hệ thống',
                    'body' => 'Tài xế ' . $driver->name . ' đã từ chối đơn hàng'
                ]
            ]);
            // broadcast(new PlaceAnOrder($order, $data['user_id']));
            return $this->success(
                $order,
                'The order has been updated failed'
            );
        }
        $order->load('user');
        $order->load('driver');
        $order->load('orderStatus');
        
        $driver->driverNotifications()->createMany([
            [
                'title' => 'Thông báo hệ thống',
                'body' => 'Bạn đã nhận đơn hàng của ' . $user->name
            ]
        ]);
        $user->userNotifications()->createMany([
            [
                'title' => 'Thông báo hệ thống',
                'body' => 'Tài xế ' . $driver->name . ' đã nhận đơn hàng'
            ]
        ]);
        // broadcast(new PlaceAnOrder($order, $data['user_id']));
        return $this->success(
            $order,
            'The order has been updated successfully'
        );
    }

    public function driverRate(Order $order, OrderUpdateRequest $request) {
        $data = $request->validated();
        $order->driver_rate = $data['driver_rate'] ?? $order->driver_rate;
        $order->save();
        return $this->success(
            $order,
            'The order has been updated successfully'
        );
    }

    public function incomeStatistic(Request $request)
    {
        $type = $request->input('type', 'month');
        $diverId = $request->input('id');

        $incomeTotal = 0;
        if ($type == 'month') {
            $orders = Order::where('driver_id', $diverId)->whereMonth('created_at', Carbon::now()->month)->get();
            $incomeTotal = $orders->sum('shipping_cost');
            $deliveryTotal = $orders->count();
            $moveTotal = $orders->sum('distance');

        } else if ($type == 'week') {
            $firstOfWeek = Carbon::now()->startOfWeek();
            $lastOfWeek = Carbon::now()->endOfWeek();

            $orders = Order::where('driver_id', $diverId)->whereBetween('created_at', [$firstOfWeek, $lastOfWeek])->get();
            $incomeTotal = $orders->sum('shipping_cost');
            $deliveryTotal = $orders->count();
            $moveTotal = $orders->sum('distance');
        }

        return $this->success([
            'incomeTotal' => strval($incomeTotal),
            'deliveryTotal' => strval($deliveryTotal),
            'moveTotal' => strval($moveTotal)
        ]);
    }

    public function incomeStatisticUser(Request $request)
    {
        $type = $request->input('type', 'month');
        $userId = $request->input('id');

        $incomeTotal = 0;
        if ($type == 'month') {
            $orders = Order::where('user_id', $userId)->whereMonth('created_at', Carbon::now()->month)->get();
            $incomeTotal = $orders->sum('shipping_cost');
            $deliveryTotal = $orders->count();
            $moveTotal = $orders->sum('distance');

        } else if ($type == 'week') {
            $firstOfWeek = Carbon::now()->startOfWeek();
            $lastOfWeek = Carbon::now()->endOfWeek();

            $orders = Order::where('user_id', $userId)->whereBetween('created_at', [$firstOfWeek, $lastOfWeek])->get();
            $incomeTotal = $orders->sum('shipping_cost');
            $deliveryTotal = $orders->count();
            $moveTotal = $orders->sum('distance');
        }

        return $this->success([
            'incomeTotal' => strval($incomeTotal),
            'deliveryTotal' => strval($deliveryTotal),
            'moveTotal' => strval($moveTotal)
        ]);
    }

    public function updateDriverLocation(UpdateDriverLocationRequest $request)
    {
        $data = $request->validated();
        broadcast(new UpdateDriverLocation($data['lat'], $data['lng'], $data['receiverId']));

        return $this->success($data);
    }
}