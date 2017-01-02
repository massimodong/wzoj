<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Solution;
use App\Problem;
use App\Testcase;

class JudgerController extends Controller
{
	public function __construct(){
		$this->middleware('auth');
		$this->middleware('judger');
	}
	public function getIndex(){
		return response()->json(['ok' => true]);
	}
	public function getPendingSolutions(Request $request){
		$solutions = Solution::where('status','<=',1)->get();
		return response()->json($solutions);
	}
	public function postCheckout(Request $request){
		$this->validate($request,[
			"solution_id" => "required|integer",
		]);
		$solution = Solution::find($request->solution_id);
		if($solution == NULL) return response()->json(["ok" => false]);
		if($solution->status <= 1 || $request->force === "true"){
			$solution->time_used = 0;
			$solution->memory_used = 0.0;
			$solution->status = SL_COMPILING;
			$solution->score = 0;
			$solution->ce = NULL;
			$solution->sim_id = NULL;
			$solution->judger_id = $request->user()->id;
			$solution->save();

			foreach($solution->testcases as $testcase){
				$testcase->delete();
			}
			return response()->json(["ok" => true]);
		}else{
			return response()->json(["ok" => false]);
		}
	}
	public function getSolution(Request $request){
		$this->validate($request,[
			"solution_id" => "required|integer",
		]);
		$solution = Solution::findOrFail($request->solution_id);
		return response()->json($solution);
	}
	public function getProblem(Request $request){
		$this->validate($request,[
			"problem_id" => "required|integer",
		]);
		$problem = Problem::findOrFail($request->problem_id);
		return response()->json($problem);
	}
	public function postUpdateCe(Request $request){
		$this->validate($request,[
			"solution_id" => "required|integer",
		]);
		$solution = Solution::findOrFail($request->solution_id);
		$solution->ce = $request->ce;
		$solution->save();
		return response()->json(["ok" => true]);
	}
	public function postUpdateSolution(Request $request){
		$this->validate($request,[
			"solution_id" => "required|integer",
		]);
		$solution = Solution::findOrFail($request->solution_id);

		$solution->time_used = $request->time_used;
		$solution->memory_used = $request->memory_used;
		$solution->status = $request->status;
		$solution->score = $request->score;
		$solution->cnt_testcases = $request->cnt_testcases;
		$solution->judged_at = date('Y-m-d H:i:s');
		$solution->save();
		return response()->json(["ok" => true]);
	}
	public function postFinishJudging(Request $request){
		$this->validate($request,[
			"solution_id" => "required|integer",
		]);
		$solution = Solution::findOrFail($request->solution_id);

		$cnt_testcases = 0;
		$time_used = 0;
		$memory_used = 0;
		$score = 0;

		foreach($solution->testcases as $testcase){
			++$cnt_testcases;
			$time_used = max($time_used, $testcase->time_used);
			$memory_used = max($memory_used, $testcase->memory_used);
			$score += $testcase->score;
		}

		if($cnt_testcases){
			$score /= $cnt_testcases;
		}else{
			$score = 0;
		}

		$solution->time_used = $time_used;
		$solution->memory_used = $memory_used;
		$solution->status = SL_JUDGED;
		$solution->score = $score;
		$solution->judged_at = date('Y-m-d H:i:s');
		
		$solution->save();
	}
	public function postPostTestcase(Request $request){
		$testcase = Testcase::create($request->all());
	}
}
