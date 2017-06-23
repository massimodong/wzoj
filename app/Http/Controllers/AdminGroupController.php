<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Validator;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class AdminGroupController extends Controller
{
	public function getGroups($id = -1){
		if($id == -1){
			$groups = \App\Group::all();
			return view('admin.groups_index',['groups' => $groups]);
		}else{
			$group = \App\Group::findOrFail($id);
			$ac_users = array();
			$homework_done = array();
			foreach($group->homeworks as $problem){
				$ac_users[$problem->id] = \App\User::whereIn('id', function($query) use($group, $problem){
					$query->select('user_id')
					      ->distinct()
					      ->from('solutions')
					      ->where('problem_id', '=', $problem->id)
					      ->where('score', '>=', 100)
					      ->whereIn('user_id', function($query) use($group){
						      $query->select('user_id')
							    ->from('group_user')
							    ->where('group_id', '=', $group->id);
					      });
				})->get();
				if(count($ac_users[$problem->id]) == count($group->users)){
					$homework_done[$problem->id] = true;
				}else{
					$homework_done[$problem->id] = false;
				}
			}
			return view('admin.groups_edit',[
				'group' => $group,
				'ac_users' => $ac_users,
				'homework_done' => $homework_done,
			]);
		}
	}

	public function postGroups(Request $request){
		$group = new \App\Group;
		$group->save();
		return redirect('/admin/groups/'.$group->id);
	}

	public function postUsers($gid, Request $request){
		$group = \App\Group::findOrFail($gid);

		foreach($request->uids as $uid){
			$arr = [];
			$arr['user_id'] = $uid;
			$validator = Validator::make($arr,[
				'user_id' => 'required|exists:users,id|unique:group_user,user_id,NULL,id,group_id,'.$gid,
			]);
			if(!$validator->fails()){
				$group->users()->attach($uid);
			}
		}
		return back();
	}

	public function postHomeworks($gid, Request $request){
		$group = \App\Group::findOrFail($gid);

		foreach($request->pids as $pid){
			$arr = [];
			$arr['problem_id'] = $pid;
			$validator = Validator::make($arr,[
				'problem_id' => 'required|exists:problems,id|unique:homeworks,problem_id,NULL,id,group_id,'.$gid,
			]);
			if(!$validator->fails()){
				$group->homeworks()->attach($pid, ['problemset_id' => $request->psid]);
			}
		}
		return back();
	}

	public function putGroups(Request $request,$id){
		$group = \App\Group::findOrFail($id);
		$group->name = $request->name;
		$group->save();
		return back();
	}

	public function deleteGroups($gid){
		$group = \App\Group::findOrFail($gid);
		$group->delete();
		return redirect('/admin/groups');
	}

	public function deleteUsers($gid, Request $request){
		$group = \App\Group::findOrFail($gid);
		foreach($request->id as $uid){
			$group->users()->detach($uid);
		}
		return back();
	}

	public function deleteHomeworks($gid, Request $request){
		$group = \App\Group::findOrFail($gid);
		foreach($request->id as $pid){
			$group->homeworks()->detach($pid);
		}
		return back();
	}
}
