<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UpdateDriverLocation implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $lat;
    private $lng;
    private $receiverId;
    /**
     * Create a new event instance.
     */
    public function __construct($lat, $lng, $receiverId)
    {
        $this->lat = $lat;
        $this->lng = $lng;
        $this->receiverId = $receiverId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel("update-driver-location.{$this->receiverId}"),
        ];
    }

    public function broadcastWith():array {
        return [
            'lat' => $this->lat,
            'lng' => $this->lng
        ];
    }
}