<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Solution;
use App\Testcase;

use DB;
use Cache;

class AdminProblemRejudgeController extends Controller
{
	public function rejudgeSolutions($solutions){
		$solutions->update(['status' => SL_PENDING_REJUDGING]);
		Cache::tags(['solutions'])->flush();
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
		Solution::where('id', $request->solution_id)->update(['status' => SL_PENDING_REJUDGING]);
		wakeJudgers($request->solution_id);
		return response()->json("ok");
		//$solutions = $this->genSolutionsQuery($request);
		//$this->rejudgeSolutions($solutions);
		//wakeJudgers();
		//return back();
	}

	public function getProblemRejudgeCheck(Request $request){
		logAction('admin_problem_rejudge', ["solution_id" => $request->solution_id, "problemset_id" => $request->problemset_id, "problem_id" => $request->problem_id], LOG_MODERATE);
		$solutions = $this->genSolutionsQuery($request);
		$sol2 = clone $solutions;
		$sol3 = clone $solutions;

		$count = $solutions->count();
		$time_used = $sol2->sum(DB::raw('time_used * cnt_testcases'));
		$sids = $sol3->orderBy('id', 'asc')->pluck("id")->all();

		return response()->json([
			'sids' => $sids,
			'count' => $count,
			'time_used' => $time_used,
		]);
	}
}
