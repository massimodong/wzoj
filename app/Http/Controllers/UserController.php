<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Gate;

use App\User;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\FileManager;

use Cache;

class UserController extends Controller
{
	public function getId($id){
		$user = User::findOrFail($id);
		$groups = Cache::tags(['user_groups'])->rememberForever($user->id, function() use($user){
			return $user->groups;
		});
		
		$cur_month = intval(date("m")) + 1;
		$cur_year = intval(date("Y"));

		$month_cnt = 6;
		$month_no = [];
		$month_submit_cnt = [];
		$month_ac_cnt = [];

		for($i=$month_cnt-1;$i>=0;--$i){
			--$cur_month;
			if($cur_month <= 0){
				$cur_month += 12;
				--$cur_year;
			}
			$month_no[$i] = $cur_month;
			$month_start = $cur_year."-".sprintf("%02d", $cur_month)."-00 00:00:00";

			$next_month = $cur_month + 1;
			$next_year = $cur_year;
			if($next_month >= 13){
				$next_month -= 12;
				++$next_year;
			}

			$month_end = $next_year."-".sprintf("%02d", $next_month)."-00 00:00:00";
	//		echo $month_no[$i].":".$month_start." - ".$month_end."<br>";

			$month_submit_cnt[$i] = $user->solutions()
				->where('created_at', '>=', $month_start)
				->where('created_at', '<', $month_end)
				->count();

			$month_ac_cnt[$i] = $user->solutions()
				->where('created_at', '>=', $month_start)
				->where('created_at', '<', $month_end)
				->where('score','>=' , 100)
				->count();

			//echo $month_no[$i].":".$month_submit_cnt[$i].":".$month_ac_cnt[$i]."<br>";
		}

		$cnt_submissions = $user->solutions()->count();

		$last_solutions = $user->solutions()->public()->orderBy('created_at', 'desc')->take(5)->get();

		return view('user.profile',['user' => $user,
						'month_cnt' => $month_cnt,
						'month_no' => $month_no,
						'month_submit_cnt' => $month_submit_cnt,
						'month_ac_cnt' => $month_ac_cnt,
						'cnt_submissions' => $cnt_submissions,
						'last_solutions' => $last_solutions,
						'groups' => $groups,
		]);
	}

	public function postId($id,Request $request){
		$this->validate($request, [
				'fullname' => 'max:255',
				'class'    => 'max:255',
		]);
		$user = User::findOrFail($id);
		$profile_changed = false;

		if(Gate::allows('change_fullname' , $user)){
			$profile_changed = true;
			$user->fullname = $request->fullname;
		}

		if(Gate::allows('change_class' , $user)){
			$profile_changed = true;
			$user->class = $request->class;
		}

		if(Gate::allows('change_lock' , $user)){
			$profile_changed = true;
			$user->fullname_lock = $request->fullname_lock;
			$user->class_lock = $request->class_lock;
		}
		if(Gate::allows('change_description', $user)){
			$profile_changed = true;
			$user->description = $request->description;
		}

		if($profile_changed){
			$user->save();
		}

		return back();
	}

	public function putUsers(Request $request){
		$query = User::whereIn('id', $request->id);
		switch($request->action){
			case 'lock_fullname':
				$query->update(['fullname_lock'=> true]);
				break;
			case 'lock_class':
				$query->update(['class_lock'=> true]);
				break;
			case 'unlock_fullname':
				$query->update(['fullname_lock'=> false]);
				break;
			case 'unlock_class':
				$query->update(['class_lock'=> false]);
				break;
		}
		return back();
	}

	public function getUserFiles($id, Request $request){
		$user = User::findOrFail($id);

		$this->authorize('view_files', $user);

		$can_modify = Gate::allows('modify_files', $user);

		return FileManager::getRequests($request, [
			'disk' => 'files',
			'basepath' => strval($user->id),
			'title' => $user->name.'-'.trans('wzoj.files'),
			'modify' => $can_modify,
		]);
	}

	public function postUserFiles($id, Request $request){
		$user = User::findOrFail($id);

		$this->authorize('modify_files', $user);

		return FileManager::postRequests($request, [
			'disk' => 'files',
			'basepath' => strval($user->id),
		]);
	}
}
