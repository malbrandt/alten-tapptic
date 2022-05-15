<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PairMatched //implements ShouldBroadcast
{
    use Dispatchable;

    public int $firstUserId;
    public int $secondUserId;

    public function __construct(int $firstUserId, int $secondUserId)
    {
        $this->firstUserId = $firstUserId;
        $this->secondUserId = $secondUserId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return [
//            new PrivateChannel('user.' . $this->firstUserId),
//            new PrivateChannel('user.' . $this->secondUserId),
        ];
    }
}
