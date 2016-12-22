<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class UserAwaitsDocumentZip
{
    use InteractsWithSockets, SerializesModels;

    protected $demands;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(array $demands)
    {
        $this->demands = $demands;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }

    public function getDemands()
    {
        return $this->demands;
    }
}
