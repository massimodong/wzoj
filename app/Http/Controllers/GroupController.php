<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Group;
use Gate;
use Auth;
use Cache;
use DB;

class GroupController extends Controller
{
	public function getHomeworkStatus($id, Request $request){
		$group = Group::findOrFail($id);

		if(Gate::denies('view', $group)){
			if(Auth::check()) abort(403);
			else return redirect('/auth/login');
		}

		$problemset_ids = [];
		$problem_ids = [];
		$total_score = 0;
		$homeworks = Cache::tags(['group_homeworks'])->rememberForever($group->id, function() use($group){
			return $group->homeworks;
		});

		$user_ids = $group->users->map(function($item, $key){
			return $item->id;
		})->toArray();

		foreach($homeworks as $problem){
			array_push($problemset_ids, $problem->pivot->problemset_id);
			array_push($problem_ids, $problem->id);
			$total_score += 100;
		}
		$problemset_ids = array_unique($problemset_ids);
		$problem_ids = array_unique($problem_ids);

    $result = \App\Solution::whereIn('user_id', $user_ids)
                           ->whereIn('problem_id', $problem_ids)
                           ->whereIn('problemset_id', $problemset_ids)
                           ->groupBy(['user_id', 'problem_id', 'problemset_id'])
                           ->get([DB::raw('MAX(score) as score'), 'user_id', 'problem_id', 'problemset_id']);

    //initialize scores table
    $user_max_scores = array();
    foreach($user_ids as $uid){
      $user_max_scores[$uid] = [];
      foreach($problemset_ids as $psid){
        $user_max_scores[$uid][$psid] = [];
        foreach($problem_ids as $pid){
          $user_max_scores[$uid][$psid][$pid] = -1;
        }
      }
    }

    foreach($result as $l){
      $user_max_scores[$l->user_id][$l->problemset_id][$l->problem_id] = $l->score;
    }


		$user_total_scores = array();
		foreach($group->users as $user){
			$user_total_scores[$user->id]=0;
			foreach($homeworks as $problem){
				$s = $user_max_scores[$user->id][$problem->pivot->problemset_id][$problem->id];
				if($s>0) $user_total_scores[$user->id]+=$s;
			}
		}

		return view('group.homework_status', [
			'group' => $group,
			'problems' => $homeworks,
			'total_score' => $total_score,
			'user_max_scores' => $user_max_scores,
			'user_total_scores' => $user_total_scores,
		]);
	}
}
