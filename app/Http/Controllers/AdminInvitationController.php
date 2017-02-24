<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Invitation;

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

	public function putInvitationsId($id,Request $request){
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
	public function putInvitations(Request $request){
		$query = Invitation::whereIn('id', $request->id);
		switch($request->action){
			case 'set_always_available':
				$query->update(['remaining' => '-1']);
				break;
			case 'set_once_available':
				$query->update(['remaining' => '1']);
				break;
			case 'set_non_available':
				$query->update(['remaining' => '0']);
				break;
			case 'set_private':
				$query->update(['private' => true]);
				break;
			case 'set_public':
				$query->update(['private' => false]);
				break;
			case 'delete':
				$query->delete();
				break;
		}
		return back();
	}

	public function deleteInvitations($iid,$gid){
		$invitation = \App\Invitation::findOrFail($iid);
		$invitation->groups()->detach($gid);
		return back();
	}

}
