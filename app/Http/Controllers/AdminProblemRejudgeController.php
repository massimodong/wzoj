<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Solution;

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

	public function postProblemRejudge(Request $request){
		$this->validate($request, [
			'problemset_id' => 'integer',
		]);
		$solutions = Solution::where('id',-1);
		if(isset($request->solution_id) && $request->solution_id != ""){
			$ids = explode(",",$request->solution_id);
			foreach($ids as $id){
				if(strpos($id,"-")){
					$range = explode("-",$id);
					echo $range[0].' to '.$range[1]."<br>";
					$solutions = $solutions->orWhere(function($query) use($range){
						$query->where('id', '>=', $range[0])
						      ->where('id', '<=', $range[1]);
					});
				}else{
					echo $id."<br>";
					$solutions = $solutions->orWhere(function($query) use($id){
						$query->where('id', $id);
					});
				}
			}
			$this->rejudgeSolutions($solutions);
		}

		if(isset($request->problemset_id) && $request->problemset_id > 0){
			$problemset = \App\Problemset::find($request->problemset_id);
			if($problemset <> null){
				$this->rejudgeSolutions($problemset->solutions());
			}
		}
		return back();
	}
}
