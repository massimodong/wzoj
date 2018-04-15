<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Group;
use Gate;
use Auth;
use Cache;

class GroupController extends Controller
{
	public function getHomeworkStatus($id, Request $request){
		$group = Group::findOrFail($id);
		if(Gate::denies('view', $group)){
			if(Auth::check()) abort(403);
			else return redirect('/auth/login');
		}

		$problem_cols = array();
		$total_score = 0;
		$homeworks = Cache::tags(['group_homeworks'])->rememberForever($group->id, function() use($group){
			return $group->homeworks;
		});
		foreach($homeworks as $problem){
			if(!isset($problem_cols[$problem->pivot->problemset_id])) $problem_cols[$problem->pivot->problemset_id] = collect(new \App\Problem);
			$problem_cols[$problem->pivot->problemset_id]->push($problem);
			$total_score += 100;
		}

		$user_max_scores = array();
		$user_total_scores = array();
		foreach($group->users as $user){
			$user_max_scores[$user->id] = array();
			$user_total_scores[$user->id] = 0;
			foreach($problem_cols as $psid=>$problems){
				$user_max_scores[$user->id][$psid] = $user->max_scores($psid, $problem_cols[$psid]);
				foreach($problems as $problem){
					if(intval($user_max_scores[$user->id][$psid][$problem->id]) >= 0)
						$user_total_scores[$user->id] += intval($user_max_scores[$user->id][$psid][$problem->id]);
				}
			}
		}

		return view('group.homework_status', [
			'group' => $group,
			'problem_cols' => $problem_cols,
			'total_score' => $total_score,
			'user_max_scores' => $user_max_scores,
			'user_total_scores' => $user_total_scores,
		]);
	}
}
