<?php

namespace App\Events;

use App\Models\PreInventoryAction;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PreInventoryActionAssignedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $action;

    /**
     * Create a new event instance.
     *
     * @param PreInventoryAction $action
     */
    public function __construct(PreInventoryAction $action)
    {
        $this->action = $action;
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
