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

class ListTestcases extends Event implements ShouldBroadcastNow
{
    use SerializesModels;

    public $testcases;
    public $solution_id;
    private $user_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($solution, $testcases)
    {
	    $this->user_id = $solution->user_id;
      $this->solution_id = $solution->id;
	    $this->testcases = $testcases;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('user.'.$this->user_id);
    }
}