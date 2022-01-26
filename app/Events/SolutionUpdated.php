<?php

namespace App\Events;

use App\Solution;
use App\Events\Event;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class SolutionUpdated extends Event implements ShouldBroadcastNow
{
    use SerializesModels;

    public $solution;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Solution $solution)
    {
	    $this->solution = $solution;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('user.'.$this->solution->user_id);
    }

    public function broadcastWith(){
	    return $this->solution->publicAttrLess();
    }
}
