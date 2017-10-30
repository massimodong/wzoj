<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Cache;
use Auth;
use App\Solution;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Redis;

class AjaxController extends Controller
{
	public function getIndex(){
		return response()->json(['welcome'=>'hello world!']);
	}

	public function getTestcases(Request $request){
		$this->validate($request, [
			'solution_id' => 'required|integer',
			'index' => 'required|integer',
		]);
		
		$solution = \App\Solution::findOrFail($request->solution_id);
		$testcases = Array();

		for($i=$request->index;isset($solution->testcases[$i]);++$i){
			array_push($testcases, $solution->testcases[$i]);
		}

		return response()->json(['testcases' => $testcases,
					'cnt_testcases' => $solution->cnt_testcases]);//total testcases
	}

	public function getSolutionStatus(Request $request){
		$this->validate($request, [
			'solution_id' => 'required|integer',
		]);

		$solution = \App\Solution::findOrFail($request->solution_id);

		return response()->json(['status' => $solution->status,
					'score' => $solution->score,
					'ce' => isset($solution->ce)?'1':'0']);
	}

	public function getSolutionResult(Request $request){
		$this->validate($request, [
			'solution_id' => 'required|integer',
		]);

		$solution = \App\Solution::findOrFail($request->solution_id);

		return response()->json(['score' => $solution->score,
					 'time_used' => $solution->time_used,
					 'memory_used' => $solution->memory_used,
					 'judger' => $solution->judger()->select(['name'])->first(),
					 'judged_at' => $solution->judged_at]);
	}

	public function getSolutionsJudging(Request $request){
		$this->validate($request, [
			'last_time' => 'required|date',
		]);
		$solutions_judging = Redis::smembers('wzoj_judging_solution_ids');
		$solutions = \App\Solution::where('judged_at', '>=', $request->last_time)
						->whereIn('id', Redis::lrange('wzoj_recent_solution_ids', 0, -1))
						->select('id')
						->get();
		foreach($solutions as $solution){
			array_push($solutions_judging, $solution->id);
		}
		return response()->json(['solutions' => $solutions_judging, 'cur_time' => date('Y-m-d H:i:s')]);
	}

	public function getContestSolutions(Request $request){
		$this->validate($request, [
			'problemset_id' => 'required|integer',
			'top' => 'required|integer',
		]);
		$problemset = \App\Problemset::findOrFail($request->problemset_id);
		$solutions = $problemset->solutions()->where('id', '>', $request->top)
			->where('created_at', '>=', $problemset->contest_start_at)
			->where('created_at', '<=', $problemset->contest_end_at)
			->public()
			->get();
		return response()->json(['solutions' => $solutions]);
	}

	public function getSolutions(Request $request){
		$this->validate($request, [
			'top' => 'required|integer',
		]);

		$solutions = \App\Solution::where('id', '>', $request->top)
			->public()
			->get();
		return response()->json(['solutions' => $solutions]);
	}

}
