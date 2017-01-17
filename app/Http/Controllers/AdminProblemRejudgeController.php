<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Solution;

class AdminProblemRejudgeController extends Controller
{
	public function rejudgeSolution(Solution $solution){
		$solution->time_used = 0;
		$solution->memory_used = 0.0;
		$solution->status = SL_PENDING;
		$solution->score = 0;
		$solution->ce = NULL;
		$solution->sim_id = NULL;
		$solution->judger_id = 0;
		$solution->save();

		foreach($solution->testcases as $testcase){
			$testcase->delete();
		}
	}
	public function getProblemRejudge(){
		return view('admin.problem_rejudge');
	}

	public function postProblemRejudge(Request $request){
		$this->validate($request, [
			'solution_id' => 'integer',
			'problemset_id' => 'integer',
		]);
		if(isset($request->solution_id) && $request->solution_id > 0){
			$solution = Solution::find($request->solution_id);
			if($solution <> null){
				$this->rejudgeSolution($solution);
			}
		}

		if(isset($request->problemset_id) && $request->problemset_id > 0){
			$problemset = \App\Problemset::find($request->problemset_id);
			if($problemset <> null){
				foreach($problemset->solutions as $solution){
					$this->rejudgeSolution($solution);
				}
			}
		}
		return back();
	}
}
