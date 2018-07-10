<?php

namespace App\Events;

use App\Solution;
use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NewSolution extends Event implements ShouldBroadcast
{
    use SerializesModels;

    public $solution;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($solution)
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
        return ['solutions'];
    }

    public function broadcastWith()
    {
	    return $this->solution->publicAttr();
    }
}
