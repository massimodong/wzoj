<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Solution;
use App\Testcase;

use DB;

class AdminProblemRejudgeController extends Controller
{
	public function rejudgeSolutions($solutions){
		$solutions->update(['time_used' => 0,
				    'memory_used' => 0.0,
				    'status' => SL_PENDING_REJUDGING,
				    'score' => 0,
				    'ce' => NULL,
				    'sim_id' => NULL,
				    'judger_id' => 0]);
	}
	public function getProblemRejudge(){
		return view('admin.problem_rejudge');
	}

	public function genSolutionsQuery(Request $request){
		$this->validate($request, [
			'problemset_id' => 'integer',
		]);
		$solutions = Solution::where('id', '<>', -1);
		if(isset($request->solution_id) && $request->solution_id != ""){
			$ids = explode(",",$request->solution_id);
			$solutions = $solutions->where(function($query) use($ids){
				foreach($ids as $id){
					if(strpos($id,"-")){
						$range = explode("-",$id);
						$query->orWhere(function($query) use($range){
							$query->where('id', '>=', $range[0])
							      ->where('id', '<=', $range[1]);
						});
					}else{
						$query->orWhere(function($query) use($id){
							$query->where('id', $id);
						});
					}
				}
			});
		}

		if(isset($request->problemset_id) && $request->problemset_id > 0){
			$problemset = \App\Problemset::find($request->problemset_id);
			if($problemset <> null){
				$solutions = $solutions->where('problemset_id', $problemset->id);
			}
		}

		if(isset($request->problem_id) && $request->problem_id > 0){
			$problem = \App\Problem::find($request->problem_id);
			if($problem <> NULL){
				$solutions = $solutions->where('problem_id', $problem->id);
			}
		}
		return $solutions;
	}

	public function postProblemRejudge(Request $request){
		$solutions = $this->genSolutionsQuery($request);
		$this->rejudgeSolutions($solutions);
		return back();
	}

	public function getProblemRejudgeCheck(Request $request){
		$solutions = $this->genSolutionsQuery($request);
		$count = (clone $solutions)->count();
		$time_used = (clone $solutions)->sum(DB::raw('time_used * cnt_testcases'));

		return response()->json([
			'count' => $count,
			'time_used' => $time_used,
		]);
	}
}
