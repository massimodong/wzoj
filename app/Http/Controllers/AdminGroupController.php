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
			return view('admin.groups_edit',['group' => $group]);
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
}
