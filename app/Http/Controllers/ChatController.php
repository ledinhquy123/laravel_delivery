<?php

namespace App\Http\Controllers;

use App\Events\SendMessage;
use App\Http\Requests\ChatStoreRequest;
use App\Models\MessagesDriver;
use App\Models\MessagesUser;
use App\Models\Order;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    private $noti;

    public function __construct(NotificationController $noti)
    {
        $this->noti = $noti;
    }
    
    public function index(Request $request) {
        // return MessagesDriver::all();
        $orderId = $request->input('orderId');
        $order = Order::find($orderId);

        $ar1 = MessagesDriver::where('order_id', $orderId)
        ->latest()
        ->get();
        
        $ar1 = $ar1->map(function($senderId) use ($order) {
            return $senderId->withSenderIdAttribute($order->driver_id);
        });
        
        $ar2 = MessagesUser::where('order_id', $orderId)->latest()->get();
        $ar2 = $ar2->map(function($senderId) use ($order) {
            return $senderId->withSenderIdAttribute($order->user_id);
        });

        $ar = $ar1->concat($ar2)->sortBy('created_at')->values();

        return $this->success($ar);
    }

    public function store(ChatStoreRequest $request) {
        $data = $request->validated();
        $guard = $data['guard'];
        $serverKey = $data['server_key'];
        
        unset($data['guard']);
        unset($data['server_key']);

        $order = Order::find($data['order_id']);
        
        if($guard == 'driver') {
            $messageDriver = MessagesDriver::create($data);
            broadcast(new SendMessage($order->user->id, $messageDriver->message));
            
            $this->noti->notify($order->driver->id, $messageDriver->message, $order->user->fcm_token, $serverKey);
            return $this->success($messageDriver, 'Message has been created successfully');
        }else if($guard == 'user') {
            $messageUser = MessagesUser::create($data);
            broadcast(new SendMessage($order->driver->id, $messageUser->message));
            $this->noti->notify($order->user->id, $messageUser->message, $order->driver->fcm_token, $serverKey);
            return $this->success($messageUser, 'Message has been created successfully');
        }
        return $this->success(null, 'Message has not been created');
    }
}