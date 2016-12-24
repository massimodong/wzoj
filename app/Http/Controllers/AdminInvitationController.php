<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class AdminInvitationController extends Controller
{
	public function getInvitations($id = -1){
		if($id == -1){
			$invitations = \App\Invitation::all();
			return view('admin.invitations_index',['invitations' => $invitations]);
		}else{
			$invitation = \App\Invitation::findOrFail($id);
			return view('admin.invitations_edit',['invitation' => $invitation]);
		}
	}

	public function postInvitations(Request $request,$id = -1){
		if($id == -1){
			$invitation = new \App\Invitation;
			$invitation->save();
			return redirect('/admin/invitations/'.$invitation->id);
		}else{
			$invitation = \App\Invitation::findOrFail($id);
			$this->validate($request,[
					'group_id' => 'required|exists:groups,id|unique:group_invitation,group_id,NULL,id,invitation_id,'.$id,
			]);

			$invitation->groups()->attach($request->group_id);
			return back();
		}
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

	public function deleteInvitations($iid,$gid){
		$invitation = \App\Invitation::findOrFail($iid);
		$invitation->groups()->detach($gid);
		return back();
	}

}
