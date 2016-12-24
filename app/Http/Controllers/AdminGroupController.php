<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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

	public function postGroups(Request $request , $id = -1){
		if($id==-1){
			$group = new \App\Group;
			$group->save();
			return redirect('/admin/groups/'.$group->id);
		}else{
			$group = \App\Group::findOrFail($id);

			$this->validate($request,[
					'user_id' => 'required|exists:users,id|unique:group_user,user_id,NULL,id,group_id,'.$id,
			]);

			$group->users()->attach($request->user_id);
			return back();
		}
	}

	public function putGroups(Request $request,$id){
		$group = \App\Group::findOrFail($id);
		$group->name = $request->name;
		$group->save();
		return back();
	}

	public function deleteGroups($gid,$uid = -1){
		if($uid == -1){
			$group = \App\Group::findOrFail($gid);
			$group->delete();
			return redirect('/admin/groups');
		}else{
			$group = \App\Group::findOrFail($gid);

			$group->users()->detach($uid);
			return back();
		}
	}


}
