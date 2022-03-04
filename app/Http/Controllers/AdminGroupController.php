<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Validator;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Cache;

class AdminGroupController extends Controller
{
	public function getGroups(Request $request, $id = -1){
		if($id == -1){
			$groups = $request->user()->manage_groups()->get();
			return view('admin.groups_index',['groups' => $groups]);
		}else{
			$group = \App\Group::findOrFail($id);
			$this->authorize('manage', $group);

			return view('admin.groups_edit',[
				'group' => $group,
			]);
		}
	}

	public function postGroups(Request $request){
		$this->authorize('create', \App\Group::class);
		$group = new \App\Group;
		$group->manager_id = $request->user()->id;
		$group->save();
		return redirect('/admin/groups/'.$group->id);
	}

	public function postUsers($gid, Request $request){
		$group = \App\Group::findOrFail($gid);
		$this->authorize('manage', $group);

		foreach($request->uids as $uid){
			$arr = [];
			$arr['user_id'] = $uid;
			$validator = Validator::make($arr,[
				'user_id' => 'required|exists:users,id|unique:group_user,user_id,NULL,id,group_id,'.$gid,
			]);
			if(!$validator->fails()){
				$group->users()->attach($uid);
				Cache::tags(['user_groups'])->forget($uid);
			}
		}
		return back();
	}

	public function postHomeworks($gid, Request $request){
		$group = \App\Group::findOrFail($gid);
		$this->authorize('manage', $group);

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
		Cache::tags(['group_homeworks'])->forget($gid);
		return back();
	}

	public function putGroups(Request $request,$id){
		$group = \App\Group::findOrFail($id);
		$this->authorize('manage', $group);

		$group->name = $request->name;
		$group->notice = $request->notice;
		if($request->user()->has_role('admin')){
			$group->manager_id = $request->manager;
		}
		$group->save();
		Cache::tags(['user_groups'])->flush();
		return back();
	}

	public function deleteGroups($gid){
		$group = \App\Group::findOrFail($gid);
		$this->authorize('manage', $group);

		$group->delete();
		Cache::tags(['user_groups'])->flush();
		return redirect('/admin/groups');
	}

	public function deleteUsers($gid, Request $request){
		$group = \App\Group::findOrFail($gid);
		$this->authorize('manage', $group);

		foreach($request->id as $uid){
			$group->users()->detach($uid);
			Cache::tags(['user_groups'])->forget($uid);
		}
		return back();
	}

	public function deleteHomeworks($gid, Request $request){
		$group = \App\Group::findOrFail($gid);
		$this->authorize('manage', $group);

		foreach($request->id as $pid){
			$group->homeworks()->detach($pid);
		}
		Cache::tags(['group_homeworks'])->forget($gid);
		return back();
	}
}
