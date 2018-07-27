<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class Broadcast extends Event implements ShouldBroadcast
{
    use SerializesModels;

    public $title;
    public $content;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($title, $content)
    {
	    $this->title = $title;
	    $this->content = $content;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return ['wzoj'];
    }
}
