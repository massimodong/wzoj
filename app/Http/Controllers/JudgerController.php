<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Cache;
use App\Problemset;
use DB;

use Log;
use App\Solution;
use App\Problem;
use App\Testcase;

class JudgerController extends Controller
{
	public function __construct(){
		$this->middleware('judger');
	}
	public function getIndex(){
		return response()->json(['ok' => true]);
	}
	public function getPendingSolutions(Request $request){
		if(!(Solution::where('status', '<=', 1)->count())){
			return response()->json([]);
		}
		$solutions = Solution::leftJoin('problemsets', 'solutions.problemset_id', '=', 'problemsets.id')
				->leftJoin('users', 'solutions.user_id', '=', 'users.id')
				->where('users.bot_tendency', '<', 100)
				->where('solutions.status', '<=', 1)
				->where(function($query){
					$query->where('problemsets.type', '<>', 'oi')
					      ->orWhere('solutions.problemset_id', '<', 0)
					      ->orWhere('solutions.created_at', '<', DB::raw('problemsets.contest_start_at'))
					      ->orWhere('solutions.created_at', '>', DB::raw('problemsets.contest_end_at'));
						})
				->take(200)
				->orderBy('solutions.id','asc')
				->groupBy('solutions.user_id')
				->select(DB::raw('MIN(solutions.id) as id'));

		$solutions_oi = Solution::leftJoin('problemsets', 'solutions.problemset_id', '=', 'problemsets.id')
				->where('solutions.status', '<=', 1)
				->where('problemsets.type', 'oi')
				->where('problemsets.contest_end_at', '<', DB::raw('now()'))
				->where('solutions.created_at', '>=', DB::raw('problemsets.contest_start_at'))
				->where('solutions.created_at', '<=', DB::raw('problemsets.contest_end_at'))
				->take(5)
				->orderBy('solutions.user_id', 'asc')
				->select('solutions.id');
		$solutions = $solutions->union($solutions_oi);

		$solutions = $solutions->get();
		return response()->json($solutions);
	}
	public function postCheckout(Request $request){
		$this->validate($request,[
			"solution_id" => "required|integer",
		]);
		$solution = Solution::find($request->solution_id);
		if($solution == NULL) return response()->json(["ok" => false]);
		if($solution->status <= 1 || $request->force === "true"){
			$query = Solution::where('id', $solution->id);
			if(!$request->force){
				$query = $query->where('status', '<=', 1);
			}
			$ok = $query->update([
				'time_used' => 0,
				'memory_used' => 0.0,
				'status' => SL_COMPILING,
				'score' => 0,
				'ce' => NULL,
				'sim_id' => NULL,
				'judger_id' => \Request::get('judger')->id,
			]);

			if($ok){
				foreach($solution->testcases as $testcase){
					$testcase->delete();
				}
				$solution->time_used = 0;
				$solution->memory_used = 0.0;
				$solution->status = SL_COMPILING;
				$solution->score = 0;
				$solution->ce = NULL;
				$solution->sim_id = NULL;
				$solution->judger_id = \Request::get('judger')->id;
				Cache::tags(['solutions'])->put($solution->id, $solution, 1);
				Cache::tags(['solutions', 'testcases'])->put($solution->id, collect(new \App\Testcase), 1);
				return response()->json(["ok" => true]);
			}else{
				return response()->json(["ok" => false]);
			}
		}else{
			return response()->json(["ok" => false]);
		}
	}
	public function getSolution(Request $request){
		$this->validate($request,[
			"solution_id" => "required|integer",
		]);
		$solution = Cache::tags(['solutions'])->remember($request->solution_id, 1, function() use($request){
			return \App\Solution::findOrFail($request->solution_id);
		});
		return response()->json([
			'id' => $solution->id,
			'problem_id' => $solution->problem_id,
			'language' => $solution->language,
			'code' => $solution->code,
			'time_used' => $solution->time_used,
			'memory_used' => $solution->memory_used,
			'status' => $solution->status,
			'score' => $solution->score,
			'ce' => $solution->ce,
			'cnt_testcases' => $solution->cnt_testcases,
		]);
	}
	public function getProblem(Request $request){
		$this->validate($request,[
			"problem_id" => "required|integer",
		]);
		$problem = Cache::tags(['problems'])->rememberForever($request->problem_id, function() use ($request){
			return Problem::findOrFail($request->problem_id);
		});
		return response()->json([
			'id' => $problem->id,
			'name' => $problem->name,
			'type' => $problem->type,
			'spj' => $problem->spj,
			'timelimit' => $problem->timelimit,
			'memorylimit' => $problem->memorylimit,
		]);
	}
	public function postUpdateCe(Request $request){
		$this->validate($request,[
			"solution_id" => "required|integer",
		]);
		$solution = Cache::tags(['solutions'])->remember($request->solution_id, 1, function() use($request){
			return \App\Solution::findOrFail($request->solution_id);
		});
		$solution->ce = $request->ce;
		$solution->save();
		Cache::tags(['solutions'])->put($solution->id, $solution, 1);
		return response()->json(["ok" => true]);
	}
	public function postUpdateSolution(Request $request){
		$this->validate($request,[
			"solution_id" => "required|integer",
		]);
		$solution = Cache::tags(['solutions'])->remember($request->solution_id, 1, function() use($request){
			return \App\Solution::findOrFail($request->solution_id);
		});

		$solution->time_used = $request->time_used;
		$solution->memory_used = $request->memory_used;
		$solution->status = $request->status;
		$solution->score = $request->score;
		$solution->cnt_testcases = $request->cnt_testcases;
		$solution->save();
		Cache::tags(['solutions'])->put($solution->id, $solution, 1);

		$cache_path = $solution->user_id.'-'.$solution->problemset_id.'-'.$solution->problem_id;
		if($solution->score > Cache::tags(['problemsets', 'max_score'])->get($cache_path, -1)){
			Cache::tags(['problemsets', 'max_score'])->put($cache_path, $solution->score, CACHE_ONE_DAY);
		}

		return response()->json(["ok" => true]);
	}
	public function postFinishJudging(Request $request){
		$this->validate($request,[
			"solution_id" => "required|integer",
		]);
		$solution = Cache::tags(['solutions'])->remember($request->solution_id, 1, function() use($request){
			return \App\Solution::findOrFail($request->solution_id);
		});

		$solution->status = SL_JUDGED;
		$solution->judged_at = date('Y-m-d H:i:s');
		$solution->rate = $solution->score * 10000000000000000
					- $solution->time_used * 100000000000
					- $solution->memory_used
					- $solution->code_length;
		
		$solution->save();
		Cache::tags(['solutions'])->put($solution->id, $solution, 1);

		$solution->user->update_cnt_ac();
	}
	public function postPostTestcase(Request $request){
		$testcase = Testcase::create($request->except('judger_token'));
		if(Cache::tags(['solutions', 'testcases'])->has($testcase->solution_id)){
			$all_testcases = Cache::tags(['solutions', 'testcases'])->get($testcase->solution_id);
			$all_testcases->push($testcase);
			Cache::tags(['solutions', 'testcases'])->put($testcase->solution_id, $all_testcases, 1);
		}
	}

	public function getGetAnswer(Request $request){
		$solution = Solution::findOrFail($request->solution_id);
		$answer = $solution->answerfiles()->where('filename', $request->filename)->first();
		return response()->json($answer);
	}
}
