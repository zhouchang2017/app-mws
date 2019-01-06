<?php

namespace App\Events;

use App\Models\Supply;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class SupplyShippedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $supply;

    /**
     * Create a new event instance.
     *
     * @param Supply $supply
     */
    public function __construct(Supply $supply)
    {
        $this->supply = $supply;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
