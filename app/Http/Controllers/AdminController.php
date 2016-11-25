<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
 	public function __construct(){
		$this->middleware('auth');
		$this->middleware('admin');
	}
	public function getIndex(){
		return view('admin.home');
	}

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

	public function getInvitations($id = -1){
		if($id == -1){
			$invitations = \App\Invitation::all();
			return view('admin.invitations_index',['invitations' => $invitations]);
		}else{
			$invitation = \App\Invitation::findOrFail($id);
			return view('admin.invitations_edit',['invitation' => $invitation]);
		}
	}

	public function postInvitations(){
		$invitation = new \App\Invitation;
		$invitation->save();
		return redirect('/admin/invitations/'.$invitation->id);
	}

	public function putInvitations($id,Request $request){
		$invitation = \App\Invitation::findOrFail($id);
		$this->validate($request,[
			'remaining' => 'required|integer',
			'private'   => 'in:1',
		]);

		$invitation->description = $request->description;
		$invitation->fullname    = $request->fullname;
		$invitation->class       = $request->class;
		$invitation->token       = $request->token;
		$invitation->remaining   = $request->remaining;
		$invitation->private     = $request->private;

		$invitation->save();
		return back();
	}
}
