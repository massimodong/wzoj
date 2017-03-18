<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Auth;
use App\Solution;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Storage;

class AjaxController extends Controller
{
	public function getIndex(){
		return response()->json(['welcome'=>'hello world!']);
	}

	public function getRoles(Request $request){
		if(!Auth::check()){
			return response()->json(NULL);
		}else{
			return response()->json($request->user()->roles);
		}
	}

	public function getToken(Request $request){
		return response()->json(['_token' => csrf_token()]);
	}

	public function postTest(Request $request){
		return $request->all();
	}

	public function getTestcases(Request $request){
		$this->validate($request, [
			'solution_id' => 'required|integer',
			'last_tid' => 'integer',
		]);
		$solution = \App\Solution::findOrFail($request->solution_id);

		$last_tid = 0;
		if(isset($request->last_tid)){
			$last_tid = $request->last_tid;
		}

		return response()->json(['testcases' => $solution->testcases()->where('id', '>', $last_tid)->get(),
					'cnt_testcases' => $solution->cnt_testcases]);//total testcases
	}

	public function getSolutionStatus(Request $request){
		$this->validate($request, [
			'solution_id' => 'required|integer',
		]);
		$solution = \App\Solution::findOrFail($request->solution_id);
		return response()->json(['status' => $solution->status,
					'score' => $solution->score,
					'ce' => isset($solution->ce)]);
	}

	public function getSolutionResult(Request $request){
		$this->validate($request, [
			'solution_id' => 'required|integer',
		]);
		$solution = \App\Solution::findOrFail($request->solution_id);
		return response()->json(['score' => $solution->score,
					 'time_used' => $solution->time_used,
					 'memory_used' => $solution->memory_used,
					 'judger' => $solution->judger()->select(['fullname'])->first(),
					 'judged_at' => $solution->judged_at]);
	}

	public function getSolutionsJudging(Request $request){
		$this->validate($request, [
			'last_time' => 'required|date',
		]);
		$solutions = \App\Solution::where('judged_at', '>', $request->last_time)
					->orderBy('judged_at', 'asc')
					->get(['id', 'judged_at']);
		return response()->json(['solutions' => $solutions]);
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

	public function postSubmitAnswerfile(Request $request){
		$problemset = \App\Problemset::findOrFail($request->problemset_id);
		$this->authorize('view',$problemset);
		$this->validate($request,[
			'problem_id' => 'required|exists:problem_problemset,problem_id,problemset_id,'.$problemset->id,
			'answerfile' => 'required',
		]);

		if(!ojCanViewProblems($problemset)){
			return back();
		}

		//check if is problem type 3
		$problem = $problemset->problems()->findOrFail($request->problem_id);
		if($problem->type <> 3){
			return response()->json(['error' => trans('wzoj.problem_not_type3')]);
		}

		$pinfo = pathinfo($request->file('answerfile')->getClientOriginalName());
		$filename = $pinfo['filename'];
		//check filename
		if($pinfo['extension'] <> 'out'){
			return response()->json(['error' => trans('wzoj.not_out_file')]);
		}
		if(!Storage::disk('data')->has('/'.$problem->id.'/'.$filename.'.in')){
			return response()->json(['error' => trans('wzoj.invalid_file')]);
		}

		$answerfile = $request->user()->answerfiles()
			->where('problemset_id', $request->problemset_id)
			->where('problem_id', $request->problem_id)
			->where('filename', $filename)
			->first();
		if($answerfile == NULL){
			$answerfile = $request->user()->answerfiles()->create([
				'problemset_id' => $request->problemset_id,
				'problem_id' => $request->problem_id,
				'filename' => $filename,
			]);
		}

		$answerfile->answer = file_get_contents($request->file('answerfile')->getRealPath());
		$answerfile->save();

		$ret=[];
		return response()->json($ret);
	}
}
