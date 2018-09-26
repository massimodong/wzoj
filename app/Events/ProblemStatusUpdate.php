<?php

namespace App\Events;

use App\Problemset;
use App\Problem;
use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ProblemStatusUpdate extends Event implements ShouldBroadcast
{
    use SerializesModels;

    public $problemset_id;
    public $problem_id;
    public $best_solutions;
    public $cnt_submit;
    public $cnt_ac;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($problemset_id, $problem_id, $best_solutions, $cnt_submit, $cnt_ac)
    {
	    $this->problemset_id = $problemset_id;
	    $this->problem_id = $problem_id;
	    $this->best_solutions = $best_solutions;
	    $this->cnt_submit = $cnt_submit;
	    $this->cnt_ac = $cnt_ac;
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

    public function broadcastWith()
    {
	    return [
		    'psid' => $this->problemset_id,
		    'pid' => $this->problem_id,
		    'best_solutions' => $this->best_solutions,
		    'cnt_submit' => $this->cnt_submit,
		    'cnt_ac' => $this->cnt_ac,
	    ];
    }
}
