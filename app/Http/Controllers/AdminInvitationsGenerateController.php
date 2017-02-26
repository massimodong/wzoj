<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Invitation;

class AdminInvitationsGenerateController extends Controller
{
	public function getIndex(){
		return view('admin.invitations_generate');
	}

	public function postIndex(Request $request){
		$this->validate($request, [
			'prefix' => 'required|min:3|max:10',
			'class' => 'max:255',
			'remaining' => 'required|integer',
			'fullname' => 'required',
			'private' => 'required|in:0,1',
			'groups_id[]' => 'array',
		]);
		$fullnames = explode("\n", trim($request->fullname));
		foreach($fullnames as $fullname){
			$fullname = trim($fullname);
			$token = $request->prefix.substr(md5($request->class.$fullname.rand(1,99999999)), 0, 20);
			while(Invitation::where('token', $token)->count()){
				$token = $request->prefix.substr(md5($request->class.$fullname.rand(1,99999999)), 0, 20);
			}

			$invitation = Invitation::create([
				'description' => $request->class.'-'.$fullname,
				'fullname' => $fullname,
				'class' => $request->class,
				'token' => $token,
				'remaining' => $request->remaining,
				'private' => $request->private,
			]);

			if(isset($request->groups_id) && count($request->groups_id)){
				foreach($request->groups_id as $group_id){
					$invitation->groups()->attach($group_id);
				}
			}
		}
		return back();
	}
}
