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

		$problems = collect(new \App\Problem);
		$problemset_ids = [];
		$problem_ids = [];
		$total_score = 0;
		$homeworks = Cache::tags(['group_homeworks'])->rememberForever($group->id, function() use($group){
			return $group->homeworks;
		});
		foreach($homeworks as $problem){
			array_push($problemset_ids, $problem->pivot->problemset_id);
			array_push($problem_ids, $problem->id);
			$problems->push($problem);
			$total_score += 100;
		}

		$user_ids = $group->users->map(function($item, $key){
			return $item->id;
		})->toArray();
		$problemset_ids = array_unique($problemset_ids);
		$problem_ids = array_unique($problem_ids);

		$user_max_scores = max_scores($user_ids, $problemset_ids, $problem_ids);

		$user_total_scores = array();
		foreach($group->users as $user){
			$user_total_scores[$user->id]=0;
			foreach($problems as $problem){
				$s = $user_max_scores[$user->id][$problem->pivot->problemset_id][$problem->id];
				if($s>0) $user_total_scores[$user->id]+=$s;
			}
		}

		return view('group.homework_status', [
			'group' => $group,
			'problems' => $problems,
			'total_score' => $total_score,
			'user_max_scores' => $user_max_scores,
			'user_total_scores' => $user_total_scores,
		]);
	}
}
