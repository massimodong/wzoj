<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Gate;

use App\User;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
	public function getId($id){
		$user = User::findOrFail($id);
		return view('user.profile',['user' => $user]);
	}

	public function postId($id,Request $request){
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

		if($profile_changed){
			$user->save();
		}

		return back();
	}
}
