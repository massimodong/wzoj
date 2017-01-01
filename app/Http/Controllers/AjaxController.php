<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Auth;
use App\Solution;
use App\Http\Requests;
use App\Http\Controllers\Controller;

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

		return response()->json(['testcases' => $solution->testcases()->where('id', '>', $last_tid)->get()]);
	}

	public function getSolutionStatus(Request $request){
		$this->validate($request, [
			'solution_id' => 'required|integer',
		]);
		$solution = \App\Solution::findOrFail($request->solution_id);
		return response()->json(['status' => $solution->status]);
	}

	public function getSolutionResult(Request $request){
		$this->validate($request, [
			'solution_id' => 'required|integer',
		]);
		$solution = \App\Solution::findOrFail($request->solution_id);
		return response()->json(['score' => $solution->score,
					 'time_used' => $solution->time_used,
					 'memory_used' => $solution->memory_used,
					 'judged_at' => $solution->judged_at]);
	}
}
