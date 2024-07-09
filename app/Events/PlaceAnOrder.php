<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PlaceAnOrder implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private Order $order;
    private $receive_driver_id;
    /**
     * Create a new event instance.
     */
    public function __construct(Order $order, $receive_driver_id)
    {
        $this->order = $order;
        $this->receive_driver_id = $receive_driver_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel("place-an-order.{$this->receive_driver_id}"),
        ];
    }

    public function broadcastWith(): array {
        return [
            'data' => $this->order
        ];
    }
}