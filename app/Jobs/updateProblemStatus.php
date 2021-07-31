<?php

namespace App\Jobs;

use Event;
use App\Events\ProblemStatusUpdate;

use Cache;
use DB;

use App\Problemset;
use App\Problem;
use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class updateProblemStatus extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $problemset, $problem;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Problemset $ps, Problem $problem)
    {
	    $this->problemset = $ps;
	    $this->problem = $problem;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
	$problemset = $this->problemset;
	$problem = $this->problem;
	$problem_status = Cache::tags(['problem_status', $problemset->id])->remember($problem->id, CACHE_ONE_MINUTE,
			function() use($problemset, $problem){
		//best solutions for this problemset/problem
		$best_solutions_meta = $problemset->solutions()
			->where('problem_id', $problem->id)
			->groupBy('user_id')
			->select('user_id')
			->addSelect(DB::raw('MAX(rate) as rate'))
			->orderBy('rate', 'desc')
			->take(3)
			->get();
		$best_solutions = [];
		foreach($best_solutions_meta as $meta){
			array_push($best_solutions,
					$problemset->solutions()
					->where('problem_id', $problem->id)
					->where('user_id', $meta->user_id)
					->where('rate', $meta->rate)
					->with(['user' => function($query){
						$query->select(['id', 'name']);
						}])
					->select(['id', 'score', 'time_used', 'memory_used', 'user_id'])
					->first()
				  );
			}
		//count solutions
		$cnt_submit = $problemset->solutions()
			->where('problem_id', $problem->id)
			->count();
		//count ac solutions
		$cnt_ac = $problemset->solutions()
			->where('problem_id', $problem->id)
			->where('score', '>=', 100)
			->count();

		Event::dispatch(new ProblemStatusUpdate($problemset->id,
							$problem->id,
							$best_solutions,
							$cnt_submit,
							$cnt_ac));

		return [
			'best_solutions' => $best_solutions,
			'cnt_submit' => $cnt_submit,
			'cnt_ac' => $cnt_ac,
		];
	});
    }
}
