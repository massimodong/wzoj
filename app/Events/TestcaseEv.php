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

class TestcaseEv extends Event implements ShouldBroadcastNow
{
    use SerializesModels;

    public $solution_id;
    public $testcase_name;
    public $time_used;
    public $memory_used;
    public $verdict;
    public $score;
    private $user_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($solution, $request)
    {
	    $this->user_id = $solution->user_id;
      $this->solution_id = $solution->id;
      $this->testcase_name = $request->testcase_name;
      $this->time_used = $request->time_used;
      $this->memory_used = $request->memory_used;
      $this->verdict = $request->verdict;
      $this->score = $request->score;
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
