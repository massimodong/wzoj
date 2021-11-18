<?php

namespace App\Http\Controllers;

use Event;
use App\Events\SolutionUpdated;
use App\Events\ListTestcases;
use App\Events\CompileErr;
use App\Events\TestcaseEv;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Cache;
use DB;

use Log;
use App\Solution;
use App\Problem;
use App\Sim;

use Illuminate\Support\Facades\Redis;

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
				->where('solutions.status', 0)
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
				->where('solutions.status', 0)
				->where('problemsets.type', 'oi')
				->where('problemsets.contest_end_at', '<', DB::raw('now()'))
				->where('solutions.created_at', '>=', DB::raw('problemsets.contest_start_at'))
				->where('solutions.created_at', '<=', DB::raw('problemsets.contest_end_at'))
				->take(5)
				->orderBy('solutions.user_id', 'asc')
				->select('solutions.id');
		$solutions = $solutions->union($solutions_oi);

		$solutions = $solutions->get();

		if(!count($solutions)){
			$solutions = Solution::leftJoin('users', 'solutions.user_id', '=', 'users.id')
				->where('users.bot_tendency', '<', 100)
				->where('solutions.status', 1)
				->take(20)
				->orderBy('solutions.id', 'asc')
				->select('solutions.id')
				->get();
		}

		/*
		if(!count($solutions)){
			$solutions = Solution::where('status', 0)
				->take(1)
				->select('id')
				->get();
		}

		if(!count($solutions)){
			$solutions = Solution::where('status', 1)
				->take(1)
				->select('id')
				->get();
		}
		*/

		return response()->json($solutions);
	}
	public function getSolution(Request $request){
		$this->validate($request,[
			"solution_id" => "required|integer",
		]);
		$solution = \App\Solution::findOrFail($request->solution_id);

    DB::update('UPDATE problem_statistics SET score_sum = score_sum - ?, count_ac = count_ac - IF(? = 100, 1, 0) WHERE problemset_id = ? AND problem_id = ?',
        [$solution->score, $solution->score, $solution->problemset_id, $solution->problem_id]);
    $solution->time_used = 0;
    $solution->memory_used = 0.0;
    $solution->status = SL_COMPILING;
    $solution->score = 0;
    $solution->ce = NULL;
    $solution->testcases = Array();
    $solution->sim_id = NULL;
    $solution->judger_id = \Request::get('judger')->id;
    $solution->save();
    //Event::dispatch(new SolutionUpdated($solution));

		return response()->json([
			'id' => $solution->id,
			'user_id' => $solution->user_id,
			'problem_id' => $solution->problem_id,
			'language' => $solution->language,
			'code' => $solution->code,
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

	public function postFinishJudging(Request $request){
		$this->validate($request,[
			"solution_id" => "required|integer",
		]);

		$solution = \App\Solution::findOrFail($request->solution_id);
		$solution->problem->update_cnt_submit();
		$solution->problem->update_cnt_ac();
		if($solution->problem->use_subtasks) $solution->calc_score();

		$solution->status = SL_JUDGED;
		$solution->judged_at = date('Y-m-d H:i:s');
		$solution->rate = $solution->score * 10000000000000000
					- $solution->time_used * 100000000000
					- $solution->memory_used
					- $solution->code_length;
		
		$solution->save();

    DB::update('UPDATE problem_statistics SET score_sum = score_sum + ?, count_ac = count_ac + IF(? = 100, 1, 0) WHERE problemset_id = ? AND problem_id = ?',
        [$solution->score, $solution->score, $solution->problemset_id, $solution->problem_id]);

		$cache_path = $solution->user_id.'-'.$solution->problemset_id.'-'.$solution->problem_id;
		if($solution->score > Cache::tags(['problemsets', 'max_score'])->get($cache_path, -1)){
			Cache::tags(['problemsets', 'max_score'])->put($cache_path, $solution->score, CACHE_ONE_DAY);
		}

		$solution->user->update_cnt_ac();
		Event::dispatch(new SolutionUpdated($solution));
	}

	public function getGetAnswer(Request $request){
		$solution = Solution::findOrFail($request->solution_id);
		$answer = $solution->answerfiles()->where('filename', $request->filename)->first();
		return response()->json($answer);
	}

  public function postListTestcases(Request $request){
		$this->validate($request,[
			"solution_id" => "required|integer",
		]);
		$solution = \App\Solution::findOrFail($request->solution_id);

		Event::dispatch(new ListTestcases($solution, $request->testcases));
    return response()->json(['ok' => true]);
  }

  public function postCompileError(Request $request){
		$this->validate($request,[
			"solution_id" => "required|integer",
		]);
		$solution = \App\Solution::findOrFail($request->solution_id);

		$solution->ce = $request->ce;
		$solution->judged_at = date('Y-m-d H:i:s');
		$solution->save();

		Event::dispatch(new CompileErr($solution));
    return response()->json(['ok' => true]);
  }

  public function postTestcase(Request $request){
		$this->validate($request,[
			"solution_id" => "required|integer",
		]);
		$solution = \App\Solution::findOrFail($request->solution_id);

    $testcase = [];
    $testcase["filename"] = $request->testcase_name;
    $testcase["score"] = $request->score;
    $testcase["time_used"] = $request->time_used;
    $testcase["memory_used"] = $request->memory_used;
    $testcase["verdict"] = $request->verdict;
    $testcase["checklog"] = "TODO";
    array_push($solution->testcases, $testcase); //TODO: testcases in a seperate table

    Event::dispatch(new TestcaseEv($solution, $request));

    return response()->json(['ok' => true]);
  }
}
